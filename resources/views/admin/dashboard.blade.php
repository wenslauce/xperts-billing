<x-admin-layout>
    <x-slot:header>
        Admin Dashboard
    </x-slot:header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Monthly Recurring Revenue</div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($mrr, 2) }} KES</div>
            <div class="mt-1 text-xs text-gray-400">Total Revenue: {{ number_format($totalRevenue, 2) }} KES</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Services</div>
            <div class="mt-2 text-3xl font-bold text-green-600 dark:text-green-400">{{ $activeServices }}</div>
            <div class="mt-1 text-xs text-gray-400">
                @if($expiringServices > 0)
                    <span class="text-orange-600">{{ $expiringServices }} expiring within 30 days</span>
                @else
                    <span class="text-gray-400">No services expiring soon</span>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Overdue Invoices</div>
            <div class="mt-2 text-3xl font-bold {{ $overdueInvoices > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">{{ $overdueInvoices }}</div>
            <div class="mt-1 text-xs text-gray-400">Total Customers: {{ $totalCustomers }}</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Open Tickets</div>
            <div class="mt-2 text-3xl font-bold {{ $openTickets > 0 ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-900 dark:text-white' }}">{{ $openTickets }}</div>
            <div class="mt-1 text-xs text-gray-400">Awaiting response</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Recent Orders --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Orders</h2>
            </div>
            <div class="p-6">
                @if($recentOrders->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentOrders as $order)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="hover:text-blue-600">#{{ $order->id }}</a>
                                        - {{ $order->customer->user->name ?? 'N/A' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $order->items->pluck('product.name')->implode(', ') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ number_format($order->total, 2) }} {{ $order->currency }}</p>
                                    <span class="px-2 py-1 text-xs rounded 
                                        {{ $order->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $order->status === 'active' ? 'bg-blue-100 text-blue-800' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr class="border-gray-200 dark:border-gray-700">
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No orders yet.</p>
                @endif
            </div>
        </div>

        {{-- Monthly Revenue Chart --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Monthly Revenue ({{ now()->year }})</h2>
            <canvas id="revenueChart" height="200"></canvas>
            @if($monthlyRevenue->isEmpty())
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">No revenue data yet.</p>
            @endif
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Welcome to Xperts Africa</h2>
        <p class="text-gray-600 dark:text-gray-400">
            This is your admin dashboard. Use the sidebar to navigate through customers, products, invoices, and more.
        </p>
    </div>

    @if($monthlyRevenue->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const months = @json(array_keys($monthlyRevenue->toArray()));
            const revenues = @json(array_values($monthlyRevenue->toArray()));
            new Chart(document.getElementById('revenueChart'), {
                type: 'line',
                data: {
                    labels: months.map(m => new Date({{ now()->year }}, m-1).toLocaleString('default', { month: 'short' })),
                    datasets: [{
                        label: 'Revenue',
                        data: revenues,
                        borderColor: '#7c3aed',
                        backgroundColor: 'rgba(124, 58, 237, 0.1)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });
    </script>
    @endif
</x-admin-layout>