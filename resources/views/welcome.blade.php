<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Xperts Africa') }} | Web Hosting & Domain Registration</title>
    <meta name="description" content="Xperts Africa - Domains, hosting, and web development solutions tailored for African businesses. 99.9% uptime, 24/7 support.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .gradient-text { background: linear-gradient(135deg, #f05622, #f26a3e, #f05622); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .gradient-btn { background: linear-gradient(135deg, #f05622, #d0481d); }
        .gradient-btn:hover { background: linear-gradient(135deg, #d0481d, #b03a15); }
        .hero-gradient-light { background: linear-gradient(135deg, #fff7f4 0%, #fef2ef 50%, #fff7f4 100%); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(240, 86, 34, 0.15); }
        .animate-float { animation: float 6s ease-in-out infinite; }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .xperts-badge { background: linear-gradient(135deg, rgba(240, 86, 34, 0.12), rgba(240, 86, 34, 0.04)); border: 1px solid rgba(240, 86, 34, 0.25); }
        .hero-image-container { position: relative; width: 100%; max-width: 500px; margin: 0 auto; }
        .hero-image-container img { width: 100%; height: auto; border-radius: 1.5rem; }
    </style>
</head>
<body class="font-sans antialiased bg-white text-slate-800">
    {{-- Navbar --}}
    <nav class="fixed top-0 z-50 w-full bg-white/90 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold">
                        <span class="gradient-text">{{ config('app.name', 'Xperts') }}</span>
                    </a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/domains" class="text-sm font-medium text-slate-600 hover:text-xperts-orange transition">Domains</a>
                    <a href="/hosting" class="text-sm font-medium text-slate-600 hover:text-xperts-orange transition">Hosting</a>
                    <a href="#features" class="text-sm font-medium text-slate-600 hover:text-xperts-orange transition">Features</a>
                    <a href="#pricing" class="text-sm font-medium text-slate-600 hover:text-xperts-orange transition">Pricing</a>
                </div>
                <div class="flex items-center space-x-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-xperts-orange">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-xperts-orange">Login</a>
                        <a href="{{ route('register') }}" class="gradient-btn text-white px-4 py-2 rounded-lg text-sm font-medium hover:shadow-lg transition">Get Started</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section (Light Mode) --}}
    <section class="hero-gradient-light min-h-screen pt-16 relative overflow-hidden">
        <div class="absolute inset-0 opacity-[0.07]">
            <div class="absolute top-20 left-20 w-72 h-72 bg-xperts-orange rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-20 w-96 h-96 bg-xperts-orange rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-32">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-8">
                    <div>
                        <span class="inline-flex items-center px-4 py-2 rounded-full xperts-badge text-xperts-orange text-sm font-medium">
                            Africa's Premier Digital Services Provider
                        </span>
                    </div>
                    <h1 class="text-4xl md:text-6xl font-bold text-slate-900 leading-tight">
                        Your Digital Journey
                        <span class="gradient-text">Starts Here</span>
                    </h1>
                    <p class="text-xl text-slate-600">Domains, hosting, and web development solutions tailored for African businesses. 99.9% uptime, 24/7 support.</p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('register') }}" class="gradient-btn text-white px-8 py-3 rounded-lg font-medium hover:shadow-lg hover:shadow-xperts-orange/25 transition-all flex items-center gap-2">
                            Get Started
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="/hosting" class="border-2 border-xperts-orange/30 text-xperts-orange hover:bg-xperts-orange/5 px-8 py-3 rounded-lg font-medium transition-all">Explore Hosting</a>
                    </div>
                    <div class="flex items-center gap-8 text-slate-500 text-sm">
                        <div class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Quick Setup</div>
                        <div class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> 30-Day Money Back</div>
                        <div class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> 24/7 Support</div>
                    </div>
                </div>
                <div class="relative animate-float">
                    <div class="absolute -inset-4 bg-gradient-to-r from-xperts-orange/15 to-xperts-orange-light/15 rounded-3xl blur-3xl"></div>
                    <div class="relative bg-white/80 backdrop-blur-sm rounded-3xl p-8 border border-xperts-orange/10 shadow-xl">
                        <div class="text-center">
                            <div class="hero-image-container">
                                @php $heroImage = public_path('images/hero-image.png'); @endphp
                                @if(file_exists($heroImage))
                                    <img src="{{ asset('images/hero-image.png') }}" alt="Xperts Africa Hosting" class="rounded-2xl shadow-lg">
                                @else
                                    <div class="w-full aspect-video bg-gradient-to-br from-xperts-orange/5 to-xperts-orange/10 rounded-2xl flex items-center justify-center border border-xperts-orange/10">
                                        <div class="text-center p-8">
                                            <svg class="w-20 h-20 text-xperts-orange mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
                                            <h3 class="text-2xl font-bold text-slate-800 mb-2">Fast & Reliable Hosting</h3>
                                            <p class="text-slate-500">Powered by DirectAdmin SSD Servers</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Domain Search --}}
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="px-4 py-1.5 rounded-full xperts-badge text-xperts-orange text-sm font-medium">Domain Search</span>
                <h2 class="text-4xl font-bold mt-4 gradient-text">Find Your Perfect Domain</h2>
                <p class="text-slate-600 mt-2">Search for available domains and secure your online presence.</p>
            </div>
            <div class="max-w-3xl mx-auto">
                <form action="{{ route('domain.check') }}" method="GET" class="bg-white rounded-2xl shadow-xl p-6 border border-slate-200">
                    <div class="flex gap-3">
                        <input type="text" name="domain" placeholder="Enter your domain name" value="{{ request('domain') }}" class="flex-1 px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-xperts-orange focus:border-xperts-orange outline-none" required>
                        <button type="submit" class="gradient-btn text-white px-8 py-3 rounded-lg font-medium hover:shadow-lg transition">Search</button>
                    </div>
                </form>

                @if(session('domain_result'))
                    @php $result = session('domain_result'); @endphp
                    <div class="mt-6 bg-white rounded-2xl shadow-lg p-6 border border-slate-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold">{{ $result['domain'] }}</h3>
                                @if($result['available'] ?? false)
                                    <p class="text-green-600 font-medium mt-1">✅ Available! <a href="{{ route('register') }}" class="text-xperts-orange hover:underline">Register now</a></p>
                                @else
                                    <p class="text-red-600 font-medium mt-1">❌ Already registered</p>
                                    <p class="text-sm text-slate-500 mt-1">Expires: {{ $result['expires'] ?? 'N/A' }}</p>
                                @endif
                            </div>
                            @if($result['available'] ?? false)
                                <a href="{{ route('register') }}" class="gradient-btn text-white px-6 py-2 rounded-lg font-medium text-sm">Register</a>
                            @endif
                        </div>
                    </div>
                @endif

                @if(session('domain_alternatives'))
                    <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach(session('domain_alternatives') as $alt)
                            <div class="bg-white rounded-lg p-3 border border-slate-200 text-center">
                                <p class="text-sm font-medium">{{ $alt['domain'] }}</p>
                                <p class="text-xs {{ $alt['available'] ?? false ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $alt['available'] ?? false ? 'Available' : 'Taken' }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section id="features" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="px-4 py-1.5 rounded-full xperts-badge text-xperts-orange text-sm font-medium">Our Services</span>
                <h2 class="text-4xl font-bold mt-4 gradient-text">Comprehensive Digital Solutions</h2>
                <p class="text-slate-600 mt-2">Everything you need to succeed online.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="card-hover bg-white rounded-2xl p-8 border border-slate-200 shadow-lg">
                    <div class="w-14 h-14 bg-xperts-orange/10 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-xperts-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Web Hosting</h3>
                    <p class="text-slate-600">Fast, secure, and reliable hosting with 99.9% uptime guarantee and 24/7 expert support.</p>
                </div>
                <div class="card-hover bg-white rounded-2xl p-8 border border-slate-200 shadow-lg">
                    <div class="w-14 h-14 bg-xperts-orange/10 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-xperts-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Domain Registration</h3>
                    <p class="text-slate-600">Register and manage domains with free WHOIS privacy protection and DNS management.</p>
                </div>
                <div class="card-hover bg-white rounded-2xl p-8 border border-slate-200 shadow-lg">
                    <div class="w-14 h-14 bg-xperts-orange/10 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-xperts-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Web Development</h3>
                    <p class="text-slate-600">Custom websites and web applications built with modern technologies for your business.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Pricing (Dynamic from Database) --}}
    <section id="pricing" class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="px-4 py-1.5 rounded-full xperts-badge text-xperts-orange text-sm font-medium">Hosting Plans</span>
                <h2 class="text-4xl font-bold mt-4 gradient-text">Powerful Hosting Solutions</h2>
                <p class="text-slate-600 mt-2">Choose the perfect plan for your website.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                @forelse($hostingProducts as $product)
                    @php
                        $monthlyPricing = $product->pricing->where('billing_cycle', 'monthly')->first();
                        $price = $monthlyPricing ? $monthlyPricing->price : ($product->pricing->first()->price ?? 0);
                        $currency = $monthlyPricing->currency ?? 'USD';
                        $isPopular = $loop->index === 1;
                        $features = $product->description ? explode("\n", $product->description) : [];
                    @endphp
                    <div class="bg-white rounded-2xl p-8 {{ $isPopular ? 'border-2 border-xperts-orange shadow-xl' : 'border border-slate-200 shadow-lg' }} card-hover {{ $isPopular ? 'relative' : '' }}">
                        @if($isPopular)
                            <div class="absolute -top-4 left-1/2 -translate-x-1/2 gradient-btn text-white px-4 py-1 rounded-full text-sm font-medium">Popular</div>
                        @endif
                        <h3 class="text-xl font-bold">{{ $product->name }}</h3>
                        <div class="mt-4 mb-6">
                            <span class="text-4xl font-bold">{{ $currency === 'KES' ? 'KSh ' : '$' }}{{ number_format($price, $currency === 'KES' ? 0 : 2) }}</span><span class="text-slate-500">/mo</span>
                        </div>
                        <ul class="space-y-3 text-slate-600">
                            @forelse($features as $feature)
                                @if(trim($feature))
                                    <li class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        {{ trim($feature) }}
                                    </li>
                                @endif
                            @empty
                                <li class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Reliable hosting service
                                </li>
                            @endforelse
                        </ul>
                        <a href="{{ route('register') }}" class="mt-8 block text-center gradient-btn text-white py-3 rounded-lg font-medium hover:shadow-lg transition">Get Started</a>
                    </div>
                @empty
                    {{-- Fallback static plans if no database data --}}
                    <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-lg card-hover">
                        <h3 class="text-xl font-bold">Starter</h3>
                        <div class="mt-4 mb-6"><span class="text-4xl font-bold">$3</span><span class="text-slate-500">/mo</span></div>
                        <ul class="space-y-3 text-slate-600">
                            <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> 100 GB Storage</li>
                            <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> 10 GB Bandwidth</li>
                            <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> 1 Website</li>
                            <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Free SSL</li>
                        </ul>
                        <a href="{{ route('register') }}" class="mt-8 block text-center gradient-btn text-white py-3 rounded-lg font-medium hover:shadow-lg transition">Get Started</a>
                    </div>
                    <div class="bg-white rounded-2xl p-8 border-2 border-xperts-orange shadow-xl card-hover relative">
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2 gradient-btn text-white px-4 py-1 rounded-full text-sm font-medium">Popular</div>
                        <h3 class="text-xl font-bold">Pro Plan</h3>
                        <div class="mt-4 mb-6"><span class="text-4xl font-bold">$15</span><span class="text-slate-500">/mo</span></div>
                        <ul class="space-y-3 text-slate-600">
                            <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Unlimited Storage</li>
                            <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Unlimited Websites</li>
                            <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Free SSL + Domain</li>
                            <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Priority Support</li>
                        </ul>
                        <a href="{{ route('register') }}" class="mt-8 block text-center gradient-btn text-white py-3 rounded-lg font-medium hover:shadow-lg transition">Get Started</a>
                    </div>
                    <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-lg card-hover">
                        <h3 class="text-xl font-bold">Business Plan</h3>
                        <div class="mt-4 mb-6"><span class="text-4xl font-bold">$25</span><span class="text-slate-500">/mo</span></div>
                        <ul class="space-y-3 text-slate-600">
                            <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Unlimited Storage</li>
                            <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Unlimited Websites</li>
                            <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Priority Support</li>
                            <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> 250 GB Bandwidth</li>
                        </ul>
                        <a href="{{ route('register') }}" class="mt-8 block text-center gradient-btn text-white py-3 rounded-lg font-medium hover:shadow-lg transition">Get Started</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 bg-xperts-slate relative overflow-hidden">
        <div class="absolute inset-0 opacity-[0.08]">
            <div class="absolute top-10 left-10 w-64 h-64 bg-xperts-orange rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-4xl mx-auto text-center px-4">
            <h2 class="text-4xl font-bold text-white mb-4">Ready to Get Started?</h2>
            <p class="text-xl text-slate-300 mb-8">Join thousands of satisfied customers who trust us for their digital needs.</p>
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-white text-xperts-orange px-8 py-3 rounded-lg font-medium hover:shadow-xl transition-all">
                Get Started Today
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-xperts-slate-dark text-slate-400 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-white font-bold text-lg mb-4 gradient-text">{{ config('app.name') }}</h3>
                    <p class="text-sm">Your trusted partner for domain registration, web hosting, and web development services across Africa.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Services</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/domains" class="hover:text-white transition">Domain Registration</a></li>
                        <li><a href="/hosting" class="hover:text-white transition">Web Hosting</a></li>
                        <li><a href="#" class="hover:text-white transition">WordPress Hosting</a></li>
                        <li><a href="#" class="hover:text-white transition">Web Development</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Company</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition">Contact</a></li>
                        <li><a href="#" class="hover:text-white transition">Terms of Service</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Support</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition">Status</a></li>
                        <li><a href="mailto:support@xpertsafrica.com" class="hover:text-white transition">support@xpertsafrica.com</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-800 mt-12 pt-8 text-center text-sm">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>