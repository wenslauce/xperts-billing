<?php

namespace App\Console\Commands;

use App\Models\Domain;
use App\Notifications\DomainExpiring;
use Illuminate\Console\Command;

class CheckDomainExpiry extends Command
{
    protected $signature = 'domains:check-expiry';
    protected $description = 'Check for expiring domains and send notifications';

    public function handle(): int
    {
        $this->info('Checking for expiring domains...');

        $daysToCheck = [30, 14, 7, 3, 1, 0, -7, -14, -30];
        $totalNotified = 0;

        foreach ($daysToCheck as $days) {
            $targetDate = now()->addDays($days)->startOfDay();
            $endOfDay = now()->addDays($days)->endOfDay();

            $domains = Domain::whereBetween('expiry_date', [$targetDate, $endOfDay])
                ->where('status', 'active')
                ->with('customer')
                ->get();

            foreach ($domains as $domain) {
                if ($domain->customer) {
                    $domain->customer->notify(new DomainExpiring($domain, $days));
                    $totalNotified++;
                }
            }

            if ($domains->count() > 0) {
                $this->info("Notified {$domains->count()} customers about domains expiring in {$days} day(s)");
            }
        }

        $this->info("Total notifications sent: {$totalNotified}");
        return Command::SUCCESS;
    }
}