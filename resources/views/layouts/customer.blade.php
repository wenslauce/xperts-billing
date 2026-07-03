<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Xperts Billing') }} - My Account</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            {{-- Top Navbar --}}
            <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <a href="{{ route('customer.dashboard') }}" class="text-xl font-bold text-gray-800 dark:text-white">
                                {{ config('app.name') }}
                            </a>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ auth()->user()->name }}
                            </span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="flex">
                {{-- Sidebar --}}
                <aside class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 min-h-[calc(100vh-4rem)]">
                    <nav class="mt-5 px-3 space-y-1">
                        <a href="{{ route('customer.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('customer.dashboard') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                            <span class="mr-3">📊</span>
                            Dashboard
                        </a>

                        <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <span class="mr-3">🖥️</span>
                            My Services
                        </a>

                        <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <span class="mr-3">📄</span>
                            Invoices
                        </a>

                        <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <span class="mr-3">🌐</span>
                            Domains
                        </a>

                        <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <span class="mr-3">🎫</span>
                            Support Tickets
                        </a>

                        <hr class="my-4 border-gray-200 dark:border-gray-700">

                        <a href="{{ route('profile') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <span class="mr-3">👤</span>
                            Profile
                        </a>
                    </nav>
                </aside>

                {{-- Main Content --}}
                <main class="flex-1 p-6">
                    @if (isset($header))
                        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">{{ $header }}</h1>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>