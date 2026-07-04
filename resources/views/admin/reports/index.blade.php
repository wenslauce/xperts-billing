<x-admin-layout>
    <x-slot:header>Reports Dashboard</x-slot:header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalRevenue, 2) }} KES</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 dark:text-gray-400">Pending Invoices</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $pendingInvoices }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 dark:text-gray-400">Active Services</p>
            <p class="text-2xl font-bold text-green-600">{{ $activeServices }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Customers</p>
            <p class="text-2xl font-bold text-blue-600">{{ $totalCustomers }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="font-semibold mb-4 dark:text-white">Monthly Revenue ({{ now()->year }})</h3>
            <canvas id="revenueChart" height="200"></canvas>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="font-semibold mb-4 dark:text-white">Invoice Status</h3>
            <canvas id="invoiceChart" height="200"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="font-semibold mb-4 dark:text-white">Monthly Orders</h3>
            <canvas id="ordersChart" height="200"></canvas>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="font-semibold mb-4 dark:text-white">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.reports.revenue') }}" class="block p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-blue-700 dark:text-blue-300 hover:bg-blue-100">
                    Revenue Report →
                </a>
                <a href="{{ route('admin.reports.outstanding') }}" class="block p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg text-yellow-700 dark:text-yellow-300 hover:bg-yellow-100">
                    Outstanding Invoices →
                </a>
                <a href="{{ route('admin.reports.churn') }}" class="block p-3 bg-red-50 dark:bg-red-900/20 rounded-lg text-red-700 dark:text-red-300 hover:bg-red-100">
                    Churn Analysis →
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Chart
            const months = @json(array_keys($monthlyRevenue->toArray()));
            const revenues = @json(array_values($monthlyRevenue->toArray()));
            new Chart(document.getElementById('revenueChart'), {
                type: 'line', data: {
                    labels: months.map(m => new Date({{ now()->year }}, m-1).toLocaleString('default', { month: 'short' })),
                    datasets: [{ label: 'Revenue', data: revenues, borderColor: '#7c3aed', tension: 0.3 }]
                }, options: { responsive: true, maintainAspectRatio: false }
            });

            // Invoice Chart
            const statuses = @json(array_keys($invoiceStatuses->toArray()));
            const counts = @json(array_values($invoiceStatuses->toArray()));
            new Chart(document.getElementById('invoiceChart'), {
                type: 'doughnut', data: {
                    labels: statuses, datasets: [{ data: counts, backgroundColor: ['#7c3aed', '#f59e0b', '#10b981', '#ef4444'] }]
                }, options: { responsive: true, maintainAspectRatio: false }
            });

            // Orders Chart
            const orderMonths = @json(array_keys($monthlyOrders->toArray()));
            const orderCounts = @json(array_values($monthlyOrders->toArray()));
            new Chart(document.getElementById('ordersChart'), {
                type: 'bar', data: {
                    labels: orderMonths.map(m => new Date({{ now()->year }}, m-1).toLocaleString('default', { month: 'short' })),
                    datasets: [{ label: 'Orders', data: orderCounts, backgroundColor: '#a855f7' }]
                }, options: { responsive: true, maintainAspectRatio: false }
            });
        });
    </script>
</x-admin-layout>