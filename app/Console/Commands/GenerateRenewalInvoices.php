<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\Service;
use App\Services\Billing\InvoiceGenerator;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateRenewalInvoices extends Command
{
    protected $signature = 'invoices:generate-renewals';
    protected $description = 'Generate renewal invoices for services approaching next_due_date';

    public function handle(): int
    {
        $this->info('Generating renewal invoices...');

        // Find services that need renewal invoices (next_due_date within 7 days, no existing unpaid invoice)
        $services = Service::where('status', 'active')
            ->whereNotNull('next_due_date')
            ->where('next_due_date', '<=', now()->addDays(7))
            ->where('next_due_date', '>=', now())
            ->with(['customer', 'product', 'orderItem.pricing'])
            ->get();

        $generated = 0;
        foreach ($services as $service) {
            // Check if there's already an unpaid invoice for this service
            $existingInvoice = Invoice::where('customer_id', $service->customer_id)
                ->whereHas('items', fn($q) => $q->where('service_id', $service->id))
                ->whereIn('status', ['unpaid', 'overdue'])
                ->exists();

            if ($existingInvoice) {
                continue;
            }

            // Create a renewal order for this service
            $order = \App\Models\Order::create([
                'customer_id' => $service->customer_id,
                'status' => 'awaiting_payment',
                'total' => $service->orderItem->unit_price,
                'currency' => $service->orderItem->pricing->currency ?? 'KES',
                'notes' => 'Renewal for ' . ($service->product->name ?? 'Service') . ' (' . $service->domain ?? 'N/A' . ')',
            ]);

            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $service->product_id,
                'pricing_id' => $service->orderItem->pricing_id,
                'quantity' => 1,
                'unit_price' => $service->orderItem->unit_price,
            ]);

            // Generate invoice
            $invoiceGenerator = new \App\Services\Billing\InvoiceGenerator();
            $invoice = $invoiceGenerator->generate($order);

            $this->info("Generated renewal invoice {$invoice->invoice_number} for service #{$service->id}");
            $generated++;
        }

        $this->info("Generated {$generated} renewal invoice(s).");
        return Command::SUCCESS;
    }
}