<x-admin-layout>
    <x-slot:header>Churn Analysis</x-slot:header>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 dark:text-gray-400">Active Services</p>
            <p class="text-2xl font-bold text-green-600">{{ $active }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Services</p>
            <p class="text-2xl font-bold text-blue-600">{{ $total }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold dark:text-white">Churned Services by Month ({{ now()->year }})</h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Month</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Churned Count</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($churned as $month => $count)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::create()->month($month)->format('F') }}</td>
                        <td class="px-6 py-4 text-sm text-right font-medium text-red-600">{{ $count }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="px-6 py-4 text-center text-gray-500">No churned services this year.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>