<x-admin-layout>
    <x-slot:header>Domains</x-slot:header>

    <div class="mb-4">
        <a href="{{ route('admin.domains.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">+ Add Domain</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Domain</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registrar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expiry</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($domains as $domain)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $domain->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $domain->customer->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $domain->registrar ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm {{ $domain->expiry_date && $domain->expiry_date->isPast() ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                            {{ $domain->expiry_date ? $domain->expiry_date->format('d M Y') : '-' }}
                            @if($domain->expiry_date && $domain->expiry_date->isPast())
                                <span class="text-xs text-red-600">(Expired)</span>
                            @elseif($domain->expiry_date && $domain->expiry_date->diffInDays(now()) < 30)
                                <span class="text-xs text-orange-600">({{ $domain->expiry_date->diffInDays(now()) }} days)</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded {{ $domain->status === 'active' ? 'bg-green-100 text-green-800' : ($domain->status === 'expired' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($domain->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm space-x-2">
                            <a href="{{ route('admin.domains.edit', $domain) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                            <form action="{{ route('admin.domains.destroy', $domain) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No domains found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $domains->links() }}</div>
</x-admin-layout>