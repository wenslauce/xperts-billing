<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\ServerController;
use App\Http\Controllers\Admin\SettingsController;

Route::middleware(['auth', 'verified', 'role:super-admin|admin|support|billing'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', ProductController::class)->middleware('can:manage products');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index')->middleware('can:manage orders');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show')->middleware('can:manage orders');

    // Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index')->middleware('can:manage invoices');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show')->middleware('can:manage invoices');
    Route::post('/invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.mark-paid')->middleware('can:manage payments');

    // Servers
    Route::get('/servers', [ServerController::class, 'index'])->name('servers.index')->middleware('can:manage servers');
    Route::get('/servers/create', [ServerController::class, 'create'])->name('servers.create')->middleware('can:manage servers');
    Route::post('/servers', [ServerController::class, 'store'])->name('servers.store')->middleware('can:manage servers');
    Route::get('/servers/{server}/edit', [ServerController::class, 'edit'])->name('servers.edit')->middleware('can:manage servers');
    Route::put('/servers/{server}', [ServerController::class, 'update'])->name('servers.update')->middleware('can:manage servers');
    Route::delete('/servers/{server}', [ServerController::class, 'destroy'])->name('servers.destroy')->middleware('can:manage servers');
    Route::get('/servers/{server}/test-connection', [ServerController::class, 'testConnection'])->name('servers.test-connection')->middleware('can:manage servers');

    // Settings
    Route::get('/settings/payments', [SettingsController::class, 'payments'])->name('settings.payments')->middleware('role:super-admin');
    Route::post('/settings/payments', [SettingsController::class, 'updatePayments'])->name('settings.payments.update')->middleware('role:super-admin');
});