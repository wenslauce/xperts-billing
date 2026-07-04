<x-admin-layout>
    <x-slot:header>{{ $customer->user->name }}</x-slot:header>
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6"><p class="text-sm text-gray-500">Total Paid</p><p class="text-2xl font-bold text-green-600">{{ number_format($totalPaid, 2) }} KES</p></div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6"><p class="text-sm text-gray-500">Unpaid Balance</p><p class="text-2xl font-bold {{ $unpaidBalance > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ number_format($unpaidBalance, 2) }} KES</p></div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6"><p class="text-sm text-gray-500">Active Services</p><p class="text-2xl font-bold text-blue-600">{{ $services->where('status', 'active')->count() }}</p></div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6"><p class="text-sm text-gray-500">Open Tickets</p><p class="text-2xl font-bold text-yellow-600">{{ $tickets->whereIn('status', ['open', 'replied'])->count() }}</p></div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="font-semibold mb-4 dark:text-white">Customer Details</h3>
            <dl class="space-y-2 text-sm">
                <dt class="text-gray-500">Email</dt><dd class="font-medium dark:text-white">{{ $customer->user->email }}</dd>
                <dt class="text-gray-500">Phone</dt><dd class="font-medium dark:text-white">{{ $customer->phone ?? '-' }}</dd>
                <dt class="text-gray-500">Company</dt><dd class="font-medium dark:text-white">{{ $customer->company_name ?? '-' }}</dd>
                <dt class="text-gray-500">Country</dt><dd class="font-medium dark:text-white">{{ $customer->country ?? '-' }}</dd>
                <dt class="text-gray-500">Tax ID</dt><dd class="font-medium dark:text-white">{{ $customer->tax_id ?? '-' }}</dd>
                <dt class="text-gray-500">Status</dt><dd><span class="px-2 py-1 text-xs rounded {{ $customer->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ ucfirst($customer->status) }}</span></dd>
            </dl>
            <div class="mt-4 space-x-2">
                <a href="{{ route('admin.customers.edit', $customer) }}" class="px-3 py-1.5 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Edit</a>
                <a href="{{ route('admin.customers.impersonate', $customer) }}" class="px-3 py-1.5 bg-yellow-600 text-white rounded text-sm hover:bg-yellow-700" onclick="return confirm('Login as this customer?')">Impersonate</a>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="font-semibold mb-4 dark:text-white">Billing Address</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ $customer->billing_address_line1 ?? 'N/A' }}<br>
                {{ $customer->billing_address_line2 ?? '' }}<br>
                {{ $customer->city ?? '' }} {{ $customer->state ?? '' }} {{ $customer->postal_code ?? '' }}<br>
                {{ $customer->country ?? '' }}
            </p>
        </div>
    </div>
    {{-- Services --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700"><h3 class="font-semibold dark:text-white">Services ({{ $services->count() }})</h3></div>
        <div class="p-6">
            @if($services->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead><tr><th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Product</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Domain</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Status</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Next Due</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($services as $service)
                            <tr><td class="px-4 py-2 text-sm">{{ $service->product->name ?? 'N/A' }}</td><td class="px-4 py-2 text-sm">{{ $service->domain ?? '-' }}</td><td class="px-4 py-2"><span class="px-2 py-1 text-xs rounded {{ $service->status == 'active' ? 'bg-green-100 text-green-800' : ($service->status == 'suspended' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">{{ ucfirst($service->status) }}</span></td><td class="px-4 py-2 text-sm">{{ $service->next_due_date?->format('d M Y') ?? '-' }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            @else<p class="text-sm text-gray-500">No services.</p>@endif
        </div>
    </div>
    {{-- Invoices --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700"><h3 class="font-semibold dark:text-white">Recent Invoices</h3></div>
        <div class="p-6">
            @if($invoices->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead><tr><th class="px-4 py-2 text-left text-xs font-medium text-gray-500">#</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Total</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Status</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Due</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($invoices as $inv)
                            <tr><td class="px-4 py-2 text-sm">{{ $inv->invoice_number }}</td><td class="px-4 py-2 text-sm">{{ number_format($inv->total, 2) }}</td><td class="px-4 py-2"><span class="px-2 py-1 text-xs rounded {{ $inv->status == 'paid' ? 'bg-green-100 text-green-800' : ($inv->status == 'overdue' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">{{ ucfirst($inv->status) }}</span></td><td class="px-4 py-2 text-sm">{{ $inv->due_date->format('d M Y') }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            @else<p class="text-sm text-gray-500">No invoices.</p>@endif
        </div>
    </div>
    {{-- Orders --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700"><h3 class="font-semibold dark:text-white">Recent Orders</h3></div>
        <div class="p-6">
            @if($orders->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead><tr><th class="px-4 py-2 text-left text-xs font-medium text-gray-500">#</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Total</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Status</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Date</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr><td class="px-4 py-2 text-sm">#{{ $order->id }}</td><td class="px-4 py-2 text-sm">{{ number_format($order->total, 2) }}</td><td class="px-4 py-2"><span class="px-2 py-1 text-xs rounded {{ $order->status == 'active' ? 'bg-green-100 text-green-800' : ($order->status == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span></td><td class="px-4 py-2 text-sm">{{ $order->created_at->format('d M Y') }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            @else<p class="text-sm text-gray-500">No orders.</p>@endif
        </div>
    </div>
    {{-- Domains --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700"><h3 class="font-semibold dark:text-white">Domains</h3></div>
        <div class="p-6">
            @if($domains->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead><tr><th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Domain</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Expiry</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Status</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($domains as $domain)
                            <tr><td class="px-4 py-2 text-sm">{{ $domain->name }}</td><td class="px-4 py-2 text-sm">{{ $domain->expiry_date?->format('d M Y') ?? '-' }}</td><td class="px-4 py-2"><span class="px-2 py-1 text-xs rounded {{ $domain->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ ucfirst($domain->status) }}</span></td></tr>
                        @endforeach
                    </tbody>
                </table>
            @else<p class="text-sm text-gray-500">No domains.</p>@endif
        </div>
    </div>
    {{-- Tickets --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700"><h3 class="font-semibold dark:text-white">Recent Tickets</h3></div>
        <div class="p-6">
            @if($tickets->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead><tr><th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Subject</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Status</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Priority</th><th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Date</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($tickets as $ticket)
                            <tr><td class="px-4 py-2 text-sm">{{ $ticket->subject }}</td><td class="px-4 py-2"><span class="px-2 py-1 text-xs rounded {{ $ticket->status == 'open' ? 'bg-blue-100 text-blue-800' : ($ticket->status == 'closed' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800') }}">{{ ucfirst($ticket->status) }}</span></td><td class="px-4 py-2 text-sm">{{ ucfirst($ticket->priority) }}</td><td class="px-4 py-2 text-sm">{{ $ticket->created_at->format('d M Y') }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            @else<p class="text-sm text-gray-500">No tickets.</p>@endif
        </div>
    </div>
</x-admin-layout>