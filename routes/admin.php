<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\InvoiceController;

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
});