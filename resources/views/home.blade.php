@extends('layouts.app')

@section('title', 'AutoDrive — Premium Cars For Sale & Rent')
@section('meta_description', 'Discover premium cars for sale or rent. Find your perfect ride, your way.')

@section('content')
    <!-- 1. Cinematic Hero Section -->
    <section class="relative min-h-[600px] lg:min-h-[680px] flex items-center overflow-hidden border-b border-white/5 bg-[#0a0b0d] -mt-20 pt-20">
        <!-- Hero Background Image (Black AMG GT on Dusk Sunset Mountain Road) -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/hero-amg-sunset.jpg') }}"
                 alt="Mercedes-AMG GT Coupe Sunset Mountain Drive"
                 class="w-full h-full object-cover object-[center_35%] lg:object-[center_28%] scale-100">
            <!-- Left Gradient to ensure text readability -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#0a0b0d] via-[#0a0b0d]/75 lg:via-[#0a0b0d]/50 to-transparent"></div>
            <!-- Bottom Vignette into Search Bar -->
            <div class="absolute inset-0 bg-gradient-to-t from-[#0a0b0d] via-transparent to-[#0a0b0d]/30"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 w-full flex flex-col lg:flex-row lg:items-center justify-between gap-12">
            <!-- Hero Left Typography & Calls to Action -->
            <div class="max-w-xl space-y-5">
                <!-- Tagline Badge -->
                <p class="text-[#e5a952] text-xs font-bold tracking-[0.22em] uppercase">
                    PREMIUM CARS FOR SALE & RENT
                </p>

                <!-- Headline -->
                <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-white leading-[1.08] font-display">
                    Your Next Drive<br>
                    <span class="text-[#e5a952]">Starts Here.</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-slate-300 text-sm sm:text-base font-normal leading-relaxed max-w-md">
                    Discover premium cars for sale or rent. Find your perfect ride, your way.
                </p>

                <!-- Dual Quick Action Buttons -->
                <div class="flex flex-wrap items-center gap-3.5 pt-2">
                    <a href="{{ route('vehicles.buy') }}"
                       class="px-6 py-3 rounded-lg bg-[#e5a952] hover:bg-[#d89b45] text-slate-950 font-bold text-sm flex items-center gap-2 shadow-lg shadow-[#e5a952]/20 transition transform hover:-translate-y-0.5">
                        <span>Explore Cars</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('vehicles.rent') }}"
                       class="px-6 py-3 rounded-lg border border-white/20 bg-black/40 hover:bg-white/10 text-white font-semibold text-sm backdrop-blur-sm transition">
                        Rent a Car
                    </a>
                </div>
            </div>

            <!-- Hero Right: 3 Floating Badges (Matching Mockup) -->
            <div class="hidden lg:flex flex-col gap-3 self-center lg:self-end mb-4">
                <!-- Badge 1: Premium Vehicles -->
                <div class="flex items-center gap-3 px-4 py-2.5 rounded-xl bg-[#12141a]/85 backdrop-blur-md border border-white/10 shadow-xl">
                    <div class="w-8 h-8 rounded-lg bg-[#e5a952]/10 border border-[#e5a952]/30 flex items-center justify-center text-[#e5a952] shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white">Premium Vehicles</div>
                        <div class="text-[10.5px] text-slate-400">Top brands, best quality</div>
                    </div>
                </div>

                <!-- Badge 2: Secure Booking -->
                <div class="flex items-center gap-3 px-4 py-2.5 rounded-xl bg-[#12141a]/85 backdrop-blur-md border border-white/10 shadow-xl">
                    <div class="w-8 h-8 rounded-lg bg-[#e5a952]/10 border border-[#e5a952]/30 flex items-center justify-center text-[#e5a952] shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white">Secure Booking</div>
                        <div class="text-[10.5px] text-slate-400">Fast & reliable process</div>
                    </div>
                </div>

                <!-- Badge 3: 24/7 Support -->
                <div class="flex items-center gap-3 px-4 py-2.5 rounded-xl bg-[#12141a]/85 backdrop-blur-md border border-white/10 shadow-xl">
                    <div class="w-8 h-8 rounded-lg bg-[#e5a952]/10 border border-[#e5a952]/30 flex items-center justify-center text-[#e5a952] shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white">24/7 Support</div>
                        <div class="text-[10.5px] text-slate-400">We're always here</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Floating Search & Filter Bar (Matching Mockup) -->
    <section id="search-filter-section" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 sm:-mt-10 relative z-30">
        <div class="bg-[#12141a]/95 backdrop-blur-xl border border-white/10 rounded-2xl p-4 sm:p-5 shadow-2xl">
            <form id="hero-search-form" method="GET" action="{{ route('vehicles.buy') }}" class="flex flex-col lg:flex-row lg:items-center gap-4">
                <!-- Left Mode Switcher: Buy vs Rent -->
                <div class="flex items-center p-1 rounded-xl bg-black/50 border border-white/10 shrink-0">
                    <button type="button" id="toggle-buy" onclick="setSearchMode('buy')"
                            class="px-4 py-2 rounded-lg text-xs font-bold flex items-center gap-2 bg-[#1b1e27] text-white border border-white/10 transition shadow-sm">
                        <svg class="w-3.5 h-3.5 text-[#e5a952]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span>Buy</span>
                    </button>
                    <button type="button" id="toggle-rent" onclick="setSearchMode('rent')"
                            class="px-4 py-2 rounded-lg text-xs font-medium flex items-center gap-2 text-slate-400 hover:text-white transition">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        <span>Rent</span>
                    </button>
                </div>

                <!-- Form Controls Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3 flex-grow items-center">
                    <!-- Brand Select -->
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 mb-1">Brand</label>
                        <select name="brand" class="w-full bg-[#1b1e27] border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-[#e5a952] transition">
                            <option value="">All Brands</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand }}">{{ $brand }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Model Select -->
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 mb-1">Model</label>
                        <select name="model" class="w-full bg-[#1b1e27] border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-[#e5a952] transition">
                            <option value="">Any Model</option>
                            @foreach($models as $model)
                                <option value="{{ $model }}">{{ $model }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Range Slider -->
                    <div>
                        <div class="flex items-center justify-between text-[10px] mb-1">
                            <span class="font-semibold text-slate-400">Price Range</span>
                            <span id="price-label" class="font-bold text-[#e5a952]">$0 - $200,000</span>
                        </div>
                        <input type="range" id="price-slider" name="max_price" min="50" max="250000" step="1000" value="200000"
                               class="w-full h-1.5 bg-[#1b1e27] rounded-lg appearance-none cursor-pointer accent-[#e5a952]"
                               oninput="updatePriceLabel(this.value)">
                    </div>

                    <!-- Transmission Select -->
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 mb-1">Transmission</label>
                        <select name="transmission" class="w-full bg-[#1b1e27] border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-[#e5a952] transition">
                            <option value="">Any</option>
                            <option value="Automatic">Automatic</option>
                            <option value="Manual">Manual</option>
                        </select>
                    </div>

                    <!-- Fuel Type Select -->
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 mb-1">Fuel Type</label>
                        <select name="fuel_type" class="w-full bg-[#1b1e27] border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:outline-none focus:border-[#e5a952] transition">
                            <option value="">Any</option>
                            <option value="Petrol">Petrol</option>
                            <option value="Electric">Electric</option>
                            <option value="Hybrid">Hybrid</option>
                            <option value="Diesel">Diesel</option>
                        </select>
                    </div>
                </div>

                <!-- Search Cars Submit Button -->
                <button type="submit"
                        class="px-5 py-2.5 rounded-lg bg-[#e5a952] hover:bg-[#d89b45] text-slate-950 font-bold text-xs flex items-center justify-center gap-2 shrink-0 shadow-lg shadow-[#e5a952]/20 transition mt-2 lg:mt-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>Search Cars</span>
                </button>
            </form>
        </div>
    </section>

    <!-- 3. Featured Vehicles Section (Matching Mockup with 5 Cards) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12">
        <!-- Header & Filter Tabs -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-white tracking-tight font-display">
                    Featured <span class="text-[#e5a952]">Vehicles</span>
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Handpicked selection of the best cars, ready for your next adventure.
                </p>
            </div>

            <div class="flex items-center gap-4">
                <!-- Filter Tabs: All, For Sale, For Rent -->
                <div class="flex items-center gap-1.5 p-1 rounded-xl bg-[#12141a] border border-white/10 text-xs">
                    <button type="button" onclick="filterCards('all')" id="tab-card-all"
                            class="px-3 py-1.5 rounded-lg text-slate-400 hover:text-white transition font-medium">
                        All
                    </button>
                    <button type="button" onclick="filterCards('sale')" id="tab-card-sale"
                            class="px-3 py-1.5 rounded-lg bg-[#1b1e27] text-[#e5a952] border border-[#e5a952]/30 font-bold transition">
                        For Sale
                    </button>
                    <button type="button" onclick="filterCards('rental')" id="tab-card-rental"
                            class="px-3 py-1.5 rounded-lg text-slate-400 hover:text-white transition font-medium">
                        For Rent
                    </button>
                </div>

                <!-- Carousel Arrows -->
                <div class="hidden sm:flex items-center gap-1.5">
                    <button type="button" onclick="scrollVehicles('left')"
                            class="w-8 h-8 rounded-lg bg-[#12141a] border border-white/10 hover:border-white/20 text-slate-300 hover:text-white flex items-center justify-center transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button type="button" onclick="scrollVehicles('right')"
                            class="w-8 h-8 rounded-lg bg-[#12141a] border border-white/10 hover:border-white/20 text-slate-300 hover:text-white flex items-center justify-center transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- 5 Vehicle Cards Grid matching the mockup exactly -->
        <div id="featured-vehicles-scroll" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 overflow-x-auto scrollbar-none pb-2">
            @foreach($featuredVehicles as $vehicle)
                @php
                    $isRental = ($vehicle->listing_type === 'rental');
                    // Pick the image from local mockup crop or fallback
                    $img = match(true) {
                        str_contains(strtolower($vehicle->model), 'amg gt') => asset('images/mockup/car_amg_gt.jpg'),
                        str_contains(strtolower($vehicle->model), '5 series') => asset('images/mockup/car_bmw5.jpg'),
                        str_contains(strtolower($vehicle->model), 'carrera') => asset('images/mockup/car_porsche.jpg'),
                        str_contains(strtolower($vehicle->model), 'sport') => asset('images/mockup/car_rangerover.jpg'),
                        str_contains(strtolower($vehicle->model), 'land cruiser') => asset('images/mockup/car_landcruiser.jpg'),
                        default => $vehicle->main_image
                    };
                @endphp
                <div class="vehicle-card group bg-[#16181f] border border-white/10 hover:border-white/25 rounded-2xl p-3 flex flex-col justify-between transition-all duration-300 hover:-translate-y-1 shadow-lg"
                     data-type="{{ $vehicle->listing_type }}">
                    <div>
                        <!-- Image Container with Badges -->
                        <div class="relative w-full aspect-[16/10] rounded-xl overflow-hidden bg-black mb-3">
                            <img src="{{ $img }}"
                                 alt="{{ $vehicle->brand }} {{ $vehicle->model }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            
                            <!-- Status Badge Top Left -->
                            <div class="absolute top-2.5 left-2.5">
                                @if($isRental)
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold tracking-wide uppercase bg-sky-500/20 text-sky-300 border border-sky-500/40 backdrop-blur-md">
                                        For Rent
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold tracking-wide uppercase bg-[#e5a952]/20 text-[#e5a952] border border-[#e5a952]/40 backdrop-blur-md">
                                        For Sale
                                    </span>
                                @endif
                            </div>

                            <!-- Heart Wishlist Top Right -->
                            <button type="button" class="absolute top-2.5 right-2.5 w-6 h-6 rounded-full bg-black/60 hover:bg-black/80 backdrop-blur-md border border-white/10 flex items-center justify-center text-slate-300 hover:text-[#e5a952] transition">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Car Title -->
                        <h3 class="text-xs font-bold text-white truncate group-hover:text-[#e5a952] transition">
                            {{ $vehicle->brand }} {{ $vehicle->model }}
                        </h3>

                        <!-- Specs Row (Year, Mileage, Transmission) -->
                        <div class="flex items-center gap-2 text-[10px] text-slate-400 mt-1.5 mb-3">
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $vehicle->year }}
                            </span>
                            <span>&bull;</span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                {{ number_format($vehicle->mileage) }} km
                            </span>
                            <span>&bull;</span>
                            <span class="truncate">{{ $vehicle->transmission ?? 'Automatic' }}</span>
                        </div>
                    </div>

                    <!-- Bottom Price & View Details Link -->
                    <div class="pt-2 border-t border-white/5 flex items-center justify-between">
                        <div>
                            @if($isRental)
                                <div class="text-[#e5a952] font-bold text-sm">
                                    ${{ number_format($vehicle->daily_rate) }} <span class="text-[10px] text-slate-400 font-normal">/ day</span>
                                </div>
                            @else
                                <div class="text-[#e5a952] font-bold text-sm">
                                    ${{ number_format($vehicle->selling_price) }}
                                </div>
                            @endif
                        </div>

                        <a href="{{ route('vehicles.show', $vehicle->id) }}"
                           class="text-[10px] font-semibold text-slate-300 hover:text-[#e5a952] flex items-center gap-0.5 transition">
                            <span>View Details</span>
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- 4. Dual Side-by-Side Promotion Banners (Matching Mockup) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Banner: BUY A CAR (Own the Journey) -->
            <div class="relative rounded-2xl overflow-hidden border border-white/10 p-8 sm:p-10 flex flex-col justify-end min-h-[220px] shadow-xl group">
                <div class="absolute inset-0 z-0">
                    <img src="{{ asset('images/promo-buy-amg.jpg') }}"
                         alt="AMG GT Sports Car"
                         class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/75 to-transparent"></div>
                </div>

                <div class="relative z-10 max-w-sm space-y-2">
                    <span class="text-[#e5a952] text-[10px] font-bold uppercase tracking-widest block">BUY A CAR</span>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white font-display">Own the Journey.</h3>
                    <p class="text-slate-300 text-xs leading-relaxed">
                        Find your dream car with transparent pricing, verified listings and easy financing options.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('vehicles.buy') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-[#e5a952] hover:bg-[#d89b45] text-slate-950 font-bold text-xs shadow-md transition">
                            <span>Browse for Sale</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Banner: RENT A CAR (Drive Without Limits) -->
            <div class="relative rounded-2xl overflow-hidden border border-white/10 p-8 sm:p-10 flex flex-col justify-end min-h-[220px] shadow-xl group">
                <div class="absolute inset-0 z-0">
                    <img src="{{ asset('images/promo-rent-suv.jpg') }}"
                         alt="Luxury SUV Sunset Mountain"
                         class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/75 to-transparent"></div>
                </div>

                <div class="relative z-10 max-w-sm space-y-2">
                    <span class="text-[#e5a952] text-[10px] font-bold uppercase tracking-widest block">RENT A CAR</span>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white font-display">Drive Without Limits.</h3>
                    <p class="text-slate-300 text-xs leading-relaxed">
                        Perfect for business, travel or special occasions. Flexible rental plans to fit your lifestyle.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('vehicles.rent') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-white/20 bg-black/40 hover:bg-white/10 text-white font-semibold text-xs backdrop-blur-sm transition">
                            <span>View Rental Cars</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Value Props 5-Columns Strip (Matching Mockup) -->
    <section id="services" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 scroll-mt-24">
        <div class="bg-[#12141a] border border-white/10 rounded-2xl p-5 sm:p-6 shadow-xl">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6 text-left">
                <!-- 1. Verified Listings -->
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#e5a952]/10 border border-[#e5a952]/20 flex items-center justify-center text-[#e5a952] shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white">Verified Listings</div>
                        <div class="text-[10px] text-slate-400 mt-0.5">Every car is checked and verified</div>
                    </div>
                </div>

                <!-- 2. Transparent Pricing -->
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#e5a952]/10 border border-[#e5a952]/20 flex items-center justify-center text-[#e5a952] shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white">Transparent Pricing</div>
                        <div class="text-[10px] text-slate-400 mt-0.5">No hidden fees</div>
                    </div>
                </div>

                <!-- 3. Customer Reviews -->
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#e5a952]/10 border border-[#e5a952]/20 flex items-center justify-center text-[#e5a952] shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white">Customer Reviews</div>
                        <div class="text-[10px] text-slate-400 mt-0.5">Real feedback, real people</div>
                    </div>
                </div>

                <!-- 4. Easy Booking -->
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#e5a952]/10 border border-[#e5a952]/20 flex items-center justify-center text-[#e5a952] shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white">Easy Booking</div>
                        <div class="text-[10px] text-slate-400 mt-0.5">Simple, fast and secure</div>
                    </div>
                </div>

                <!-- 5. Multiple Payment Options -->
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#e5a952]/10 border border-[#e5a952]/20 flex items-center justify-center text-[#e5a952] shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white">Multiple Payment Options</div>
                        <div class="text-[10px] text-slate-400 mt-0.5">Cards, bank transfer, mobile money</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. "What Our Customers Say" Section (Matching Mockup) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16">
        <!-- Header & Arrows -->
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-white tracking-tight font-display">
                    What Our Customers Say
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Trusted by thousands of happy drivers.
                </p>
            </div>

            <!-- Arrows -->
            <div class="flex items-center gap-2">
                <button type="button" class="w-8 h-8 rounded-lg bg-[#12141a] border border-white/10 hover:border-white/20 text-slate-400 hover:text-white flex items-center justify-center transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button type="button" class="w-8 h-8 rounded-lg bg-[#12141a] border border-white/10 hover:border-white/20 text-slate-400 hover:text-white flex items-center justify-center transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- 3 Testimonial Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Review 1: Fitsum Alemu -->
            <div class="bg-[#12141a] border border-white/10 rounded-2xl p-5 flex flex-col justify-between space-y-4 shadow-lg hover:border-white/20 transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#e5a952] to-[#996515] flex items-center justify-center font-bold text-slate-950 text-xs shrink-0 shadow-md">
                        FA
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white">Fitsum Alemu</div>
                        <div class="flex items-center text-[#e5a952] text-xs mt-0.5">
                            ★★★★★
                        </div>
                    </div>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed italic">
                    "Amazing experience! The car was in perfect condition and the booking process was super easy. Highly recommend AUTODRIVE!"
                </p>
            </div>

            <!-- Review 2: Selamawit Tesfaye -->
            <div class="bg-[#12141a] border border-white/10 rounded-2xl p-5 flex flex-col justify-between space-y-4 shadow-lg hover:border-white/20 transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center font-bold text-white text-xs shrink-0 shadow-md">
                        ST
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white">Selamawit Tesfaye</div>
                        <div class="flex items-center text-[#e5a952] text-xs mt-0.5">
                            ★★★★★
                        </div>
                    </div>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed italic">
                    "Rented a BMW for my vacation. Everything was smooth and professional. Will definitely use again!"
                </p>
            </div>

            <!-- Review 3: Daniel Kassa -->
            <div class="bg-[#12141a] border border-white/10 rounded-2xl p-5 flex flex-col justify-between space-y-4 shadow-lg hover:border-white/20 transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-700 flex items-center justify-center font-bold text-white text-xs shrink-0 shadow-md">
                        DK
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white">Daniel Kassa</div>
                        <div class="flex items-center text-[#e5a952] text-xs mt-0.5">
                            ★★★★★
                        </div>
                    </div>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed italic">
                    "I sold my car through AUTODRIVE and got a better price than expected. Great service!"
                </p>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // Search Mode Switcher (Buy vs Rent)
    function setSearchMode(mode) {
        const form = document.getElementById('hero-search-form');
        const btnBuy = document.getElementById('toggle-buy');
        const btnRent = document.getElementById('toggle-rent');
        const priceLabel = document.getElementById('price-label');
        const priceSlider = document.getElementById('price-slider');

        if (mode === 'buy') {
            form.action = "{{ route('vehicles.buy') }}";
            btnBuy.className = "px-4 py-2 rounded-lg text-xs font-bold flex items-center gap-2 bg-[#1b1e27] text-white border border-white/10 transition shadow-sm";
            btnRent.className = "px-4 py-2 rounded-lg text-xs font-medium flex items-center gap-2 text-slate-400 hover:text-white transition";
            priceSlider.max = 250000;
            priceSlider.value = 200000;
            updatePriceLabel(200000);
        } else {
            form.action = "{{ route('vehicles.rent') }}";
            btnRent.className = "px-4 py-2 rounded-lg text-xs font-bold flex items-center gap-2 bg-[#1b1e27] text-white border border-white/10 transition shadow-sm";
            btnBuy.className = "px-4 py-2 rounded-lg text-xs font-medium flex items-center gap-2 text-slate-400 hover:text-white transition";
            priceSlider.max = 2500;
            priceSlider.value = 500;
            updatePriceLabel(500, true);
        }
    }

    function updatePriceLabel(val, isRental = false) {
        const label = document.getElementById('price-label');
        if (isRental) {
            label.innerText = '$0 - $' + Number(val).toLocaleString() + ' / day';
        } else {
            label.innerText = '$0 - $' + Number(val).toLocaleString();
        }
    }

    // Filter Cards in Featured Vehicles
    function filterCards(type) {
        const cards = document.querySelectorAll('.vehicle-card');
        const btnAll = document.getElementById('tab-card-all');
        const btnSale = document.getElementById('tab-card-sale');
        const btnRent = document.getElementById('tab-card-rental');

        const activeStyle = "px-3 py-1.5 rounded-lg bg-[#1b1e27] text-[#e5a952] border border-[#e5a952]/30 font-bold transition";
        const inactiveStyle = "px-3 py-1.5 rounded-lg text-slate-400 hover:text-white transition font-medium";

        btnAll.className = (type === 'all') ? activeStyle : inactiveStyle;
        btnSale.className = (type === 'sale') ? activeStyle : inactiveStyle;
        btnRent.className = (type === 'rental') ? activeStyle : inactiveStyle;

        cards.forEach(card => {
            const cardType = card.getAttribute('data-type');
            if (type === 'all') {
                card.style.display = 'flex';
            } else if (type === 'sale' && (cardType === 'sale' || cardType === 'both')) {
                card.style.display = 'flex';
            } else if (type === 'rental' && (cardType === 'rental' || cardType === 'both')) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Scroll carousel
    function scrollVehicles(dir) {
        const container = document.getElementById('featured-vehicles-scroll');
        const scrollAmount = 300;
        if (dir === 'left') {
            container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        } else {
            container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        }
    }
</script>
@endsection
