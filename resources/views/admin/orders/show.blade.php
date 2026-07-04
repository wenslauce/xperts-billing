<x-admin-layout>
    <x-slot:header>Order #{{ $order->id }}</x-slot:header>
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6"><p class="text-sm text-gray-500">Order Total</p><p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($order->total, 2) }} {{ $order->currency }}</p></div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6"><p class="text-sm text-gray-500">Status</p><p class="text-2xl font-bold"><span class="px-2 py-1 text-xs rounded {{ $order->status === 'paid' ? 'bg-green-100 text-green-800' : ($order->status === 'awaiting_payment' ? 'bg-yellow-100 text-yellow-800' : ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span></p></div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6"><p class="text-sm text-gray-500">Date</p><p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $order->created_at->format('d M Y H:i') }}</p></div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700"><h3 class="font-semibold dark:text-white">Customer</h3></div>
            <div class="p-6">
                <p class="font-medium dark:text-white">{{ $order->customer->user->name }}</p>
                <p class="text-sm text-gray-500">{{ $order->customer->user->email }}</p>
                <p class="text-sm text-gray-500">{{ $order->customer->phone ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700"><h3 class="font-semibold dark:text-white">Order Items</h3></div>
            <div class="p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead><tr><th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Product</th><th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Qty</th><th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Unit Price</th><th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Total</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($order->items as $item)
                            <tr><td class="px-4 py-2 text-sm">{{ $item->product->name }}</td><td class="px-4 py-2 text-right text-sm">{{ $item->quantity }}</td><td class="px-4 py-2 text-right text-sm">{{ number_format($item->unit_price, 2) }}</td><td class="px-4 py-2 text-right text-sm font-medium">{{ number_format($item->unit_price * $item->quantity, 2) }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if($order->invoice)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700"><h3 class="font-semibold dark:text-white">Invoice</h3></div>
            <div class="p-6">
                <p><a href="{{ route('admin.invoices.show', $order->invoice) }}" class="text-blue-600 hover:underline">Invoice #{{ $order->invoice->invoice_number }}</a> - {{ ucfirst($order->invoice->status) }} - {{ number_format($order->invoice->total, 2) }} {{ $order->invoice->currency }}</p>
            </div>
        </div>
    @endif
</x-admin-layout>