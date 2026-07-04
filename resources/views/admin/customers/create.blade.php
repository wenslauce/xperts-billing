<x-admin-layout>
    <x-slot:header>Add Customer</x-slot:header>
    <form action="{{ route('admin.customers.store') }}" method="POST" class="max-w-2xl">
        @csrf
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label><input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label><input type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label><input type="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label><input type="text" name="phone" value="{{ old('phone') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Company</label><input type="text" name="company_name" value="{{ old('company_name') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Country</label><input type="text" name="country" value="{{ old('country', 'KE') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="KE"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tax ID / KRA PIN</label><input type="text" name="tax_id" value="{{ old('tax_id') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address Line 1</label><input type="text" name="billing_address_line1" value="{{ old('billing_address_line1') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address Line 2</label><input type="text" name="billing_address_line2" value="{{ old('billing_address_line2') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
            <div class="grid grid-cols-3 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">City</label><input type="text" name="city" value="{{ old('city') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">State</label><input type="text" name="state" value="{{ old('state') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Postal Code</label><input type="text" name="postal_code" value="{{ old('postal_code') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
            </div>
            <div class="flex justify-end"><button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create Customer</button></div>
        </div>
    </form>
</x-admin-layout>