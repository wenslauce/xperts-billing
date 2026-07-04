<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Service;
use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Monthly Recurring Revenue (MRR) - sum of active service prices
        $mrr = Service::where('status', 'active')
            ->join('order_items', 'services.order_item_id', '=', 'order_items.id')
            ->join('pricing', 'order_items.pricing_id', '=', 'pricing.id')
            ->select(DB::raw('SUM(pricing.price) as total'))
            ->value('total') ?? 0;

        // Total revenue from succeeded transactions
        $totalRevenue = Transaction::where('status', 'succeeded')->sum('amount');

        // Active services count
        $activeServices = Service::where('status', 'active')->count();

        // Overdue invoices (unpaid and past due date)
        $overdueInvoices = Invoice::whereIn('status', ['unpaid', 'overdue'])
            ->where('due_date', '<', now())
            ->count();

        // Open tickets
        $openTickets = Ticket::whereIn('status', ['open', 'replied'])->count();

        // Total customers
        $totalCustomers = Customer::count();

        // Services expiring within 30 days
        $expiringServices = Service::where('status', 'active')
            ->whereNotNull('next_due_date')
            ->whereBetween('next_due_date', [now(), now()->addDays(30)])
            ->count();

        // Recent orders (last 5)
        $recentOrders = Order::with(['customer.user', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        // Monthly revenue for current year
        $monthlyRevenue = Transaction::where('status', 'succeeded')
            ->whereYear('created_at', now()->year)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(amount) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return view('admin.dashboard', compact(
            'mrr',
            'totalRevenue',
            'activeServices',
            'overdueInvoices',
            'openTickets',
            'totalCustomers',
            'expiringServices',
            'recentOrders',
            'monthlyRevenue'
        ));
    }
}