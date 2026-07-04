<x-admin-layout>
    <x-slot:header>Edit Domain</x-slot:header>
    <form action="{{ route('admin.domains.update', $domain) }}" method="POST" class="max-w-lg">
        @csrf @method('PUT')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Customer</label>
                <select name="customer_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}" {{ $domain->customer_id === $c->id ? 'selected' : '' }}>{{ $c->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Domain Name</label>
                <input type="text" name="name" value="{{ old('name', $domain->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Registrar</label>
                <input type="text" name="registrar" value="{{ old('registrar', $domain->registrar) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Registration Date</label>
                    <input type="date" name="registration_date" value="{{ old('registration_date', $domain->registration_date?->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expiry Date</label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date', $domain->expiry_date?->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="active" {{ $domain->status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ $domain->status === 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="transferred" {{ $domain->status === 'transferred' ? 'selected' : '' }}>Transferred</option>
                    <option value="cancelled" {{ $domain->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="auto_renew" value="1" {{ $domain->auto_renew ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Auto Renew</span>
                </label>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Domain</button>
            </div>
        </div>
    </form>
</x-admin-layout>