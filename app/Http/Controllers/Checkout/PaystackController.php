<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\Integrations\Paystack\PaystackClient;
use App\Services\PaymentGateway;
use Illuminate\Http\Request;

class PaystackController extends Controller
{
    public function checkout(Invoice $invoice, PaymentGateway $gateway)
    {
        if ($invoice->status === 'paid') {
            return redirect()->route('customer.invoices.show', $invoice)
                ->with('info', 'This invoice is already paid.');
        }

        $client = new PaystackClient($gateway);
        $result = $client->initializeTransaction($invoice);

        if (! $result || ! $result['status']) {
            return redirect()->route('customer.invoices.show', $invoice)
                ->with('error', 'Failed to initialize payment. Please try again.');
        }

        return view('checkout.paystack', [
            'invoice' => $invoice,
            'publicKey' => $gateway->paystackPublicKey(),
            'reference' => $result['data']['reference'],
            'amount' => (int) ($invoice->total * 100),
            'email' => $invoice->customer->user->email,
        ]);
    }

    public function callback(Request $request, PaymentGateway $gateway)
    {
        $reference = $request->query('reference');
        if (! $reference) {
            return redirect()->route('customer.invoices.index')
                ->with('error', 'Invalid payment reference.');
        }

        $client = new PaystackClient($gateway);
        $result = $client->verifyTransaction($reference);

        if ($result && $result['status'] && $result['data']['status'] === 'success') {
            $invoiceId = $result['data']['metadata']['invoice_id'] ?? null;
            if ($invoiceId) {
                $invoice = Invoice::find($invoiceId);
                if ($invoice && $invoice->status !== 'paid') {
                    $invoice->update(['status' => 'paid', 'paid_at' => now()]);
                    $invoice->transactions()->create([
                        'gateway' => 'paystack',
                        'gateway_reference' => $reference,
                        'amount' => $invoice->total,
                        'currency' => $invoice->currency,
                        'status' => 'succeeded',
                        'raw_payload' => json_encode($result),
                    ]);
                }
            }
        }

        return redirect()->route('customer.invoices.index')
            ->with('success', 'Payment completed successfully.');
    }
}