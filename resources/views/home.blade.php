@extends('layouts.app')

@section('title', 'AutoDrive — Premium Automotive Rentals & Certified Acquisitions')
@section('meta_description', 'Discover pure automotive excellence. Rent exotic sports cars, bespoke luxury sedans, or acquire certified pre-owned supercars at AutoDrive.')

@section('content')
    <!-- 1. Cinematic Hero Section -->
    <section class="relative min-h-[85vh] flex items-center justify-center overflow-hidden border-b border-white/10">
        <!-- Hero Background Image with Multi-Layer Gradients -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?auto=format&fit=crop&w=2000&q=85"
                 alt="AutoDrive Flagship Luxury Fleet"
                 class="w-full h-full object-cover object-center scale-105 animate-[pulse_10s_ease-in-out_infinite]">
            <div class="absolute inset-0 bg-gradient-to-r from-carbon-950 via-carbon-950/85 to-carbon-950/60"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-carbon-950 via-transparent to-carbon-950/40"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 w-full">
            <div class="max-w-3xl space-y-6">
                <!-- Tagline Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/5 border border-white/15 backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-electric-400 animate-ping"></span>
                    <span class="text-xs font-semibold uppercase tracking-widest text-slate-300">Miami Beach &bull; World-Class Fleet</span>
                </div>

                <!-- Headline -->
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-white leading-tight font-display">
                    Find Your Drive.<br>
                    <span class="text-gradient-electric">Own Your Journey.</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-base sm:text-lg text-slate-300 font-normal leading-relaxed max-w-2xl">
                    Engineered for discerning connoisseurs. Experience uncompromising exotic rentals, tailored executive charters, or secure acquisitions of certified high-performance vehicles.
                </p>

                <!-- Dual Quick Action Buttons -->
                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <a href="{{ route('vehicles.rent') }}" class="px-6 py-3.5 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-sm shadow-xl shadow-electric-500/30 transition transform hover:-translate-y-0.5">
                        Explore Rental Fleet
                    </a>
                    <a href="{{ route('vehicles.buy') }}" class="px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/15 text-white font-semibold text-sm backdrop-blur-md border border-white/20 transition">
                        Browse Cars For Sale
                    </a>
                </div>
            </div>

            <!-- 2. Interactive Search & Booking Form with Tabs -->
            <div class="mt-12 glass-card rounded-2xl p-6 sm:p-8 border border-white/15 shadow-2xl">
                <!-- Switcher Tabs -->
                <div class="flex items-center gap-4 border-b border-white/10 pb-4 mb-6">
                    <button type="button" id="tab-rent" onclick="switchSearchTab('rent')"
                            class="flex items-center gap-2 text-sm font-bold pb-2 border-b-2 border-electric-400 text-white transition">
                        <svg class="w-4 h-4 text-electric-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Rent a Vehicle
                    </button>
                    <button type="button" id="tab-buy" onclick="switchSearchTab('buy')"
                            class="flex items-center gap-2 text-sm font-medium pb-2 border-b-2 border-transparent text-slate-400 hover:text-white transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Buy a Vehicle
                    </button>
                </div>

                <!-- Rent Search Form -->
                <form id="form-rent" method="GET" action="{{ route('vehicles.rent') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Category</label>
                        <select name="category_id" class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }} ({{ $category->vehicles_count }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Make / Brand</label>
                        <select name="brand" class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                            <option value="">All Brands</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand }}">{{ $brand }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Max Daily Rate</label>
                        <select name="max_daily_rate" class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                            <option value="">Any Budget</option>
                            <option value="500">Under $500/day</option>
                            <option value="1000">Under $1,000/day</option>
                            <option value="1500">Under $1,500/day</option>
                            <option value="2500">Under $2,500/day</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Availability</label>
                        <select name="availability" class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                            <option value="available">Available Now</option>
                            <option value="">All Vehicles</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-sm shadow-lg shadow-electric-500/25 transition">
                            Search Rentals
                        </button>
                    </div>
                </form>

                <!-- Buy Search Form (Hidden by default, switched via JS) -->
                <form id="form-buy" method="GET" action="{{ route('vehicles.buy') }}" class="hidden grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Category</label>
                        <select name="category_id" class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Make / Brand</label>
                        <select name="brand" class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                            <option value="">All Brands</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand }}">{{ $brand }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Condition</label>
                        <select name="condition" class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                            <option value="">Any Condition</option>
                            <option value="new">New</option>
                            <option value="certified_preowned">Certified Pre-Owned</option>
                            <option value="used">Pre-Owned</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Max Price</label>
                        <select name="max_price" class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                            <option value="">Any Price</option>
                            <option value="150000">Under $150,000</option>
                            <option value="250000">Under $250,000</option>
                            <option value="350000">Under $350,000</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-sm shadow-lg shadow-electric-500/25 transition">
                            Search Cars for Sale
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- 3. Fleet Categories Carousel/Grid -->
    <section class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-electric-400 block mb-2">Distinctive Classes</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-display tracking-tight">
                    Explore By Category
                </h2>
            </div>
            <p class="text-slate-400 text-sm max-w-md">
                Every vehicle in our collection represents the pinnacle of its class, curated for unparalleled driving dynamics.
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories as $cat)
                <a href="{{ route('vehicles.rent', ['category_id' => $cat->id]) }}"
                   class="glass-card glass-card-hover rounded-2xl p-5 text-center flex flex-col items-center justify-between group border border-white/10">
                    <div class="w-14 h-14 rounded-2xl bg-white/5 group-hover:bg-electric-500/20 border border-white/10 group-hover:border-electric-500/40 flex items-center justify-center transition mb-3">
                        @if($cat->slug === 'sports')
                            <svg class="w-7 h-7 text-electric-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        @elseif($cat->slug === 'luxury')
                            <svg class="w-7 h-7 text-electric-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        @elseif($cat->slug === 'electric')
                            <svg class="w-7 h-7 text-electric-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                        @elseif($cat->slug === 'suv')
                            <svg class="w-7 h-7 text-electric-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                        @elseif($cat->slug === 'pickup')
                            <svg class="w-7 h-7 text-electric-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                        @else
                            <svg class="w-7 h-7 text-electric-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        @endif
                    </div>
                    <span class="font-bold text-white text-sm group-hover:text-electric-300 transition">{{ $cat->name }}</span>
                    <span class="text-xs text-slate-400 mt-1">{{ $cat->vehicles_count }} Models</span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- 4. Featured Rental Vehicles -->
    <section class="py-16 bg-carbon-900/50 border-y border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-electric-400 block mb-2">Prime Availability</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-display tracking-tight">
                        Featured Rental Exotics
                    </h2>
                </div>
                <a href="{{ route('vehicles.rent') }}" class="text-sm font-semibold text-electric-400 hover:text-electric-300 flex items-center gap-1.5 transition">
                    View Complete Rental Fleet
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredRentals as $vehicle)
                    <x-vehicle-card :vehicle="$vehicle" mode="rent" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- 5. Featured Vehicles For Sale -->
    <section class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-electric-400 block mb-2">Bespoke Acquisition</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-display tracking-tight">
                    Featured Vehicles For Sale
                </h2>
            </div>
            <a href="{{ route('vehicles.buy') }}" class="text-sm font-semibold text-electric-400 hover:text-electric-300 flex items-center gap-1.5 transition">
                View All Cars For Sale
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredSales as $vehicle)
                <x-vehicle-card :vehicle="$vehicle" mode="sale" />
            @endforeach
        </div>
    </section>

    <!-- 6. How It Works Section -->
    <section class="py-20 bg-carbon-900/60 border-y border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-electric-400 block mb-2">Frictionless Experience</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-display tracking-tight">
                    How AutoDrive Works
                </h2>
                <p class="text-slate-400 text-sm mt-3">
                    Whether reserving an exotic for the weekend or purchasing your dream supercar, our process is transparent, secure, and white-glove.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="glass-card rounded-2xl p-8 relative overflow-hidden group">
                    <span class="text-6xl font-black text-white/5 absolute -top-2 right-4 font-display">01</span>
                    <div class="w-12 h-12 rounded-xl bg-electric-500/20 text-electric-400 flex items-center justify-center font-bold text-lg mb-6 border border-electric-500/30">
                        1
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Select Your Vehicle</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Explore our verified fleet with high-resolution photography, authentic factory option specs, and live availability calendars.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="glass-card rounded-2xl p-8 relative overflow-hidden group">
                    <span class="text-6xl font-black text-white/5 absolute -top-2 right-4 font-display">02</span>
                    <div class="w-12 h-12 rounded-xl bg-electric-500/20 text-electric-400 flex items-center justify-center font-bold text-lg mb-6 border border-electric-500/30">
                        2
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Request & Fast Approval</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Submit dates for rental or submit your purchase offer. Our dedicated manager reviews credentials and confirms terms in real-time.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="glass-card rounded-2xl p-8 relative overflow-hidden group">
                    <span class="text-6xl font-black text-white/5 absolute -top-2 right-4 font-display">03</span>
                    <div class="w-12 h-12 rounded-xl bg-electric-500/20 text-electric-400 flex items-center justify-center font-bold text-lg mb-6 border border-electric-500/30">
                        3
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Drive Away or Delivery</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Enjoy digital inspection handovers at our Miami Flagship terminal or take delivery via our enclosed transport to your private address.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 7. Benefits / Why Choose Us -->
    <section class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <span class="text-xs font-bold uppercase tracking-widest text-electric-400 block">The AutoDrive Standard</span>
                <h2 class="text-3xl sm:text-5xl font-extrabold text-white font-display tracking-tight leading-tight">
                    Uncompromising Luxury.<br>Zero Red Tape.
                </h2>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Unlike standard commercial aggregators, AutoDrive owns and meticulously maintains every single vehicle listed. Each asset undergoes a 150-point diagnostic scan prior to key handoff.
                </p>

                <div class="space-y-4 pt-2">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-electric-500/20 text-electric-400 flex items-center justify-center shrink-0 border border-electric-500/30">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-white">Transparent, Fixed Pricing</h4>
                            <p class="text-xs text-slate-400 mt-0.5">Zero hidden fees, transparent insurance tiers, and instant printable receipts for corporate expense reconciliation.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-electric-500/20 text-electric-400 flex items-center justify-center shrink-0 border border-electric-500/30">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-white">150-Point Certified Inspection</h4>
                            <p class="text-xs text-slate-400 mt-0.5">Every return and pickup inspection is logged digitally with odometer, fuel, and diagnostic status recorded into the system.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-electric-500/20 text-electric-400 flex items-center justify-center shrink-0 border border-electric-500/30">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-white">Verified Acquisition Escrow</h4>
                            <p class="text-xs text-slate-400 mt-0.5">Guaranteed clear titles, clean Carfax records, and dedicated sales manager verification on every transaction.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visual Showcase -->
            <div class="relative">
                <div class="glass-card rounded-3xl overflow-hidden border border-white/15 p-2 shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1200&q=80"
                         alt="AutoDrive Cockpit Perfection"
                         class="w-full h-[450px] object-cover rounded-2xl">
                </div>
                <div class="absolute -bottom-6 -left-6 glass-card rounded-2xl p-5 border border-white/15 shadow-xl max-w-xs hidden sm:block">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-xl">
                            99%
                        </div>
                        <div>
                            <div class="text-sm font-bold text-white">5-Star Reviews</div>
                            <div class="text-xs text-slate-400">Over 1,200 successful journeys</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. Genuine Customer Testimonials -->
    <section class="py-20 bg-carbon-900/40 border-y border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-electric-400 block mb-2">Client Testimonials</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-display tracking-tight">
                    Praised by True Enthusiasts
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="glass-card rounded-2xl p-6 border border-white/10 flex flex-col justify-between">
                    <div>
                        <div class="flex text-amber-400 mb-4 gap-1 text-sm">
                            &#9733;&#9733;&#9733;&#9733;&#9733;
                        </div>
                        <p class="text-slate-300 text-sm italic leading-relaxed">
                            "Renting the Porsche 911 GT3 RS for Miami Grand Prix weekend was effortless. The vehicle was delivered to our private hangar in pristine condition with 2,000 miles on the clock. Will only use AutoDrive from now on."
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-white/10 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-800 border border-white/15 flex items-center justify-center font-bold text-white text-xs">
                            CR
                        </div>
                        <div>
                            <div class="text-sm font-bold text-white">Christopher Ross</div>
                            <div class="text-xs text-slate-400">Founder, Ross Capital Miami</div>
                        </div>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-6 border border-white/10 flex flex-col justify-between">
                    <div>
                        <div class="flex text-amber-400 mb-4 gap-1 text-sm">
                            &#9733;&#9733;&#9733;&#9733;&#9733;
                        </div>
                        <p class="text-slate-300 text-sm italic leading-relaxed">
                            "Acquired a certified Ferrari 296 GTB through Elena in the sales department. Wire clearance, registration transfer, and enclosed transport were finalized in 48 hours without a single glitch."
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-white/10 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-800 border border-white/15 flex items-center justify-center font-bold text-white text-xs">
                            SM
                        </div>
                        <div>
                            <div class="text-sm font-bold text-white">Sophia Montgomery</div>
                            <div class="text-xs text-slate-400">Tech Executive & Collector</div>
                        </div>
                    </div>
                </div>

                <div class="glass-card rounded-2xl p-6 border border-white/10 flex flex-col justify-between">
                    <div>
                        <div class="flex text-amber-400 mb-4 gap-1 text-sm">
                            &#9733;&#9733;&#9733;&#9733;&#9733;
                        </div>
                        <p class="text-slate-300 text-sm italic leading-relaxed">
                            "The Maybach S 680 was the perfect choice for our corporate summit. Our guests commented that the acoustic isolation and rear comfort suites were exceptional. Transparent billing was appreciated."
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-white/10 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-800 border border-white/15 flex items-center justify-center font-bold text-white text-xs">
                            LW
                        </div>
                        <div>
                            <div class="text-sm font-bold text-white">Lawrence Wright</div>
                            <div class="text-xs text-slate-400">Managing Partner, Wright & Partners</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 9. CTA Banner -->
    <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="glass-card rounded-3xl p-10 sm:p-16 border border-electric-500/30 text-center relative overflow-hidden bg-gradient-to-r from-carbon-900 via-carbon-850 to-carbon-900 shadow-2xl">
            <div class="relative z-10 max-w-2xl mx-auto space-y-6">
                <h2 class="text-3xl sm:text-5xl font-extrabold text-white font-display tracking-tight">
                    Ready to Command Extraordinary?
                </h2>
                <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                    Join AutoDrive today. Reserve your vehicle in under 3 minutes or speak with our sales advisors regarding luxury acquisitions.
                </p>
                <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
                    <a href="{{ route('vehicles.rent') }}" class="px-8 py-3.5 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-sm shadow-xl shadow-electric-500/30 transition">
                        Browse Rentals
                    </a>
                    <a href="{{ route('contact') }}" class="px-8 py-3.5 rounded-xl bg-white/10 hover:bg-white/15 text-white font-semibold text-sm backdrop-blur-md border border-white/20 transition">
                        Contact Concierge
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    function switchSearchTab(tab) {
        const formRent = document.getElementById('form-rent');
        const formBuy = document.getElementById('form-buy');
        const tabRent = document.getElementById('tab-rent');
        const tabBuy = document.getElementById('tab-buy');

        if (tab === 'rent') {
            formRent.classList.remove('hidden');
            formRent.classList.add('grid');
            formBuy.classList.add('hidden');
            formBuy.classList.remove('grid');

            tabRent.classList.add('border-electric-400', 'text-white');
            tabRent.classList.remove('border-transparent', 'text-slate-400');
            tabBuy.classList.remove('border-electric-400', 'text-white');
            tabBuy.classList.add('border-transparent', 'text-slate-400');
        } else {
            formBuy.classList.remove('hidden');
            formBuy.classList.add('grid');
            formRent.classList.add('hidden');
            formRent.classList.remove('grid');

            tabBuy.classList.add('border-electric-400', 'text-white');
            tabBuy.classList.remove('border-transparent', 'text-slate-400');
            tabRent.classList.remove('border-electric-400', 'text-white');
            tabRent.classList.add('border-transparent', 'text-slate-400');
        }
    }
</script>
@endsection
