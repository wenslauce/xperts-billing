<?php

namespace App\Services\Integrations\Paystack;

use App\Models\Invoice;
use App\Services\PaymentGateway;
use Illuminate\Support\Facades\Http;

class PaystackClient
{
    public function __construct(
        protected PaymentGateway $gateway
    ) {}

    public function initializeTransaction(Invoice $invoice): ?array
    {
        $response = Http::withToken($this->gateway->paystackSecret())
            ->post('https://api.paystack.co/transaction/initialize', [
                'email' => $invoice->customer->user->email,
                'amount' => (int) ($invoice->total * 100),
                'currency' => $invoice->currency,
                'reference' => 'INV-' . $invoice->id . '-' . time(),
                'callback_url' => route('checkout.success', ['invoice' => $invoice->id]),
                'metadata' => [
                    'invoice_id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                ],
            ]);

        return $response->successful() ? $response->json() : null;
    }

    public function verifyTransaction(string $reference): ?array
    {
        $response = Http::withToken($this->gateway->paystackSecret())
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        return $response->successful() ? $response->json() : null;
    }

    public function verifyWebhook(string $payload, string $signature): bool
    {
        $expected = hash_hmac('sha512', $payload, $this->gateway->paystackSecret());
        return hash_equals($expected, $signature);
    }
}