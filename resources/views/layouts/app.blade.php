<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#0a0b0d] text-slate-100 antialiased scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'AutoDrive — Premium Cars For Sale & Rent')</title>
    <meta name="description" content="@yield('meta_description', 'Discover premium cars for sale or rent. Find your perfect ride, your way.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Vite Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0a0b0d;
        }
        .font-display {
            font-family: 'Space Grotesk', sans-serif;
        }
        /* Gold Accent Theme Tokens */
        .gold-accent {
            color: #e5a952;
        }
        .bg-gold-accent {
            background-color: #e5a952;
        }
        .border-gold-accent {
            border-color: #e5a952;
        }
    </style>
</head>
<body class="min-h-full flex flex-col bg-[#0a0b0d] text-slate-200 selection:bg-[#e5a952] selection:text-black">    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-50 bg-[#0a0b0d]/60 backdrop-blur-md border-b border-white/5 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Logo (Red Aerodynamic Silhouette & Wordmark) -->
                <a href="{{ route('home') }}" class="flex items-center group py-1" title="AutoDrive Home">
                    <x-application-logo variant="red" />
                </a>

                <!-- Desktop Navigation Links (Centered) -->
                <nav class="hidden md:flex items-center gap-7 lg:gap-8 font-medium text-sm lg:text-[15px]">
                    <a href="{{ route('home') }}"
                       class="transition-colors py-1 relative {{ request()->routeIs('home') ? 'text-[#ef4444] font-medium after:absolute after:-bottom-3 after:left-0 after:right-0 after:h-[2px] after:bg-[#ef4444]' : 'text-slate-100 hover:text-white' }}">
                        Home
                    </a>
                    <a href="{{ route('vehicles.buy') }}"
                       class="transition-colors py-1 relative {{ request()->routeIs('vehicles.buy') ? 'text-[#ef4444] font-medium after:absolute after:-bottom-3 after:left-0 after:right-0 after:h-[2px] after:bg-[#ef4444]' : 'text-slate-100 hover:text-white' }}">
                        Cars for Sale
                    </a>
                    <a href="{{ route('vehicles.rent') }}"
                       class="transition-colors py-1 relative {{ request()->routeIs('vehicles.rent') ? 'text-[#ef4444] font-medium after:absolute after:-bottom-3 after:left-0 after:right-0 after:h-[2px] after:bg-[#ef4444]' : 'text-slate-100 hover:text-white' }}">
                        Rentals
                    </a>
                    <a href="{{ route('home') }}#services"
                       class="transition-colors py-1 relative text-slate-100 hover:text-white">
                        Services
                    </a>
                    <a href="{{ route('about') }}"
                       class="transition-colors py-1 relative {{ request()->routeIs('about') ? 'text-[#ef4444] font-medium after:absolute after:-bottom-3 after:left-0 after:right-0 after:h-[2px] after:bg-[#ef4444]' : 'text-slate-100 hover:text-white' }}">
                        About
                    </a>
                    <a href="{{ route('contact') }}"
                       class="transition-colors py-1 relative {{ request()->routeIs('contact') ? 'text-[#ef4444] font-medium after:absolute after:-bottom-3 after:left-0 after:right-0 after:h-[2px] after:bg-[#ef4444]' : 'text-slate-100 hover:text-white' }}">
                        Contact
                    </a>
                </nav>

                <!-- Right Action Icons & Get Started Button -->
                <div class="hidden md:flex items-center gap-5 lg:gap-6">
                    <!-- Search Icon -->
                    <button type="button" onclick="document.getElementById('search-filter-section')?.scrollIntoView({behavior: 'smooth'})" class="text-slate-100 hover:text-white transition-colors p-1" title="Search Cars">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                    </button>

                    <!-- Favorites / Wishlist Icon -->
                    <a href="{{ route('vehicles.rent') }}" class="text-slate-100 hover:text-[#ef4444] transition-colors p-1" title="Favorites">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                        </svg>
                    </a>

                    <!-- Profile Icon -->
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
                        <a href="{{ $roleRoute }}" class="text-slate-100 hover:text-white transition-colors p-1 flex items-center gap-1.5" title="Account Console ({{ auth()->user()->name }})">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-1 text-slate-400 hover:text-rose-400 transition" title="Log out">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-100 hover:text-white transition-colors p-1" title="Sign In">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                        </a>
                    @endauth

                    <!-- Get Started Pill Button -->
                    <a href="{{ auth()->check() ? $roleRoute : route('register') }}"
                       class="inline-flex items-center gap-2.5 px-6 py-2.5 rounded-full border border-white/70 hover:border-white bg-black/40 hover:bg-white/10 text-white text-sm font-medium backdrop-blur-sm transition-all duration-200 group shadow-sm ml-1">
                        <span>Get Started</span>
                        <svg class="w-4 h-4 text-white group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex md:hidden items-center">
                    <button type="button" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="p-2 text-slate-300 hover:text-white focus:outline-none">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Drawer -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-white/10 bg-[#0a0b0d]/95 backdrop-blur-xl px-4 pt-3 pb-6 space-y-2">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('home') ? 'text-[#ef4444] font-semibold bg-white/5' : 'text-slate-200 hover:bg-white/5' }}">Home</a>
            <a href="{{ route('vehicles.buy') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('vehicles.buy') ? 'text-[#ef4444] font-semibold bg-white/5' : 'text-slate-200 hover:bg-white/5' }}">Cars for Sale</a>
            <a href="{{ route('vehicles.rent') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('vehicles.rent') ? 'text-[#ef4444] font-semibold bg-white/5' : 'text-slate-200 hover:bg-white/5' }}">Rentals</a>
            <a href="{{ route('home') }}#services" class="block px-3 py-2 rounded-lg text-slate-200 hover:bg-white/5">Services</a>
            <a href="{{ route('about') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('about') ? 'text-[#ef4444] font-semibold bg-white/5' : 'text-slate-200 hover:bg-white/5' }}">About</a>
            <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('contact') ? 'text-[#ef4444] font-semibold bg-white/5' : 'text-slate-200 hover:bg-white/5' }}">Contact</a>
            <div class="pt-3 border-t border-white/10 flex flex-col gap-2">
                @auth
                    <a href="{{ $roleRoute }}" class="block text-center py-2.5 rounded-lg bg-white/10 text-white font-medium">My Workspace</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-center py-2 text-rose-400">Log Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block text-center py-2 text-slate-300">Sign In</a>
                    <a href="{{ route('register') }}" class="flex items-center justify-center gap-2 py-2.5 rounded-full border border-white/80 bg-black/40 text-white font-medium">
                        <span>Get Started</span>
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Notification Toasts / Flash Messages -->
    @if(session('success') || session('error') || $errors->any())
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
    @endif

    <!-- Main Page Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Sleek Luxury Footer Matching Mockup -->
    <footer class="bg-[#0b0c10] border-t border-white/10 py-8 text-xs text-slate-400 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <!-- Left: Logo -->
            <a href="{{ route('home') }}" class="flex items-center">
                <x-application-logo variant="gold" />
            </a>

            <!-- Center: Links -->
            <nav class="flex flex-wrap items-center justify-center gap-6 text-xs text-slate-400 font-medium">
                <a href="{{ route('home') }}" class="hover:text-white transition {{ request()->routeIs('home') ? 'text-white' : '' }}">Home</a>
                <a href="{{ route('vehicles.buy') }}" class="hover:text-white transition">Cars</a>
                <a href="{{ route('vehicles.buy') }}" class="hover:text-white transition">Sell</a>
                <a href="{{ route('vehicles.rent') }}" class="hover:text-white transition">Rent</a>
                <a href="{{ route('about') }}" class="hover:text-white transition">About</a>
                <a href="{{ route('contact') }}" class="hover:text-white transition">Contact</a>
            </nav>

            <!-- Right: Social Icons & Copyright -->
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3.5 text-slate-400">
                    <a href="#" class="hover:text-[#e5a952] transition" title="Facebook">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="hover:text-[#e5a952] transition" title="Instagram">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="#" class="hover:text-[#e5a952] transition" title="X (Twitter)">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="#" class="hover:text-[#e5a952] transition" title="YouTube">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                    <a href="#" class="hover:text-[#e5a952] transition" title="LinkedIn">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    </a>
                </div>
                <span class="text-slate-500 text-[11px]">&copy; 2025 AUTODRIVE. All rights reserved.</span>
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
