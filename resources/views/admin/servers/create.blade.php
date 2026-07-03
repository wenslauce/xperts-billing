<x-admin-layout>
    <x-slot:header>Add Server</x-slot:header>

    <form action="{{ route('admin.servers.store') }}" method="POST" class="max-w-2xl">
        @csrf

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Server Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <p class="mt-1 text-xs text-gray-500">e.g. DA Reseller #1, Primary Server</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hostname / IP</label>
                <input type="text" name="hostname" value="{{ old('hostname') }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <p class="mt-1 text-xs text-gray-500">e.g. 198.251.89.82 (without port)</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reseller Username</label>
                <input type="text" name="api_username" value="{{ old('api_username') }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Login Key</label>
                <input type="password" name="api_key" value="{{ old('api_key') }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <p class="mt-1 text-xs text-gray-500">DirectAdmin Login Key or password</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Server Group</label>
                <input type="text" name="server_group" value="{{ old('server_group') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <p class="mt-1 text-xs text-gray-500">Optional grouping tag (e.g. standard, premium)</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Accounts</label>
                <input type="number" name="max_accounts" value="{{ old('max_accounts', 100) }}" min="1" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                </label>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add Server</button>
            </div>
        </div>
    </form>
</x-admin-layout>