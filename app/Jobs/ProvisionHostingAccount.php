<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Server;
use App\Models\Service;
use App\Notifications\ProvisioningComplete;
use App\Notifications\ProvisioningFailed;
use App\Services\Integrations\DirectAdmin\DirectAdminClient;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProvisionHostingAccount implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(
        public Order $order
    ) {}

    public function handle(): void
    {
        // Find all unpaid order items for hosting products
        $items = $this->order->items()->whereHas('product', function ($q) {
            $q->whereIn('type', ['shared_hosting', 'reseller', 'vps']);
        })->get();

        foreach ($items as $item) {
            $this->provisionItem($item);
        }
    }

    protected function provisionItem(OrderItem $item): void
    {
        // Skip if already provisioned
        $service = Service::where('order_item_id', $item->id)->first();
        if (! $service || $service->provisioned_at) {
            return;
        }

        // Skip if no server assigned yet
        if (! $service->server_id) {
            $server = $this->pickServer();
            if (! $server) {
                $service->update([
                    'provisioning_errors' => 'No available server in pool.',
                ]);
                $this->order->customer->user->notify(
                    new ProvisioningFailed($service, 'No available server found.')
                );
                return;
            }
            $service->update(['server_id' => $server->id]);
        }

        $server = Server::find($service->server_id);
        if (! $server || ! $server->is_active) {
            $service->update([
                'provisioning_errors' => 'Assigned server is inactive.',
            ]);
            return;
        }

        $client = new DirectAdminClient($server);
        $result = $client->createUser($service);

        if ($result['success']) {
            $billingCycle = $item->pricing->billing_cycle;
            $nextDueDate = $this->calculateNextDueDate($billingCycle);

            $service->update([
                'username' => $result['username'],
                'status' => 'active',
                'provisioned_at' => now(),
                'provisioning_errors' => null,
                'next_due_date' => $nextDueDate,
            ]);

            $server->increment('current_accounts');

            $this->order->customer->user->notify(
                new ProvisioningComplete($service, $result['username'], $result['password'])
            );

            Log::info("Provisioned hosting account: {$result['username']} for order #{$this->order->id}");
        } else {
            $error = $result['error'] ?? 'Unknown error';
            $service->update([
                'provisioning_errors' => $error,
            ]);

            $this->order->customer->user->notify(
                new ProvisioningFailed($service, $error)
            );

            Log::error("Provisioning failed for order #{$this->order->id}: {$error}");
        }
    }

    protected function pickServer(): ?Server
    {
        return Server::where('is_active', true)
            ->whereRaw('current_accounts < max_accounts')
            ->orderBy('current_accounts', 'asc')
            ->first();
    }

    protected function calculateNextDueDate(string $billingCycle): Carbon
    {
        return match ($billingCycle) {
            'monthly' => now()->addMonth(),
            'quarterly' => now()->addMonths(3),
            'semiannual' => now()->addMonths(6),
            'annual' => now()->addYear(),
            'biennial' => now()->addYears(2),
            default => now()->addMonth(),
        };
    }
}