<x-admin-layout>
    <x-slot:header>Invoice {{ $invoice->invoice_number }}</x-slot:header>
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6"><p class="text-sm text-gray-500">Total</p><p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($invoice->total, 2) }} {{ $invoice->currency }}</p></div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6"><p class="text-sm text-gray-500">Status</p><p class="text-2xl font-bold"><span class="px-2 py-1 text-xs rounded {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : ($invoice->status === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">{{ ucfirst($invoice->status) }}</span></p></div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6"><p class="text-sm text-gray-500">Due Date</p><p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $invoice->due_date->format('d M Y') }}</p></div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700"><h3 class="font-semibold dark:text-white">Customer</h3></div>
            <div class="p-6">
                <p class="font-medium dark:text-white">{{ $invoice->customer->user->name }}</p>
                <p class="text-sm text-gray-500">{{ $invoice->customer->user->email }}</p>
                <p class="text-sm text-gray-500">{{ $invoice->customer->phone ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700"><h3 class="font-semibold dark:text-white">Invoice Details</h3></div>
            <div class="p-6">
                <p class="text-sm text-gray-500">Invoice #: {{ $invoice->invoice_number }}</p>
                <p class="text-sm text-gray-500">Date: {{ $invoice->created_at->format('d M Y') }}</p>
                <p class="text-sm text-gray-500">Due: {{ $invoice->due_date->format('d M Y') }}</p>
                @if($invoice->paid_at)
                    <p class="text-sm text-green-600">Paid: {{ $invoice->paid_at->format('d M Y H:i') }}</p>
                @endif
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-70000"><h3 class="font-semibold dark:text-white">Line Items</h3></div>
        <div class="p-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead><tr><th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Description</th><th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Qty</th><th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Unit Price</th><th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Total</th></tr></thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($invoice->items as $item)
                        <tr><td class="px-4 py-2 text-sm">{{ $item->description }}</td><td class="px-4 py-2 text-right text-sm">{{ $item->quantity }}</td><td class="px-4 py-2 text-right text-sm">{{ number_format($item->unit_price, 2) }}</td><td class="px-4 py-2 text-right text-sm font-medium">{{ number_format($item->total, 2) }}</td></tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4 text-right text-lg font-bold">Total: {{ number_format($invoice->total, 2) }} {{ $invoice->currency }}</div>
        </div>
    </div>
    @if($invoice->status !== 'paid')
        <div class="mt-4 flex justify-end">
            <form action="{{ route('admin.invoices.mark-paid', $invoice) }}" method="POST" class="inline">
                @csrf
                <div class="flex items-center gap-2">
                    <select name="gateway" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                        <option value="manual">Manual</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                    <input type="text" name="reference" placeholder="Reference" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm px-3 py-1.5">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Mark as Paid</button>
                </div>
            </form>
        </div>
    @endif
</x-admin-layout>