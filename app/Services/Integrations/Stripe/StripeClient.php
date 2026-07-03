<?php

namespace App\Services\Integrations\Stripe;

use App\Models\Invoice;
use App\Services\PaymentGateway;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeClient
{
    public function __construct(
        protected PaymentGateway $gateway
    ) {
        Stripe::setApiKey($this->gateway->stripeSecret());
    }

    public function createCheckoutSession(Invoice $invoice): Session
    {
        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => strtolower($invoice->currency),
                    'product_data' => [
                        'name' => 'Invoice ' . $invoice->invoice_number,
                    ],
                    'unit_amount' => (int) ($invoice->total * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout.success', ['invoice' => $invoice->id]),
            'cancel_url' => route('checkout.cancel', ['invoice' => $invoice->id]),
            'metadata' => [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
            ],
        ]);
    }

    public function verifyWebhook(string $payload, string $sigHeader): ?array
    {
        $event = \Stripe\Webhook::constructEvent(
            $payload, $sigHeader, $this->gateway->stripeWebhookSecret()
        );
        return $event->toArray();
    }
}