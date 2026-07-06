<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Checkout\StripeController;
use App\Http\Controllers\Checkout\PaystackController;
use App\Http\Controllers\DomainCheckController;
use App\Models\Product;

Route::get('/', function () {
    $hostingProducts = Product::with('pricing')->where('type', 'hosting')->where('is_active', true)->get();
    return view('welcome', compact('hostingProducts'));
});
Route::get('/hosting', [DomainCheckController::class, 'hosting'])->name('hosting');
Route::get('/domains/check', [DomainCheckController::class, 'check'])->name('domain.check');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Checkout routes
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout/stripe/{invoice}', [StripeController::class, 'checkout'])->name('checkout.stripe');
    Route::get('/checkout/paystack/{invoice}', [PaystackController::class, 'checkout'])->name('checkout.paystack');
    Route::get('/checkout/success', fn () => view('checkout.success'))->name('checkout.success');
    Route::get('/checkout/cancel', fn () => view('checkout.cancel'))->name('checkout.cancel');
});

// Paystack callback (no auth required - redirected from Paystack)
Route::get('/paystack/callback', [PaystackController::class, 'callback'])->name('paystack.callback');

// Social Login
Route::get('/auth/{provider}', [App\Http\Controllers\Auth\SocialLoginController::class, 'redirect'])->name('social.login');
Route::get('/auth/{provider}/callback', [App\Http\Controllers\Auth\SocialLoginController::class, 'callback']);

require __DIR__.'/auth.php';