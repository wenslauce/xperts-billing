<x-admin-layout>
    <x-slot:header>Payment Settings</x-slot:header>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.settings.payments.update') }}" method="POST" class="max-w-3xl">
        @csrf

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-6">
            {{-- Mode Toggle --}}
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Payment Mode</h3>
                <div class="flex space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="payment_mode" value="test" {{ ($settings['payment_mode'] ?? 'test') === 'test' ? 'checked' : '' }} class="text-blue-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Test Mode</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="payment_mode" value="live" {{ ($settings['payment_mode'] ?? '') === 'live' ? 'checked' : '' }} class="text-blue-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Live Mode</span>
                    </label>
                </div>
            </div>

            <hr class="border-gray-200 dark:border-gray-700">

            {{-- Stripe --}}
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Stripe</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Test Publishable Key</label>
                        <input type="text" name="stripe_test_key" value="{{ $settings['stripe_test_key'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Test Secret Key</label>
                        <input type="password" name="stripe_test_secret" value="{{ $settings['stripe_test_secret'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Test Webhook Secret</label>
                        <input type="password" name="stripe_test_webhook_secret" value="{{ $settings['stripe_test_webhook_secret'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div></div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Live Publishable Key</label>
                        <input type="text" name="stripe_live_key" value="{{ $settings['stripe_live_key'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Live Secret Key</label>
                        <input type="password" name="stripe_live_secret" value="{{ $settings['stripe_live_secret'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Live Webhook Secret</label>
                        <input type="password" name="stripe_live_webhook_secret" value="{{ $settings['stripe_live_webhook_secret'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                </div>
            </div>

            <hr class="border-gray-200 dark:border-gray-700">

            {{-- Paystack --}}
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Paystack</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Test Public Key</label>
                        <input type="text" name="paystack_test_public_key" value="{{ $settings['paystack_test_public_key'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Test Secret Key</label>
                        <input type="password" name="paystack_test_secret" value="{{ $settings['paystack_test_secret'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Live Public Key</label>
                        <input type="text" name="paystack_live_public_key" value="{{ $settings['paystack_live_public_key'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Live Secret Key</label>
                        <input type="password" name="paystack_live_secret" value="{{ $settings['paystack_live_secret'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                </div>
            </div>

            <hr class="border-gray-200 dark:border-gray-700">

            {{-- Webhook URLs --}}
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Webhook & Callback URLs</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Configure these URLs in your Stripe and Paystack dashboards.</p>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 w-40">Stripe Webhook:</span>
                        <code class="text-sm bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ url('/webhooks/stripe') }}</code>
                    </div>
                    <div class="flex items-center">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 w-40">Paystack Webhook:</span>
                        <code class="text-sm bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ url('/webhooks/paystack') }}</code>
                    </div>
                    <div class="flex items-center">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 w-40">Success URL:</span>
                        <code class="text-sm bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ url('/checkout/success') }}</code>
                    </div>
                    <div class="flex items-center">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 w-40">Cancel URL:</span>
                        <code class="text-sm bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ url('/checkout/cancel') }}</code>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save Settings</button>
            </div>
        </div>
    </form>
</x-admin-layout>