@props(['vehicle', 'mode' => 'all'])

@php
    $statusColor = match($vehicle->status) {
        'available' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
        'reserved' => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
        'rented' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
        'sold' => 'bg-rose-500/20 text-rose-300 border-rose-500/30',
        default => 'bg-slate-700 text-slate-300 border-slate-600',
    };
@endphp

<div class="glass-card glass-card-hover rounded-2xl overflow-hidden flex flex-col group border border-white/10">
    <!-- Vehicle Image Container with Zoom & Badge -->
    <div class="relative h-56 sm:h-64 overflow-hidden bg-carbon-900">
        <img src="{{ $vehicle->main_image }}"
             alt="{{ $vehicle->full_name }}"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
             loading="lazy">
        <div class="absolute inset-0 bg-gradient-to-t from-carbon-950 via-carbon-950/20 to-transparent"></div>

        <!-- Top Badges -->
        <div class="absolute top-3 left-3 flex flex-wrap gap-1.5 z-10">
            <span class="px-2.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wider backdrop-blur-md border {{ $statusColor }}">
                {{ ucfirst($vehicle->status) }}
            </span>
            @if($vehicle->is_featured)
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-electric-500/20 text-electric-300 border border-electric-500/40 backdrop-blur-md">
                    Featured
                </span>
            @endif
        </div>

        <div class="absolute top-3 right-3 z-10">
            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-carbon-900/80 text-slate-300 backdrop-blur-md border border-white/10">
                {{ $vehicle->category?->name }}
            </span>
        </div>

        <!-- Condition & Year Tag (Bottom Left of Image) -->
        <div class="absolute bottom-3 left-3 z-10">
            <span class="text-xs uppercase tracking-wider font-semibold text-slate-300 bg-carbon-900/90 px-2.5 py-1 rounded-md border border-white/10">
                {{ str_replace('_', ' ', $vehicle->condition) }} &bull; {{ $vehicle->year }}
            </span>
        </div>
    </div>

    <!-- Vehicle Content -->
    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
        <div>
            <div class="flex items-baseline justify-between gap-2">
                <span class="text-xs font-semibold uppercase tracking-wider text-electric-400">{{ $vehicle->brand }}</span>
                <span class="text-xs text-slate-400 font-mono">{{ number_format($vehicle->mileage) }} mi</span>
            </div>
            <h3 class="text-lg font-bold text-white group-hover:text-electric-300 transition mt-0.5 line-clamp-1">
                {{ $vehicle->model }}
            </h3>
            <p class="text-xs text-slate-400 mt-1.5 line-clamp-2">
                {{ $vehicle->description }}
            </p>
        </div>

        <!-- Specification Pills -->
        <div class="grid grid-cols-3 gap-2 py-3 border-y border-white/10 text-[11px] text-slate-300">
            <div class="flex items-center gap-1.5 bg-white/5 rounded-lg p-2 justify-center">
                <svg class="w-3.5 h-3.5 text-electric-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                <span class="truncate">{{ $vehicle->transmission }}</span>
            </div>
            <div class="flex items-center gap-1.5 bg-white/5 rounded-lg p-2 justify-center">
                <svg class="w-3.5 h-3.5 text-electric-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <span class="truncate">{{ $vehicle->fuel_type }}</span>
            </div>
            <div class="flex items-center gap-1.5 bg-white/5 rounded-lg p-2 justify-center">
                <svg class="w-3.5 h-3.5 text-electric-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>{{ $vehicle->seating_capacity }} Seats</span>
            </div>
        </div>

        <!-- Pricing & Call to Action -->
        <div class="flex items-center justify-between gap-3 pt-1">
            <div>
                @if($mode === 'rent' || ($vehicle->listing_type === 'rental' && $mode !== 'sale'))
                    <span class="text-[10px] uppercase tracking-wider text-slate-400 block font-semibold">Rental Rate</span>
                    <div class="text-xl font-extrabold text-white">
                        ${{ number_format($vehicle->daily_rate, 0) }}
                        <span class="text-xs font-normal text-slate-400">/day</span>
                    </div>
                @elseif($mode === 'sale' || $vehicle->listing_type === 'sale')
                    <span class="text-[10px] uppercase tracking-wider text-slate-400 block font-semibold">Acquisition Price</span>
                    <div class="text-xl font-extrabold text-electric-400">
                        ${{ number_format($vehicle->selling_price, 0) }}
                    </div>
                @else
                    <span class="text-[10px] uppercase tracking-wider text-slate-400 block font-semibold">Daily Rate / Sale</span>
                    <div class="text-base font-bold text-white">
                        ${{ number_format($vehicle->daily_rate, 0) }}<span class="text-xs font-normal text-slate-400">/d</span>
                        @if($vehicle->selling_price)
                            <span class="text-xs text-electric-400 ml-1 font-semibold">${{ number_format($vehicle->selling_price / 1000, 0) }}k</span>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('vehicles.show', $vehicle) }}"
                   class="px-3.5 py-2 rounded-xl bg-carbon-800 hover:bg-carbon-700 text-white text-xs font-semibold border border-white/10 transition">
                    Details
                </a>
                @if($vehicle->status === 'available')
                    @if($mode === 'sale' || $vehicle->listing_type === 'sale')
                        <a href="{{ route('vehicles.show', $vehicle) }}#purchase-section"
                           class="px-3.5 py-2 rounded-xl bg-electric-500 hover:bg-electric-400 text-white text-xs font-bold shadow-md shadow-electric-500/20 transition">
                            Buy Now
                        </a>
                    @else
                        <a href="{{ route('vehicles.show', $vehicle) }}#booking-section"
                           class="px-3.5 py-2 rounded-xl bg-electric-500 hover:bg-electric-400 text-white text-xs font-bold shadow-md shadow-electric-500/20 transition">
                            Rent Now
                        </a>
                    @endif
                @else
                    <span class="px-3 py-2 rounded-xl bg-white/5 text-slate-500 text-xs font-medium cursor-not-allowed">
                        {{ ucfirst($vehicle->status) }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
