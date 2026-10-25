<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-carbon-950 text-slate-100 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Workspace') — AutoDrive Management System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Vite Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-display { font-family: 'Space Grotesk', sans-serif; }
    </style>
</head>
<body class="min-h-full bg-carbon-950 text-slate-200 flex flex-col md:flex-row">

    <!-- Mobile Top Navigation Header -->
    <div class="md:hidden flex items-center justify-between p-4 bg-carbon-900 border-b border-white/10 sticky top-0 z-40">
        <a href="{{ route('home') }}" class="flex items-center">
            <x-application-logo variant="electric" class="h-8 w-auto object-contain" />
        </a>
        <button type="button" onclick="document.getElementById('dashboard-sidebar').classList.toggle('-translate-x-full')" class="p-2 text-slate-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    <!-- Sidebar Navigation -->
    <aside id="dashboard-sidebar" class="fixed md:sticky top-0 left-0 z-50 h-screen w-64 bg-carbon-900 border-r border-white/10 flex flex-col justify-between transition-transform duration-300 -translate-x-full md:translate-x-0">
        <div class="p-5 flex flex-col gap-6 overflow-y-auto">
            <!-- Brand -->
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center">
                    <x-application-logo variant="electric" class="h-9 w-auto object-contain" />
                </a>
                <button type="button" onclick="document.getElementById('dashboard-sidebar').classList.add('-translate-x-full')" class="md:hidden text-slate-400 hover:text-white p-1">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- User Persona Pill -->
            <div class="p-3 rounded-xl bg-white/5 border border-white/10">
                <div class="text-xs text-slate-400">Active Account</div>
                <div class="font-semibold text-white text-sm truncate">{{ auth()->user()->name }}</div>
                <div class="mt-1 flex items-center gap-1.5">
                    @php
                        $roleBadgeColor = match(auth()->user()->role) {
                            'admin' => 'bg-purple-900/60 text-purple-300 border-purple-700/50',
                            'rental_manager' => 'bg-blue-900/60 text-blue-300 border-blue-700/50',
                            'sales_manager' => 'bg-amber-900/60 text-amber-300 border-amber-700/50',
                            'customer_support' => 'bg-teal-900/60 text-teal-300 border-teal-700/50',
                            default => 'bg-slate-800 text-slate-300 border-slate-700'
                        };
                    @endphp
                    <span class="px-2 py-0.5 rounded text-[10px] font-mono uppercase tracking-wider border {{ $roleBadgeColor }}">
                        {{ str_replace('_', ' ', auth()->user()->role) }}
                    </span>
                </div>
            </div>

            <!-- Role-Specific Sidebar Menu Links -->
            <nav class="space-y-1 text-sm font-medium">
                @if(auth()->user()->isAdmin())
                    <div class="text-[11px] font-semibold uppercase tracking-wider text-slate-500 px-3 pt-2 pb-1">Executive Workspace</div>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-electric-500/15 text-electric-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.vehicles.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.vehicles.*') ? 'bg-electric-500/15 text-electric-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        Fleet Inventory
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.users.*') ? 'bg-electric-500/15 text-electric-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Users & Roles
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.reports.*') ? 'bg-electric-500/15 text-electric-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Financial Reports
                    </a>

                    <div class="text-[11px] font-semibold uppercase tracking-wider text-slate-500 px-3 pt-4 pb-1">Staff Workspaces</div>
                    <a href="{{ route('rental.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                        Rental Operations
                    </a>
                    <a href="{{ route('sales.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        Sales Operations
                    </a>
                    <a href="{{ route('support.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition">
                        <span class="w-1.5 h-1.5 rounded-full bg-teal-400"></span>
                        Support Tickets
                    </a>

                @elseif(auth()->user()->isRentalManager())
                    <div class="text-[11px] font-semibold uppercase tracking-wider text-slate-500 px-3 pt-2 pb-1">Rental Management</div>
                    <a href="{{ route('rental.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('rental.dashboard') ? 'bg-electric-500/15 text-electric-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Rental Schedule
                    </a>
                    <a href="{{ route('vehicles.rent') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        View Public Catalog
                    </a>

                @elseif(auth()->user()->isSalesManager())
                    <div class="text-[11px] font-semibold uppercase tracking-wider text-slate-500 px-3 pt-2 pb-1">Sales Management</div>
                    <a href="{{ route('sales.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('sales.dashboard') ? 'bg-electric-500/15 text-electric-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Sales Transactions
                    </a>
                    <a href="{{ route('vehicles.buy') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        View Purchase Catalog
                    </a>

                @elseif(auth()->user()->isCustomerSupport())
                    <div class="text-[11px] font-semibold uppercase tracking-wider text-slate-500 px-3 pt-2 pb-1">Support Workspace</div>
                    <a href="{{ route('support.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('support.dashboard') ? 'bg-electric-500/15 text-electric-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        Tickets Inbox
                    </a>

                @else
                    <!-- Customer Links -->
                    <div class="text-[11px] font-semibold uppercase tracking-wider text-slate-500 px-3 pt-2 pb-1">Customer Portal</div>
                    <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('customer.dashboard') ? 'bg-electric-500/15 text-electric-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Overview
                    </a>
                    <a href="{{ route('customer.bookings') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('customer.bookings') ? 'bg-electric-500/15 text-electric-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        My Rentals
                    </a>
                    <a href="{{ route('customer.purchases') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('customer.purchases') ? 'bg-electric-500/15 text-electric-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        My Purchases
                    </a>
                    <a href="{{ route('customer.payments') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('customer.payments') ? 'bg-electric-500/15 text-electric-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        Payments & Receipts
                    </a>
                    <a href="{{ route('customer.tickets.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('customer.tickets.*') ? 'bg-electric-500/15 text-electric-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Support Tickets
                    </a>
                    <a href="{{ route('customer.profile') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('customer.profile') ? 'bg-electric-500/15 text-electric-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Profile Settings
                    </a>
                @endif
            </nav>
        </div>

        <!-- Sidebar Bottom Actions -->
        <div class="p-4 border-t border-white/10 space-y-2">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-xs text-slate-400 hover:text-white transition px-2 py-1.5 rounded-lg hover:bg-white/5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Return to Website
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 text-xs text-rose-400 hover:text-rose-300 transition px-2 py-1.5 rounded-lg hover:bg-rose-950/30">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Workspace Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top Workspace Bar -->
        <header class="bg-carbon-900/60 backdrop-blur-xl border-b border-white/10 px-6 py-4 flex items-center justify-between sticky top-0 z-30">
            <div>
                <h1 class="text-xl font-bold text-white tracking-tight">@yield('page_title', 'Workspace')</h1>
                <p class="text-xs text-slate-400">@yield('page_subtitle', 'AutoDrive Management Console')</p>
            </div>


        </header>

        <!-- Flash Messages -->
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-200 flex items-start gap-3 shadow-lg">
                    <svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="text-sm font-medium">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error') || $errors->any())
                <div class="p-4 rounded-xl bg-rose-950/80 border border-rose-500/40 text-rose-200 flex items-start gap-3 shadow-lg">
                    <svg class="w-5 h-5 text-rose-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="text-sm font-medium">
                        @if(session('error')) <div>{{ session('error') }}</div> @endif
                        @if($errors->any())
                            <ul class="list-disc list-inside mt-1">
                                @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Dashboard Content Body -->
        <div class="p-6 flex-grow">
            @yield('content')
        </div>
    </div>

    @yield('scripts')
</body>
</html>
