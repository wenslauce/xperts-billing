<x-admin-layout>
    <x-slot:header>Edit Server</x-slot:header>

    <form action="{{ route('admin.servers.update', $server) }}" method="POST" class="max-w-2xl">
        @csrf @method('PUT')

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Server Name</label>
                <input type="text" name="name" value="{{ old('name', $server->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hostname / IP</label>
                <input type="text" name="hostname" value="{{ old('hostname', $server->hostname) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reseller Username</label>
                <input type="text" name="api_username" value="{{ old('api_username', $server->api_username) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Login Key</label>
                <input type="password" name="api_key" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <p class="mt-1 text-xs text-gray-500">Leave blank to keep the current key</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Server Group</label>
                <input type="text" name="server_group" value="{{ old('server_group', $server->server_group) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Accounts</label>
                <input type="number" name="max_accounts" value="{{ old('max_accounts', $server->max_accounts) }}" min="1" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <p class="mt-1 text-xs text-gray-500">Current: {{ $server->current_accounts }} accounts</p>
            </div>

            <div>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ $server->is_active ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                </label>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Server</button>
            </div>
        </div>
    </form>
</x-admin-layout>