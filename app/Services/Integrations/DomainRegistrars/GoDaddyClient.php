<x-admin-layout>
    <x-slot:header>Edit TLD Price</x-slot:header>
    <form action="{{ route('admin.tld-prices.update', $tldPrice) }}" method="POST" class="max-w-lg">
        @csrf @method('PUT')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-4">
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">TLD</label><input type="text" name="tld" value="{{ old('tld', $tldPrice->tld) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="com, org, net"><p class="mt-1 text-xs text-gray-500">Enter without the leading dot</p></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Registrar</label>
                <select name="registrar" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="resellerclub" {{ $tldPrice->registrar == 'resellerclub' ? 'selected' : '' }}>ResellerClub</option>
                    <option value="enom" {{ $tldPrice->registrar == 'enom' ? 'selected' : '' }}>Enom</option>
                    <option value="namecheap" {{ $tldPrice->registrar == 'namecheap' ? 'selected' : '' }}>Namecheap</option>
                    <option value="godaddy" {{ $tldPrice->registrar == 'godaddy' ? 'selected' : '' }}>GoDaddy</option>
                    <option value="namesilo" {{ $tldPrice->registrar == 'namesilo' ? 'selected' : '' }}>NameSilo</option>
                    <option value="manual" {{ $tldPrice->registrar == 'manual' ? 'selected' : '' }}>Manual</option>
                </select>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Register Price</label><input type="number" step="0.01" min="0" name="register_price" value="{{ old('register_price', $tldPrice->register_price) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Renew Price</label><input type="number" step="0.01" min="0" name="renew_price" value="{{ old('renew_price', $tldPrice->renew_price) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Transfer Price</label><input type="number" step="0.01" min="0" name="transfer_price" value="{{ old('transfer_price', $tldPrice->transfer_price) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></div>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Currency</label>
                <select name="currency" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="KES" {{ $tldPrice->currency == 'KES' ? 'selected' : '' }}>KES - Kenyan Shilling</option>
                    <option value="USD" {{ $tldPrice->currency == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                    <option value="EUR" {{ $tldPrice->currency == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                </select>
            </div>
            <div class="flex justify-end"><button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update TLD Price</button></div>
        </div>
    </form>
</x-admin-layout>