<x-admin-layout>
    <x-slot:header>Edit Customer: {{ $customer->user->name }}</x-slot:header>
    <form action="{{ route('admin.customers.update', $customer) }}" method="POST" class="max-w-2xl">
        @csrf @method('PUT')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label><input type="text" name="name" value="{{ old('name', $customer->user->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label><input type="email" name="email" value="{{ old('email', $customer->user->email) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password (leave blank to keep)</label><input type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label><input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Company</label><input type="text" name="company_name" value="{{ old('company_name', $customer->company_name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Country</label><input type="text" name="country" value="{{ old('country', $customer->country ?? 'KE') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tax ID</label><input type="text" name="tax_id" value="{{ old('tax_id', $customer->tax_id) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select name="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="active" {{ $customer->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="locked" {{ $customer->status == 'locked' ? 'selected' : '' }}>Locked</option>
                    </select>
                </div>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address Line 1</label><input type="text" name="billing_address_line1" value="{{ old('billing_address_line1', $customer->billing_address_line1) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address Line 2</label><input type="text" name="billing_address_line2" value="{{ old('billing_address_line2', $customer->billing_address_line2) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
            <div class="grid grid-cols-3 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">City</label><input type="text" name="city" value="{{ old('city', $customer->city) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">State</label><input type="text" name="state" value="{{ old('state', $customer->state) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Postal Code</label><input type="text" name="postal_code" value="{{ old('postal_code', $customer->postal_code) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
            </div>
            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.customers.show', $customer) }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Customer</button>
            </div>
        </div>
    </form>
</x-admin-layout>