<x-admin-layout>
    <x-slot:header>
        Admin Dashboard
    </x-slot:header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- KPI Cards --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Monthly Recurring Revenue</div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">KES 0</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Services</div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">0</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Overdue Invoices</div>
            <div class="mt-2 text-3xl font-bold text-red-600 dark:text-red-400">0</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Open Tickets</div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">0</div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Welcome to Xperts Billing</h2>
        <p class="text-gray-600 dark:text-gray-400">
            This is your admin dashboard. Use the sidebar to navigate through customers, products, invoices, and more.
        </p>
    </div>
</x-admin-layout>