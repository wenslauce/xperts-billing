<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Xperts Africa') }} - Admin</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <style>
            .sidebar-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 0.75rem; font-size: 0.875rem; font-weight: 500; border-radius: 0.5rem; color: #64748b; transition: all 0.2s ease; }
            .sidebar-link:hover { background: rgba(240, 86, 34, 0.08); color: #f05622; }
            .sidebar-link.active { background: rgba(240, 86, 34, 0.12); color: #f05622; font-weight: 600; }
            .sidebar-link svg { width: 1.25rem; height: 1.25rem; flex-shrink: 0; }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: true }" class="min-h-screen bg-gray-100 dark:bg-gray-900">
            {{-- Top Navbar --}}
            <nav class="fixed top-0 left-0 right-0 z-40 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 h-16">
                <div class="flex items-center justify-between h-full px-4 sm:px-6">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <a href="{{ route('admin.dashboard') }}">
                            <img src="{{ asset('images/logos/logo-dark.png') }}" alt="{{ config('app.name') }}" class="h-8 w-auto">
                        </a>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 text-sm text-gray-500 hover:text-xperts-orange dark:text-gray-400 dark:hover:text-gray-200 transition px-3 py-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <div class="flex pt-16">
                {{-- Sidebar --}}
                <aside x-show="sidebarOpen" x-transition:enter="transition-all duration-200" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" class="fixed left-0 top-16 bottom-0 z-30 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 overflow-y-auto">
                    <nav class="p-3 space-y-0.5">
                        @can('view dashboard')
                            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                <span>Dashboard</span>
                            </a>
                        @endcan

                        @can('manage customers')
                            <a href="{{ route('admin.customers.index') }}" class="sidebar-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                                <span>Customers</span>
                            </a>
                        @endcan

                        @can('manage products')
                            <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                <span>Products</span>
                            </a>
                        @endcan

                        @can('manage orders')
                            <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                <span>Orders</span>
                            </a>
                        @endcan

                        @can('manage invoices')
                            <a href="{{ route('admin.invoices.index') }}" class="sidebar-link {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Invoices</span>
                            </a>
                        @endcan

                        @can('manage payments')
                            <a href="{{ route('admin.invoices.index') }}" class="sidebar-link">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                <span>Payments</span>
                            </a>
                        @endcan

                        @can('manage servers')
                            <a href="{{ route('admin.servers.index') }}" class="sidebar-link {{ request()->routeIs('admin.servers.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                                <span>Servers</span>
                            </a>
                        @endcan

                        @can('manage tickets')
                            <a href="{{ route('admin.tickets.index') }}" class="sidebar-link {{ request()->routeIs('admin.tickets.*') || request()->routeIs('admin.ticket-departments.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                <span>Tickets</span>
                            </a>
                        @endcan

                        @can('manage domains')
                            <a href="{{ route('admin.domains.index') }}" class="sidebar-link {{ request()->routeIs('admin.domains.*') && !request()->routeIs('admin.tld-prices.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                <span>Domains</span>
                            </a>
                            <a href="{{ route('admin.tld-prices.index') }}" class="sidebar-link {{ request()->routeIs('admin.tld-prices.*') ? 'active' : '' }} ml-6">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>TLD Prices</span>
                            </a>
                        @endcan

                        @can('manage reports')
                            <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                <span>Reports</span>
                            </a>
                        @endcan

                        @can('manage settings')
                            <a href="{{ route('admin.settings.payments') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Settings</span>
                            </a>
                        @endcan

                        <hr class="my-3 border-gray-200 dark:border-gray-700">

                        <a href="{{ route('dashboard') }}" class="sidebar-link text-gray-400 hover:text-xperts-orange">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            <span>Back to Site</span>
                        </a>
                    </nav>
                </aside>

                {{-- Overlay for mobile --}}
                <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black/20 lg:hidden" style="display:none"></div>

                {{-- Main Content - margin-left matches sidebar width when open --}}
                <main :style="sidebarOpen ? 'margin-left: 16rem' : 'margin-left: 0'" class="flex-1 p-6 transition-all duration-200 min-h-screen">
                    @if (isset($header))
                        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">{{ $header }}</h1>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>