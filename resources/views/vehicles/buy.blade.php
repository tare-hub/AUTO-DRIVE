@extends('layouts.app')

@section('title', 'Buy a Car — AutoDrive Certified Supercar & Luxury Acquisitions')
@section('meta_description', 'Acquire your dream automobile. Certified pre-owned exotic sports cars, luxury flagships, and electric hypercars with guaranteed provenance.')

@section('content')
<div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
    <!-- Header Banner -->
    <div class="glass-card rounded-3xl p-8 border border-white/10 relative overflow-hidden">
        <div class="max-w-2xl space-y-2">
            <span class="text-xs font-bold uppercase tracking-widest text-electric-400 block">Automotive Acquisitions</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white font-display tracking-tight">
                Acquire Verified Collector & Luxury Assets
            </h1>
            <p class="text-slate-300 text-sm leading-relaxed">
                Clear title guarantee, full factory option records, verified mileage, and dedicated sales manager escrow for every acquisition.
            </p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Filter Form -->
        <aside class="lg:col-span-1">
            <form method="GET" action="{{ route('vehicles.buy') }}" class="glass-card rounded-2xl p-6 border border-white/10 space-y-6 sticky top-28">
                <div class="flex items-center justify-between pb-4 border-b border-white/10">
                    <h2 class="font-bold text-white text-base flex items-center gap-2">
                        <svg class="w-4 h-4 text-electric-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter Sales
                    </h2>
                    <a href="{{ route('vehicles.buy') }}" class="text-xs text-slate-400 hover:text-white transition">Reset</a>
                </div>

                <!-- Search -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Search Model</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="e.g. Porsche, Ferrari..."
                           class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-electric-400">
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Category</label>
                    <select name="category_id" class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-electric-400">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Brand -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Brand</label>
                    <select name="brand" class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-electric-400">
                        <option value="">All Brands</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Condition -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Condition</label>
                    <select name="condition" class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-electric-400">
                        <option value="">All Conditions</option>
                        <option value="new" {{ request('condition') === 'new' ? 'selected' : '' }}>Brand New</option>
                        <option value="certified_preowned" {{ request('condition') === 'certified_preowned' ? 'selected' : '' }}>Certified Pre-Owned</option>
                        <option value="used" {{ request('condition') === 'used' ? 'selected' : '' }}>Pre-Owned</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Inventory Status</label>
                    <select name="status" class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-electric-400">
                        <option value="">All Statuses</option>
                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available for Purchase</option>
                        <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>Under Reservation Hold</option>
                        <option value="sold" {{ request('status') === 'sold' ? 'selected' : '' }}>Sold Archive</option>
                    </select>
                </div>

                <!-- Max Price -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Max Price</label>
                    <select name="max_price" class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-electric-400">
                        <option value="">No Limit</option>
                        <option value="150000" {{ request('max_price') == '150000' ? 'selected' : '' }}>Up to $150,000</option>
                        <option value="250000" {{ request('max_price') == '250000' ? 'selected' : '' }}>Up to $250,000</option>
                        <option value="350000" {{ request('max_price') == '350000' ? 'selected' : '' }}>Up to $350,000</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-sm shadow-lg shadow-electric-500/25 transition">
                    Filter Vehicles
                </button>
            </form>
        </aside>

        <!-- Listings Content Area -->
        <main class="lg:col-span-3 space-y-6">
            <!-- Sort and Count Bar -->
            <div class="glass-card rounded-2xl p-4 border border-white/10 flex flex-wrap items-center justify-between gap-4">
                <div class="text-sm text-slate-300">
                    Showing <strong class="text-white">{{ $vehicles->total() }}</strong> sales listing(s)
                </div>

                <div class="flex items-center gap-3">
                    <label class="text-xs text-slate-400 font-semibold uppercase">Sort By:</label>
                    <form method="GET" action="{{ route('vehicles.buy') }}" id="sort-buy-form">
                        @foreach(request()->except('sort', 'page') as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <select name="sort" onchange="document.getElementById('sort-buy-form').submit()"
                                class="bg-carbon-900 border border-white/15 rounded-xl px-3 py-1.5 text-xs text-white focus:outline-none focus:border-electric-400">
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest Inventory</option>
                            <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="mileage_low" {{ request('sort') === 'mileage_low' ? 'selected' : '' }}>Lowest Mileage</option>
                            <option value="brand" {{ request('sort') === 'brand' ? 'selected' : '' }}>Brand (A to Z)</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Vehicles Grid -->
            @if($vehicles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($vehicles as $vehicle)
                        <x-vehicle-card :vehicle="$vehicle" mode="sale" />
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pt-4">
                    {{ $vehicles->links() }}
                </div>
            @else
                <!-- Clear Empty State -->
                <div class="glass-card rounded-3xl p-12 text-center border border-white/10 space-y-4">
                    <div class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mx-auto text-slate-400">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">No Vehicles Match Your Search</h3>
                    <p class="text-sm text-slate-400 max-w-md mx-auto">
                        No purchase listings match your active filters. Try adjusting budget or condition criteria.
                    </p>
                    <a href="{{ route('vehicles.buy') }}" class="inline-block px-5 py-2.5 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-xs transition">
                        Reset Filters
                    </a>
                </div>
            @endif
        </main>
    </div>
</div>
@endsection
