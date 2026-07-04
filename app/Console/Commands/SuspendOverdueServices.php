<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\Service;
use App\Notifications\ServiceSuspended;
use App\Notifications\ServiceUnsuspended;
use App\Services\Integrations\DirectAdmin\DirectAdminClient;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SuspendOverdueServices extends Command
{
    protected $signature = 'services:check-suspension';
    protected $description = 'Suspend services with overdue invoices past grace period, unsuspend on payment';

    public function handle(): int
    {
        $this->info('Checking service suspensions...');
        $gracePeriod = (int) config('app.suspension_grace_days', 7);

        // Suspend services where invoices are overdue beyond grace period
        $overdueInvoices = Invoice::whereIn('status', ['unpaid', 'overdue'])
            ->where('due_date', '<', now()->subDays($gracePeriod))
            ->with('customer')
            ->get();

        $suspended = 0;
        foreach ($overdueInvoices as $invoice) {
            $services = Service::where('customer_id', $invoice->customer_id)
                ->where('status', 'active')
                ->whereNotNull('server_id')
                ->get();

            foreach ($services as $service) {
                $server = $service->server;
                if (! $server || ! $server->is_active) continue;

                try {
                    $client = new DirectAdminClient($server);
                    $result = $client->suspendUser($service->username);

                    if ($result) {
                        $service->update(['status' => 'suspended']);
                        if ($service->customer->user) {
                            $service->customer->user->notify(new ServiceSuspended($service, $invoice));
                        }
                        $suspended++;
                        $this->info("Suspended service #{$service->id} ({$service->username})");
                    }
                } catch (\Exception $e) {
                    Log::error("Suspension failed for service #{$service->id}: {$e->getMessage()}");
                }
            }
        }

        // Unsuspend services where overdue invoices have been paid
        $suspendedServices = Service::where('status', 'suspended')
            ->whereNotNull('server_id')
            ->get();

        $unsuspended = 0;
        foreach ($suspendedServices as $service) {
            $hasUnpaidOverdue = Invoice::where('customer_id', $service->customer_id)
                ->whereIn('status', ['unpaid', 'overdue'])
                ->exists();

            if (! $hasUnpaidOverdue) {
                $server = $service->server;
                if (! $server || ! $server->is_active) continue;

                try {
                    $client = new DirectAdminClient($server);
                    $result = $client->unsuspendUser($service->username);

                    if ($result) {
                        $service->update(['status' => 'active']);
                        if ($service->customer->user) {
                            $service->customer->user->notify(new ServiceUnsuspended($service));
                        }
                        $unsuspended++;
                        $this->info("Unsuspended service #{$service->id} ({$service->username})");
                    }
                } catch (\Exception $e) {
                    Log::error("Unsuspension failed for service #{$service->id}: {$e->getMessage()}");
                }
            }
        }

        $this->info("Suspended {$suspended} service(s), unsuspended {$unsuspended} service(s).");
        return Command::SUCCESS;
    }
}