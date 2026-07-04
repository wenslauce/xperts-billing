<x-customer-layout>
    <x-slot:header>
        My Dashboard
    </x-slot:header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Services</div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $activeServices->count() }}</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Next Invoice Due</div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                @if($nextInvoice)
                    {{ number_format($nextInvoice->total, 2) }} {{ $nextInvoice->currency }}
                @else
                    —
                @endif
            </div>
            @if($nextInvoice)
                <div class="mt-1 text-xs text-gray-400">
                    Due {{ $nextInvoice->due_date->format('d M Y') }}
                </div>
            @endif
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Open Tickets</div>
            <div class="mt-2 text-3xl font-bold {{ $openTickets > 0 ? 'text-yellow-600' : 'text-gray-900 dark:text-white' }}">{{ $openTickets }}</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Unpaid Balance</div>
            <div class="mt-2 text-3xl font-bold {{ $unpaidBalance > 0 ? 'text-red-600' : 'text-green-600' }}">{{ number_format($unpaidBalance, 2) }} KES</div>
        </div>
    </div>

    {{-- Active Services --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">My Services</h2>
        </div>
        <div class="p-6">
            @if($activeServices->count() > 0)
                <div class="space-y-4">
                    @foreach($activeServices as $service)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $service->product->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $service->domain ?? 'No domain' }}
                                    @if($service->next_due_date)
                                        · Next due: {{ $service->next_due_date->format('d M Y') }}
                                    @endif
                                </p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">
                                {{ ucfirst($service->status) }}
                            </span>
                        </div>
                        @if(!$loop->last)
                            <hr class="border-gray-200 dark:border-gray-700">
                        @endif
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No active services yet. <a href="{{ route('hosting') }}" class="text-blue-600 hover:underline">Browse plans</a></p>
            @endif
        </div>
    </div>

    {{-- Recent Invoices --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Invoices</h2>
        </div>
        <div class="p-6">
            @if($recentInvoices->count() > 0)
                <div class="space-y-4">
                    @foreach($recentInvoices as $invoice)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $invoice->invoice_number }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Due {{ $invoice->due_date->format('d M Y') }}
                                    @if($invoice->items->count() > 0)
                                        · {{ $invoice->items->first()->description }}
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ number_format($invoice->total, 2) }} {{ $invoice->currency }}</p>
                                <span class="px-2 py-1 text-xs rounded 
                                    {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $invoice->status === 'unpaid' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $invoice->status === 'overdue' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $invoice->status === 'cancelled' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                                @if(in_array($invoice->status, ['unpaid', 'overdue']))
                                    <a href="{{ route('checkout.stripe', $invoice) }}" class="block mt-1 text-xs text-blue-600 hover:underline">Pay Now</a>
                                @endif
                            </div>
                        </div>
                        @if(!$loop->last)
                            <hr class="border-gray-200 dark:border-gray-700">
                        @endif
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No invoices yet.</p>
            @endif
        </div>
    </div>

    {{-- Expiring Domains --}}
    @if($expiringDomains->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-orange-600">Domains Expiring Soon</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($expiringDomains as $domain)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $domain->name }}</p>
                                <p class="text-xs text-orange-600">Expires {{ $domain->expiry_date->format('d M Y') }} ({{ $domain->expiry_date->diffInDays(now()) }} days)</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded bg-orange-100 text-orange-800">Expiring</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Welcome to Your Account</h2>
        <p class="text-gray-600 dark:text-gray-400">
            From here you can manage your services, view invoices, and submit support tickets.
        </p>
    </div>
</x-customer-layout>