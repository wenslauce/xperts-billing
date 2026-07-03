<x-customer-layout>
    <x-slot:header>Payment Cancelled</x-slot:header>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
        <div class="text-6xl mb-4">❌</div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Payment Cancelled</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-6">Your payment was cancelled. No charges were made.</p>
        <a href="{{ route('customer.invoices.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            Try Again
        </a>
    </div>
</x-customer-layout>