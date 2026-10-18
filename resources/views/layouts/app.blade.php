<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-carbon-950 text-slate-100 antialiased scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'AutoDrive — Premium Car Rental & Sales Management System')</title>
    <meta name="description" content="@yield('meta_description', 'Experience premier automotive luxury. Browse our curated fleet for exotic rentals and certified vehicle acquisitions.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">

    <!-- Vite Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .font-display {
            font-family: 'Space Grotesk', sans-serif;
        }
    </style>
</head>
<body class="min-h-full flex flex-col bg-carbon-950 text-slate-200 selection:bg-electric-500 selection:text-white">

    <!-- Demo Role Switcher Quick Bar -->
    <div class="bg-carbon-900 border-b border-white/10 px-4 py-1.5 text-xs text-slate-400">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="font-medium text-slate-300">AutoDrive Role Switcher:</span>
                <span class="hidden sm:inline text-slate-500">Test different user personas with 1-click</span>
            </div>
            <div class="flex items-center flex-wrap gap-1.5 font-medium">
                <a href="{{ route('demo.login', 'admin') }}" class="px-2 py-0.5 rounded bg-purple-950/80 hover:bg-purple-800 text-purple-200 border border-purple-700/50 transition">Admin</a>
                <a href="{{ route('demo.login', 'rental') }}" class="px-2 py-0.5 rounded bg-blue-950/80 hover:bg-blue-800 text-blue-200 border border-blue-700/50 transition">Rental Mgr</a>
                <a href="{{ route('demo.login', 'sales') }}" class="px-2 py-0.5 rounded bg-amber-950/80 hover:bg-amber-800 text-amber-200 border border-amber-700/50 transition">Sales Mgr</a>
                <a href="{{ route('demo.login', 'support') }}" class="px-2 py-0.5 rounded bg-teal-950/80 hover:bg-teal-800 text-teal-200 border border-teal-700/50 transition">Support</a>
                <a href="{{ route('demo.login', 'customer') }}" class="px-2 py-0.5 rounded bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-600/50 transition">Customer</a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-50 glass-card border-b border-white/10 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-electric-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-electric-500/20 group-hover:scale-105 transition">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="font-display text-2xl font-bold tracking-tight text-white flex items-center">
                            AUTO<span class="text-electric-400">DRIVE</span>
                        </span>
                        <span class="text-[10px] tracking-widest uppercase text-slate-400 block -mt-1 font-semibold">Exotics & Sales</span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-1 font-medium text-sm">
                    <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-lg transition {{ request()->routeIs('home') ? 'text-electric-400 bg-white/5 font-semibold' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        Home
                    </a>
                    <a href="{{ route('vehicles.rent') }}" class="px-3.5 py-2 rounded-lg transition {{ request()->routeIs('vehicles.rent') ? 'text-electric-400 bg-white/5 font-semibold' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        Rent a Car
                    </a>
                    <a href="{{ route('vehicles.buy') }}" class="px-3.5 py-2 rounded-lg transition {{ request()->routeIs('vehicles.buy') ? 'text-electric-400 bg-white/5 font-semibold' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        Buy a Car
                    </a>
                    <a href="{{ route('about') }}" class="px-3.5 py-2 rounded-lg transition {{ request()->routeIs('about') ? 'text-electric-400 bg-white/5 font-semibold' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        About
                    </a>
                    <a href="{{ route('contact') }}" class="px-3.5 py-2 rounded-lg transition {{ request()->routeIs('contact') ? 'text-electric-400 bg-white/5 font-semibold' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        Contact
                    </a>
                </nav>

                <!-- Authentication CTA / User Menu -->
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        @php
                            $roleRoute = match(auth()->user()->role) {
                                'admin' => route('admin.dashboard'),
                                'rental_manager' => route('rental.dashboard'),
                                'sales_manager' => route('sales.dashboard'),
                                'customer_support' => route('support.dashboard'),
                                default => route('customer.dashboard')
                            };
                        @endphp
                        <a href="{{ $roleRoute }}" class="px-4 py-2 rounded-lg bg-carbon-800 hover:bg-carbon-700 text-white text-sm font-semibold border border-white/10 flex items-center gap-2 transition">
                            <span class="w-2 h-2 rounded-full bg-electric-400"></span>
                            <span>{{ auth()->user()->name }}</span>
                            <span class="text-xs text-electric-400 font-mono">({{ strtoupper(str_replace('_', ' ', auth()->user()->role)) }})</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-400 transition" title="Log out">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-slate-300 hover:text-white transition">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-electric-500 to-indigo-600 hover:from-electric-400 hover:to-indigo-500 text-white text-sm font-semibold shadow-lg shadow-electric-500/25 transition">
                            Register
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex md:hidden items-center">
                    <button type="button" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="p-2 text-slate-400 hover:text-white focus:outline-none">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Drawer -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-white/10 bg-carbon-900/95 backdrop-blur-xl px-4 pt-3 pb-6 space-y-2">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-slate-200 hover:bg-white/5">Home</a>
            <a href="{{ route('vehicles.rent') }}" class="block px-3 py-2 rounded-lg text-slate-200 hover:bg-white/5">Rent a Car</a>
            <a href="{{ route('vehicles.buy') }}" class="block px-3 py-2 rounded-lg text-slate-200 hover:bg-white/5">Buy a Car</a>
            <a href="{{ route('about') }}" class="block px-3 py-2 rounded-lg text-slate-200 hover:bg-white/5">About</a>
            <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-lg text-slate-200 hover:bg-white/5">Contact</a>
            <div class="pt-3 border-t border-white/10 flex flex-col gap-2">
                @auth
                    <a href="{{ $roleRoute }}" class="block text-center py-2.5 rounded-lg bg-carbon-800 text-white font-medium">My Workspace</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-center py-2 text-rose-400">Log Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block text-center py-2 text-slate-300">Sign In</a>
                    <a href="{{ route('register') }}" class="block text-center py-2.5 rounded-lg bg-electric-500 text-white font-semibold">Register</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Global Alert Notifications -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-200 flex items-start gap-3 shadow-lg">
                <svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div class="p-4 rounded-xl bg-rose-950/80 border border-rose-500/40 text-rose-200 flex items-start gap-3 shadow-lg">
                <svg class="w-5 h-5 text-rose-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm font-medium">
                    @if(session('error'))
                        <div>{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                        <ul class="list-disc list-inside space-y-1 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Main Page Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-carbon-950 border-t border-white/10 pt-16 pb-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-electric-500 to-indigo-600 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <span class="font-display text-2xl font-bold tracking-tight text-white">
                            AUTO<span class="text-electric-400">DRIVE</span>
                        </span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-sm">
                        Curating the world’s most formidable automotive fleet. From exotic track-bred supercars and executive sedans for daily rental to pristine certified pre-owned acquisitions.
                    </p>
                    <div class="flex items-center gap-3 text-xs text-slate-400">
                        <span class="px-2.5 py-1 rounded-md bg-white/5 border border-white/10 text-slate-300">Verified Fleet</span>
                        <span class="px-2.5 py-1 rounded-md bg-white/5 border border-white/10 text-slate-300">24/7 Concierge</span>
                        <span class="px-2.5 py-1 rounded-md bg-white/5 border border-white/10 text-slate-300">Secure Escrow</span>
                    </div>
                </div>

                <div>
                    <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Marketplace</h3>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        <li><a href="{{ route('vehicles.rent') }}" class="hover:text-electric-400 transition">Rent a Car</a></li>
                        <li><a href="{{ route('vehicles.buy') }}" class="hover:text-electric-400 transition">Buy a Car</a></li>
                        <li><a href="{{ route('vehicles.rent', ['category_id' => 1]) }}" class="hover:text-electric-400 transition">Luxury Sedans</a></li>
                        <li><a href="{{ route('vehicles.rent', ['category_id' => 2]) }}" class="hover:text-electric-400 transition">Track & Sports</a></li>
                        <li><a href="{{ route('vehicles.rent', ['category_id' => 5]) }}" class="hover:text-electric-400 transition">Electric Hypercars</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Company</h3>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        <li><a href="{{ route('about') }}" class="hover:text-electric-400 transition">Our Provenance</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-electric-400 transition">Customer Support</a></li>
                        <li><a href="{{ route('about') }}#inspection" class="hover:text-electric-400 transition">150-Point Inspection</a></li>
                        <li><a href="{{ route('about') }}#terms" class="hover:text-electric-400 transition">Insurance & Coverage</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Flagship Terminal</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        100 Ocean Drive, Suite 500<br>
                        Miami Beach, FL 33139<br><br>
                        <span class="text-slate-300 font-medium">Concierge Hotline:</span><br>
                        +1 (800) 555-DRIVE<br>
                        <span class="text-slate-300 font-medium">Private Aircraft Line:</span><br>
                        vip@autodrive.com
                    </p>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p>&copy; {{ date('Y') }} AutoDrive International Inc. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-slate-400 transition">Privacy Policy</a>
                    <a href="#" class="hover:text-slate-400 transition">Terms of Service</a>
                    <a href="#" class="hover:text-slate-400 transition">Security Protocols</a>
                </div>
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
