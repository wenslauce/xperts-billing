<x-admin-layout>
    <x-slot:header>Customers</x-slot:header>
    <div class="mb-4 flex items-center justify-between">
        <a href="{{ route('admin.customers.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">+ Add Customer</a>
    </div>
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label><input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, company, phone..." class="mt-1 block w-60 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" class="mt-1 block w-32 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                    <option value="">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="locked" {{ request('status') == 'locked' ? 'selected' : '' }}>Locked</option>
                </select>
            </div>
            <div class="flex items-end"><button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md text-sm hover:bg-gray-700">Search</button><a href="{{ route('admin.customers.index') }}" class="ml-2 px-4 py-2 bg-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-400">Clear</a></div>
        </form>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th><th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($customers as $customer)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $customer->user->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $customer->user->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $customer->company_name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $customer->phone ?? '-' }}</td>
                        <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded {{ $customer->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ ucfirst($customer->status) }}</span></td>
                        <td class="px-6 py-4 text-right text-sm space-x-2">
                            <a href="{{ route('admin.customers.show', $customer) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            <a href="{{ route('admin.customers.edit', $customer) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No customers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $customers->links() }}</div>
</x-admin-layout>