<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Notifications\InvoiceReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendInvoiceReminders extends Command
{
    protected $signature = 'invoices:send-reminders';
    protected $description = 'Send invoice reminder emails at 7, 3, 1 days before due, on due date, and 3/7 days overdue';

    public function handle(): int
    {
        $this->info('Sending invoice reminders...');

        $thresholds = [7, 3, 1, 0, -3, -7];
        $totalSent = 0;

        foreach ($thresholds as $days) {
            $targetDate = now()->addDays($days)->toDateString();
            $invoices = Invoice::where('status', 'unpaid')
                ->whereDate('due_date', $targetDate)
                ->with('customer.user')
                ->get();

            foreach ($invoices as $invoice) {
                if ($invoice->customer && $invoice->customer->user) {
                    $invoice->customer->user->notify(new \App\Notifications\InvoiceReminder($invoice, $days));
                    $this->info("Sent reminder for {$invoice->invoice_number} ({$days} days offset) to {$invoice->customer->user->email}");
                    $totalSent++;
                }
            }
        }

        $this->info("Sent {$totalSent} invoice reminder(s).");
        return Command::SUCCESS;
    }
}