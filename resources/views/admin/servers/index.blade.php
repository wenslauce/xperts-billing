<x-admin-layout>
    <x-slot:header>Servers</x-slot:header>

    <div class="mb-4">
        <a href="{{ route('admin.servers.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
            + Add Server
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="mb-4 p-4 bg-yellow-100 text-yellow-800 rounded">{{ session('warning') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Hostname</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Usage</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($servers as $server)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $server->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $server->hostname }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $server->max_accounts > 0 ? min(100, ($server->current_accounts / $server->max_accounts) * 100) : 0 }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $server->current_accounts }}/{{ $server->max_accounts }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded {{ $server->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $server->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm space-x-2">
                            <a href="{{ route('admin.servers.test-connection', $server) }}" class="text-green-600 hover:text-green-900 dark:text-green-400" title="Test Connection">
                                Test
                            </a>
                            <a href="{{ route('admin.servers.edit', $server) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400">Edit</a>
                            <form action="{{ route('admin.servers.destroy', $server) }}" method="POST" class="inline" onsubmit="return confirm('Delete this server?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No servers configured. Add your first DirectAdmin server.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $servers->links() }}</div>
</x-admin-layout>