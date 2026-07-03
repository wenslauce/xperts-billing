<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Jobs\ProvisionHostingAccount;
use App\Notifications\PaymentReceived;
use App\Services\Integrations\Paystack\PaystackClient;
use App\Services\PaymentGateway;
use Illuminate\Http\Request;

class PaystackWebhookController extends Controller
{
    public function handle(Request $request, PaymentGateway $gateway)
    {
        $payload = $request->getContent();
        $signature = $request->header('x-paystack-signature');

        $client = new PaystackClient($gateway);
        if (! $client->verifyWebhook($payload, $signature)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $event = json_decode($payload, true);
        $eventType = $event['event'] ?? '';

        if ($eventType === 'charge.success') {
            $data = $event['data'] ?? [];
            $metadata = $data['metadata'] ?? [];
            $invoiceId = $metadata['invoice_id'] ?? null;

            if ($invoiceId) {
                $invoice = Invoice::find($invoiceId);
                if ($invoice && $invoice->status !== 'paid') {
                    $invoice->update(['status' => 'paid', 'paid_at' => now()]);
                    $transaction = $invoice->transactions()->create([
                        'gateway' => 'paystack',
                        'gateway_reference' => $data['reference'] ?? '',
                        'amount' => ($data['amount'] ?? 0) / 100,
                        'currency' => $data['currency'] ?? 'KES',
                        'status' => 'succeeded',
                        'raw_payload' => $payload,
                    ]);

                    if ($invoice->customer->user) {
                        $invoice->customer->user->notify(new PaymentReceived($invoice, $transaction));
                    }

                    // Dispatch provisioning for hosting products
                    if ($invoice->order) {
                        ProvisionHostingAccount::dispatch($invoice->order);
                    }
                }
            }
        }

        if ($eventType === 'refund.processed') {
            $data = $event['data'] ?? [];
            $invoiceId = $data['metadata']['invoice_id'] ?? null;

            if ($invoiceId) {
                $invoice = Invoice::find($invoiceId);
                if ($invoice) {
                    $invoice->transactions()->create([
                        'gateway' => 'paystack',
                        'gateway_reference' => $data['reference'] ?? '',
                        'amount' => ($data['amount'] ?? 0) / 100,
                        'currency' => $data['currency'] ?? 'KES',
                        'status' => 'refunded',
                        'raw_payload' => $payload,
                    ]);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}