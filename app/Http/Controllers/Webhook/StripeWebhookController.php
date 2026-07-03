<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Notifications\PaymentReceived;
use App\Services\Integrations\Stripe\StripeClient;
use App\Services\PaymentGateway;
use Illuminate\Http\Request;

class StripeWebhookController extends Controller
{
    public function handle(Request $request, PaymentGateway $gateway)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $client = new StripeClient($gateway);
            $event = $client->verifyWebhook($payload, $sigHeader);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $eventType = $event['type'] ?? '';
        $data = $event['data']['object'] ?? [];

        if ($eventType === 'checkout.session.completed') {
            $invoiceId = $data['metadata']['invoice_id'] ?? null;
            if ($invoiceId) {
                $invoice = Invoice::find($invoiceId);
                if ($invoice && $invoice->status !== 'paid') {
                    $invoice->update(['status' => 'paid', 'paid_at' => now()]);
                    $transaction = $invoice->transactions()->create([
                        'gateway' => 'stripe',
                        'gateway_reference' => $data['payment_intent'] ?? $data['id'],
                        'amount' => $data['amount_total'] / 100,
                        'currency' => strtoupper($data['currency'] ?? 'KES'),
                        'status' => 'succeeded',
                        'raw_payload' => json_encode($event),
                    ]);

                    if ($invoice->customer->user) {
                        $invoice->customer->user->notify(new PaymentReceived($invoice, $transaction));
                    }
                }
            }
        }

        if ($eventType === 'charge.refunded') {
            $invoiceId = $data['metadata']['invoice_id'] ?? null;
            if ($invoiceId) {
                $invoice = Invoice::find($invoiceId);
                if ($invoice) {
                    $invoice->transactions()->create([
                        'gateway' => 'stripe',
                        'gateway_reference' => $data['id'],
                        'amount' => $data['amount_refunded'] / 100,
                        'currency' => strtoupper($data['currency'] ?? 'KES'),
                        'status' => 'refunded',
                        'raw_payload' => json_encode($event),
                    ]);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}