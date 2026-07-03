<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ProvisionHostingAccount;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['customer.user'])->latest()->paginate(10);
        return view('admin.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['customer.user', 'items', 'transactions', 'order']);
        return view('admin.invoices.show', compact('invoice'));
    }

    public function markPaid(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'gateway' => 'required|string|in:manual,bank',
            'reference' => 'nullable|string|max:255',
        ]);

        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        Transaction::create([
            'invoice_id' => $invoice->id,
            'gateway' => $validated['gateway'],
            'gateway_reference' => $validated['reference'],
            'amount' => $invoice->total,
            'currency' => $invoice->currency,
            'status' => 'succeeded',
        ]);

        // Dispatch provisioning for hosting products
        if ($invoice->order) {
            ProvisionHostingAccount::dispatch($invoice->order);
        }

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Invoice marked as paid successfully.');
    }
}