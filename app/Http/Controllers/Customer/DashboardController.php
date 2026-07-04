<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        $customer = auth()->user()->customer;

        // Active services for this customer
        $activeServices = Service::where('customer_id', $customer->id)
            ->where('status', 'active')
            ->with('product')
            ->latest()
            ->get();

        // Next invoice due (earliest unpaid)
        $nextInvoice = Invoice::where('customer_id', $customer->id)
            ->whereIn('status', ['unpaid', 'overdue'])
            ->orderBy('due_date', 'asc')
            ->first();

        // Open tickets
        $openTickets = Ticket::where('customer_id', $customer->id)
            ->whereIn('status', ['open', 'replied'])
            ->count();

        // Recent invoices (last 5)
        $recentInvoices = Invoice::where('customer_id', $customer->id)
            ->with('items')
            ->latest()
            ->take(5)
            ->get();

        // Domains expiring within 30 days
        $expiringDomains = Domain::where('customer_id', $customer->id)
            ->where('status', 'active')
            ->whereNotNull('expiry_date')
            ->whereBetween('expiry_date', [now(), now()->addDays(30)])
            ->get();

        // Total unpaid balance
        $unpaidBalance = Invoice::where('customer_id', $customer->id)
            ->whereIn('status', ['unpaid', 'overdue'])
            ->sum('total');

        return view('customer.dashboard', compact(
            'activeServices',
            'nextInvoice',
            'openTickets',
            'recentInvoices',
            'expiringDomains',
            'unpaidBalance'
        ));
    }
}