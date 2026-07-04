<x-admin-layout>
    <x-slot:header>Outstanding Invoices</x-slot:header>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <span class="font-semibold dark:text-white">{{ $invoices->count() }} outstanding invoices</span>
            <a href="{{ route('admin.reports.export', 'outstanding') }}" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm">Export CSV</a>
        </div>
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($invoices as $inv)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $inv->invoice_number }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $inv->customer->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-right font-medium text-gray-900 dark:text-white">{{ number_format($inv->total, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $inv->due_date->format('d M Y') }}</td>
                        <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded {{ $inv->status === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst($inv->status) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No outstanding invoices.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>