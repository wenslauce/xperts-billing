<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\ServerController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\TicketDepartmentController;
use App\Http\Controllers\Admin\DomainController;
use App\Http\Controllers\Admin\ReportController;

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

    // Tickets
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index')->middleware('can:manage tickets');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show')->middleware('can:manage tickets');
    Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply')->middleware('can:manage tickets');
    Route::post('/tickets/{ticket}/close', [TicketController::class, 'close'])->name('tickets.close')->middleware('can:manage tickets');
    Route::post('/tickets/{ticket}/priority', [TicketController::class, 'priority'])->name('tickets.priority')->middleware('can:manage tickets');

    // Ticket Departments
    Route::get('/ticket-departments', [TicketDepartmentController::class, 'index'])->name('ticket-departments.index')->middleware('can:manage tickets');
    Route::post('/ticket-departments', [TicketDepartmentController::class, 'store'])->name('ticket-departments.store')->middleware('can:manage tickets');
    Route::put('/ticket-departments/{ticketDepartment}', [TicketDepartmentController::class, 'update'])->name('ticket-departments.update')->middleware('can:manage tickets');
    Route::delete('/ticket-departments/{ticketDepartment}', [TicketDepartmentController::class, 'destroy'])->name('ticket-departments.destroy')->middleware('can:manage tickets');

    // Domains
    Route::get('/domains', [DomainController::class, 'index'])->name('domains.index')->middleware('can:manage domains');
    Route::get('/domains/create', [DomainController::class, 'create'])->name('domains.create')->middleware('can:manage domains');
    Route::post('/domains', [DomainController::class, 'store'])->name('domains.store')->middleware('can:manage domains');
    Route::get('/domains/{domain}/edit', [DomainController::class, 'edit'])->name('domains.edit')->middleware('can:manage domains');
    Route::put('/domains/{domain}', [DomainController::class, 'update'])->name('domains.update')->middleware('can:manage domains');
    Route::delete('/domains/{domain}', [DomainController::class, 'destroy'])->name('domains.destroy')->middleware('can:manage domains');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index')->middleware('can:manage reports');
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue')->middleware('can:manage reports');
    Route::get('/reports/outstanding', [ReportController::class, 'outstanding'])->name('reports.outstanding')->middleware('can:manage reports');
    Route::get('/reports/churn', [ReportController::class, 'churn'])->name('reports.churn')->middleware('can:manage reports');
    Route::get('/reports/export/{type}', [ReportController::class, 'exportCsv'])->name('reports.export')->middleware('can:manage reports');

    // Settings
    Route::get('/settings/payments', [SettingsController::class, 'payments'])->name('settings.payments')->middleware('role:super-admin');
    Route::post('/settings/payments', [SettingsController::class, 'updatePayments'])->name('settings.payments.update')->middleware('role:super-admin');
});