<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webhook\StripeWebhookController;
use App\Http\Controllers\Webhook\PaystackWebhookController;
use App\Http\Controllers\Webhook\EmailWebhookController;

Route::post('/webhooks/stripe', [StripeWebhookController::class, 'handle'])->name('webhooks.stripe');
Route::post('/webhooks/paystack', [PaystackWebhookController::class, 'handle'])->name('webhooks.paystack');
Route::post('/webhooks/email', [EmailWebhookController::class, 'handle'])->name('webhooks.email');
