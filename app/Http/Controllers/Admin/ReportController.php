<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $totalRevenue = Transaction::where('status', 'succeeded')->sum('amount');
        $pendingInvoices = Invoice::where('status', 'unpaid')->count();
        $activeServices = Service::where('status', 'active')->count();
        $totalCustomers = Customer::count();

        // Monthly revenue for chart
        $monthlyRevenue = Transaction::where('status', 'succeeded')
            ->whereYear('created_at', now()->year)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(amount) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Invoice status distribution
        $invoiceStatuses = Invoice::select(DB::raw('status'), DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        // Monthly orders
        $monthlyOrders = Order::whereYear('created_at', now()->year)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        return view('admin.reports.index', compact(
            'totalRevenue', 'pendingInvoices', 'activeServices', 'totalCustomers',
            'monthlyRevenue', 'invoiceStatuses', 'monthlyOrders'
        ));
    }

    public function revenue(Request $request)
    {
        $startDate = $request->date('start_date', now()->startOfYear());
        $endDate = $request->date('end_date', now()->endOfYear());

        $revenues = Transaction::where('status', 'succeeded')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $total = $revenues->sum('total');

        return view('admin.reports.revenue', compact('revenues', 'total', 'startDate', 'endDate'));
    }

    public function outstanding()
    {
        $invoices = Invoice::whereIn('status', ['unpaid', 'overdue'])
            ->with('customer.user')
            ->latest()
            ->get();

        return view('admin.reports.outstanding', compact('invoices'));
    }

    public function churn()
    {
        $churned = Service::where('status', 'cancelled')
            ->select(DB::raw('MONTH(updated_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('updated_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        $active = Service::where('status', 'active')->count();
        $total = Service::count();

        return view('admin.reports.churn', compact('churned', 'active', 'total'));
    }

    public function exportCsv(Request $request, string $type)
    {
        $filename = "report-{$type}-" . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($type) {
            $handle = fopen('php://output', 'w');

            if ($type === 'revenue') {
                fputcsv($handle, ['Date', 'Amount', 'Currency', 'Gateway', 'Reference']);
                Transaction::where('status', 'succeeded')->chunk(100, function ($transactions) use ($handle) {
                    foreach ($transactions as $t) {
                        fputcsv($handle, [
                            $t->created_at->format('Y-m-d'),
                            $t->amount,
                            $t->currency,
                            $t->gateway,
                            $t->gateway_reference,
                        ]);
                    }
                });
            } elseif ($type === 'outstanding') {
                fputcsv($handle, ['Invoice #', 'Customer', 'Total', 'Due Date', 'Status']);
                Invoice::whereIn('status', ['unpaid', 'overdue'])->with('customer.user')->chunk(100, function ($invoices) use ($handle) {
                    foreach ($invoices as $inv) {
                        fputcsv($handle, [
                            $inv->invoice_number,
                            $inv->customer->user->name ?? 'N/A',
                            $inv->total,
                            $inv->due_date->format('Y-m-d'),
                            $inv->status,
                        ]);
                    }
                });
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}