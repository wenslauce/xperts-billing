<x-customer-layout>
    <x-slot:header>Web Hosting Plans</x-slot:header>

    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <span class="px-4 py-1.5 rounded-full bg-xperts-orange/10 text-xperts-orange text-sm font-medium border border-xperts-orange/25">Hosting Plans</span>
            <h2 class="text-4xl font-bold mt-4 gradient-text">Powerful Hosting Solutions</h2>
            <p class="text-slate-600 mt-2">Choose the perfect hosting plan for your website.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 border border-slate-200 dark:border-gray-700 shadow-lg card-hover">
                <h3 class="text-xl font-bold dark:text-white">Starter</h3>
                <div class="mt-4 mb-6">
                    <span class="text-4xl font-bold dark:text-white">$3</span><span class="text-slate-500">/mo</span>
                </div>
                <ul class="space-y-3 text-slate-600 dark:text-gray-400">
                    <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> 100 GB Storage</li>
                    <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> 10 GB Bandwidth</li>
                    <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> 1 Website</li>
                    <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Free SSL Certificate</li>
                </ul>
                <a href="{{ route('register') }}" class="mt-8 block text-center gradient-btn text-white py-3 rounded-lg font-medium hover:shadow-lg transition">Get Started</a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 border-2 border-xperts-orange shadow-xl card-hover relative">
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 gradient-btn text-white px-4 py-1 rounded-full text-sm font-medium">Popular</div>
                <h3 class="text-xl font-bold dark:text-white">Pro Plan</h3>
                <div class="mt-4 mb-6">
                    <span class="text-4xl font-bold dark:text-white">$15</span><span class="text-slate-500">/mo</span>
                </div>
                <ul class="space-y-3 text-slate-600 dark:text-gray-400">
                    <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Unlimited Storage</li>
                    <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> 50 GB Bandwidth</li>
                    <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Unlimited Websites</li>
                    <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Free SSL + Domain</li>
                </ul>
                <a href="{{ route('register') }}" class="mt-8 block text-center gradient-btn text-white py-3 rounded-lg font-medium hover:shadow-lg transition">Get Started</a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 border border-slate-200 dark:border-gray-700 shadow-lg card-hover">
                <h3 class="text-xl font-bold dark:text-white">Business Plan</h3>
                <div class="mt-4 mb-6">
                    <span class="text-4xl font-bold dark:text-white">$25</span><span class="text-slate-500">/mo</span>
                </div>
                <ul class="space-y-3 text-slate-600 dark:text-gray-400">
                    <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Unlimited Storage</li>
                    <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> 250 GB Bandwidth</li>
                    <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Unlimited Websites</li>
                    <li class="flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Priority Support</li>
                </ul>
                <a href="{{ route('register') }}" class="mt-8 block text-center gradient-btn text-white py-3 rounded-lg font-medium hover:shadow-lg transition">Get Started</a>
            </div>
        </div>
    </div>
</x-customer-layout>