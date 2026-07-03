<x-customer-layout>
    <x-slot:header>
        My Dashboard
    </x-slot:header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Services</div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">0</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Next Invoice Due</div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">—</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Open Tickets</div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">0</div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Welcome to Your Account</h2>
        <p class="text-gray-600 dark:text-gray-400">
            From here you can manage your services, view invoices, and submit support tickets.
        </p>
    </div>
</x-customer-layout>