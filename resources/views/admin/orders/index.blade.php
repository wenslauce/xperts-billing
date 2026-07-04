<x-admin-layout>
    <x-slot:header>Orders</x-slot:header>
    <div class="mb-4 flex items-center justify-between">
        <a href="{{ route('admin.orders.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">+ Add Order</a>
    </div>
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label><input type="text" name="search" value="{{ request('search') }}" placeholder="Order #, name, email..." class="mt-1 block w-60 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" class="mt-1 block w-40 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                    <option value="">All</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="awaiting_payment" {{ request('status') == 'awaiting_payment' ? 'selected' : '' }}>Awaiting Payment</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="provisioning" {{ request('status') == 'provisioning' ? 'selected' : '' }}>Provisioning</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="flex items-end"><button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md text-sm hover:bg-gray-700">Filter</button><a href="{{ route('admin.orders.index') }}" class="ml-2 px-4 py-2 bg-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-400">Clear</a></div>
        </form>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th><th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($orders as $order)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">#{{ $order->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $order->customer->user->name ?? 'N/A' }}<br><span class="text-xs">{{ $order->customer->user->email }}</span></td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ number_format($order->total, 2) }} {{ $order->currency }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded
                                {{ $order->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->status === 'awaiting_payment' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $order->status === 'active' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $order->status === 'provisioning' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $order->status === 'pending' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $order->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                            ">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-right text-sm"><a-sm space-x-2">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-900">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $orders->links() }}</div>
</x-admin-layout>