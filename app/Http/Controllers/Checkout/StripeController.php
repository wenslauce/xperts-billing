<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\Integrations\Stripe\StripeClient;
use App\Services\PaymentGateway;

class StripeController extends Controller
{
    public function checkout(Invoice $invoice, PaymentGateway $gateway)
    {
        if ($invoice->status === 'paid') {
            return redirect()->route('customer.invoices.show', $invoice)
                ->with('info', 'This invoice is already paid.');
        }

        $client = new StripeClient($gateway);
        $session = $client->createCheckoutSession($invoice);

        return redirect($session->url);
    }
}