<?php

namespace App\Services;

use App\Models\Setting;

class PaymentGateway
{
    protected array $config = [];

    public function __construct()
    {
        $this->loadConfig();
    }

    protected function loadConfig(): void
    {
        $settings = Setting::whereIn('key', [
            'payment_mode',
            'stripe_test_key', 'stripe_test_secret', 'stripe_test_webhook_secret',
            'stripe_live_key', 'stripe_live_secret', 'stripe_live_webhook_secret',
            'paystack_test_public_key', 'paystack_test_secret',
            'paystack_live_public_key', 'paystack_live_secret',
        ])->pluck('value', 'key')->toArray();

        $this->config = $settings;
    }

    public function isLiveMode(): bool
    {
        return ($this->config['payment_mode'] ?? 'test') === 'live';
    }

    public function getMode(): string
    {
        return $this->isLiveMode() ? 'live' : 'test';
    }

    // Stripe
    public function stripeKey(): string
    {
        $key = $this->isLiveMode() ? 'stripe_live_key' : 'stripe_test_key';
        return $this->config[$key] ?? '';
    }

    public function stripeSecret(): string
    {
        $key = $this->isLiveMode() ? 'stripe_live_secret' : 'stripe_test_secret';
        return $this->config[$key] ?? '';
    }

    public function stripeWebhookSecret(): string
    {
        $key = $this->isLiveMode() ? 'stripe_live_webhook_secret' : 'stripe_test_webhook_secret';
        return $this->config[$key] ?? '';
    }

    // Paystack
    public function paystackPublicKey(): string
    {
        $key = $this->isLiveMode() ? 'paystack_live_public_key' : 'paystack_test_public_key';
        return $this->config[$key] ?? '';
    }

    public function paystackSecret(): string
    {
        $key = $this->isLiveMode() ? 'paystack_live_secret' : 'paystack_test_secret';
        return $this->config[$key] ?? '';
    }
}