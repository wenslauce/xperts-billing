<x-admin-layout>
    <x-slot:header>Create Product</x-slot:header>

    <form action="{{ route('admin.products.store') }}" method="POST" class="max-w-3xl">
        @csrf

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                <select name="type" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="shared_hosting">Shared Hosting</option>
                    <option value="reseller">Reseller</option>
                    <option value="vps">VPS</option>
                    <option value="domain">Domain</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">DirectAdmin Package</label>
                <input type="text" name="directadmin_package" value="{{ old('directadmin_package') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                </label>
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Pricing Tiers</h3>
                <div id="pricing-rows" class="space-y-3">
                    <div class="pricing-row grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Billing Cycle</label>
                            <select name="pricing[0][billing_cycle]" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="semiannual">Semi-Annual</option>
                                <option value="annual">Annual</option>
                                <option value="biennial">Biennial</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Price</label>
                            <input type="number" step="0.01" min="0" name="pricing[0][price]" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Setup Fee</label>
                            <input type="number" step="0.01" min="0" name="pricing[0][setup_fee]" value="0" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                    </div>
                </div>
                <button type="button" onclick="addPricingRow()" class="mt-2 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">+ Add another pricing tier</button>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create Product</button>
            </div>
        </div>
    </form>

    <script>
        let pricingIndex = 1;
        function addPricingRow() {
            const html = `<div class="pricing-row grid grid-cols-3 gap-4">
                <select name="pricing[${pricingIndex}][billing_cycle]" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="monthly">Monthly</option>
                    <option value="quarterly">Quarterly</option>
                    <option value="semiannual">Semi-Annual</option>
                    <option value="annual">Annual</option>
                    <option value="biennial">Biennial</option>
                </select>
                <input type="number" step="0.01" min="0" name="pricing[${pricingIndex}][price]" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                <input type="number" step="0.01" min="0" name="pricing[${pricingIndex}][setup_fee]" value="0" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>`;
            document.getElementById('pricing-rows').insertAdjacentHTML('beforeend', html);
            pricingIndex++;
        }
    </script>
</x-admin-layout>