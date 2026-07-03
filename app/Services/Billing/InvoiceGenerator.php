<?php

namespace App\Services\Billing;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Pricing;
use Carbon\Carbon;

class InvoiceGenerator
{
    public function generate(Order $order): Invoice
    {
        $customer = $order->customer;
        $taxRate = config('app.vat_rate', 16) / 100;

        $subtotal = $order->total;
        $tax = round($subtotal * $taxRate, 2);
        $total = $subtotal + $tax;

        $invoice = Invoice::create([
            'customer_id' => $customer->id,
            'order_id' => $order->id,
            'invoice_number' => $this->nextInvoiceNumber(),
            'status' => 'unpaid',
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'currency' => $order->currency,
            'due_date' => now()->addDays(7),
        ]);

        foreach ($order->items as $item) {
            $invoice->items()->create([
                'description' => $item->product->name . ' (' . $item->pricing->billing_cycle . ')',
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total' => $item->unit_price * $item->quantity,
            ]);
        }

        return $invoice;
    }

    protected function nextInvoiceNumber(): string
    {
        $last = Invoice::whereYear('created_at', now()->year)->count();
        return 'INV-' . now()->year . '-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }
}