@extends('layouts.app')

@section('title', "{$vehicle->full_name} — AutoDrive Flagship")
@section('meta_description', "Experience the {$vehicle->full_name}. Specifications: {$vehicle->engine_capacity}, {$vehicle->transmission}, {$vehicle->fuel_type}. Reserve for rental or acquire through AutoDrive.")

@section('content')
<div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-400">
        <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
        <span>/</span>
        @if($vehicle->listing_type === 'sale')
            <a href="{{ route('vehicles.buy') }}" class="hover:text-white transition">Sales Marketplace</a>
        @else
            <a href="{{ route('vehicles.rent') }}" class="hover:text-white transition">Rental Marketplace</a>
        @endif
        <span>/</span>
        <span class="text-white font-medium">{{ $vehicle->brand }} {{ $vehicle->model }}</span>
    </nav>

    <!-- Header & Price Highlights -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 pb-6 border-b border-white/10">
        <div>
            <div class="flex flex-wrap items-center gap-2 mb-2">
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-electric-500/20 text-electric-300 border border-electric-500/40">
                    {{ $vehicle->category?->name }}
                </span>
                <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-white/5 border border-white/10 text-slate-300">
                    {{ str_replace('_', ' ', $vehicle->condition) }}
                </span>
                @php
                    $statusColor = match($vehicle->status) {
                        'available' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                        'reserved' => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
                        'rented' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                        'sold' => 'bg-rose-500/20 text-rose-300 border-rose-500/30',
                        default => 'bg-slate-700 text-slate-300 border-slate-600',
                    };
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $statusColor }}">
                    {{ ucfirst($vehicle->status) }}
                </span>
            </div>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-white font-display tracking-tight">
                {{ $vehicle->year }} {{ $vehicle->brand }} <span class="text-electric-400">{{ $vehicle->model }}</span>
            </h1>
        </div>

        <div class="flex items-center gap-6">
            @if(in_array($vehicle->listing_type, ['rental', 'both']))
                <div class="text-right">
                    <span class="text-xs uppercase tracking-wider text-slate-400 block font-semibold">Daily Rental Rate</span>
                    <div class="text-3xl font-extrabold text-white">
                        ${{ number_format($vehicle->daily_rate, 2) }}
                        <span class="text-sm font-normal text-slate-400">/day</span>
                    </div>
                </div>
            @endif

            @if(in_array($vehicle->listing_type, ['sale', 'both']) && $vehicle->selling_price)
                <div class="text-right pl-6 border-l border-white/10">
                    <span class="text-xs uppercase tracking-wider text-slate-400 block font-semibold">Acquisition Price</span>
                    <div class="text-3xl font-extrabold text-electric-400">
                        ${{ number_format($vehicle->selling_price, 2) }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Gallery & Specifications Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Gallery (Left 2 cols) -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Main Featured Large Image -->
            <div class="glass-card rounded-3xl overflow-hidden border border-white/10 relative h-96 sm:h-[500px]">
                <img id="main-gallery-image"
                     src="{{ $vehicle->main_image }}"
                     alt="{{ $vehicle->full_name }}"
                     class="w-full h-full object-cover transition-opacity duration-300">
            </div>

            <!-- Thumbnail Carousel / Grid -->
            @if($vehicle->images->count() > 1)
                <div class="grid grid-cols-3 gap-3">
                    @foreach($vehicle->images as $img)
                        <button type="button"
                                onclick="document.getElementById('main-gallery-image').src = '{{ $img->image_url }}'"
                                class="h-24 rounded-xl overflow-hidden border border-white/10 hover:border-electric-400 transition focus:outline-none">
                            <img src="{{ $img->image_url }}" alt="{{ $img->caption }}" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif

            <!-- Vehicle Narrative & Provenance Description -->
            <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-3">
                <h3 class="text-lg font-bold text-white">Vehicle Provenance & Overview</h3>
                <p class="text-slate-300 text-sm leading-relaxed whitespace-pre-line">
                    {{ $vehicle->description }}
                </p>
            </div>

            <!-- Factory Features & Options List -->
            @if(!empty($vehicle->features) && count($vehicle->features) > 0)
                <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-electric-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Factory Option Equipment & Upgrades
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-slate-300">
                        @foreach($vehicle->features as $feature)
                            <div class="flex items-center gap-2 bg-white/5 rounded-xl px-3.5 py-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-electric-400"></span>
                                <span>{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Specs Table + Booking / Purchase Form -->
        <div class="space-y-6">
            <!-- Technical Specifications Card -->
            <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-4">
                <h3 class="text-base font-bold text-white uppercase tracking-wider text-xs border-b border-white/10 pb-3">
                    Technical Specifications
                </h3>

                <dl class="divide-y divide-white/5 text-xs">
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-400">Powertrain</dt>
                        <dd class="text-white font-semibold text-right">{{ $vehicle->engine_capacity ?: 'High-Performance' }}</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-400">Transmission</dt>
                        <dd class="text-white font-semibold">{{ $vehicle->transmission }}</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-400">Fuel Type</dt>
                        <dd class="text-white font-semibold">{{ $vehicle->fuel_type }}</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-400">Odometer / Mileage</dt>
                        <dd class="text-white font-mono font-semibold">{{ number_format($vehicle->mileage) }} miles</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-400">Seating Capacity</dt>
                        <dd class="text-white font-semibold">{{ $vehicle->seating_capacity }} Passengers</dd>
                    </div>
                    @if($vehicle->color)
                        <div class="py-2.5 flex justify-between">
                            <dt class="text-slate-400">Exterior Finish</dt>
                            <dd class="text-white font-semibold">{{ $vehicle->color }}</dd>
                        </div>
                    @endif
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-400">VIN Identification</dt>
                        <dd class="text-slate-300 font-mono text-[11px]">{{ $vehicle->vin }}</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-400">Registered Plate</dt>
                        <dd class="text-slate-300 font-mono">{{ $vehicle->license_plate }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Booking / Purchase Action Panel -->
            @if(in_array($vehicle->listing_type, ['rental', 'both']) && $vehicle->status === 'available')
                <div class="glass-card rounded-2xl p-6 border border-electric-500/40 shadow-xl" id="booking-section">
                    <h3 class="text-lg font-bold text-white mb-1">Reserve for Rental</h3>
                    <p class="text-xs text-slate-400 mb-5">Select dates to receive instant booking validation.</p>

                    @auth
                        <form method="POST" action="{{ route('customer.bookings.store') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Pickup Date</label>
                                    <input type="date" name="start_date" id="book-start-date" required
                                           min="{{ date('Y-m-d') }}" value="{{ old('start_date', date('Y-m-d')) }}"
                                           onchange="updateRentalQuote()"
                                           class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-electric-400">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Return Date</label>
                                    <input type="date" name="end_date" id="book-end-date" required
                                           min="{{ date('Y-m-d') }}" value="{{ old('end_date', date('Y-m-d', strtotime('+3 days'))) }}"
                                           onchange="updateRentalQuote()"
                                           class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-electric-400">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Pickup Location</label>
                                <select name="pickup_location" class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-electric-400">
                                    <option value="AutoDrive Miami Flagship Terminal">AutoDrive Miami Flagship Terminal</option>
                                    <option value="Miami International Airport (MIA) Signature FBO">Miami Airport (MIA) Signature Flight Support</option>
                                    <option value="Fort Lauderdale Executive Airport (FXE)">Fort Lauderdale Executive (FXE)</option>
                                    <option value="Enclosed Private Villa Transport Delivery">Enclosed Private Villa Delivery (+$150)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Return Location</label>
                                <select name="return_location" class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-electric-400">
                                    <option value="AutoDrive Miami Flagship Terminal">AutoDrive Miami Flagship Terminal</option>
                                    <option value="Miami International Airport (MIA)">Miami International Airport (MIA)</option>
                                    <option value="Fort Lauderdale Airport (FLL)">Fort Lauderdale Airport (FLL)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Special Concierge Requests</label>
                                <textarea name="notes" rows="2" placeholder="e.g., flight number, child safety seat..."
                                          class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-electric-400">{{ old('notes') }}</textarea>
                            </div>

                            <!-- Live Quote Summary Box -->
                            <div class="bg-carbon-950 p-4 rounded-xl border border-white/10 space-y-1.5 text-xs">
                                <div class="flex justify-between text-slate-400">
                                    <span>Duration</span>
                                    <span id="quote-days" class="text-white font-medium">3 billable days</span>
                                </div>
                                <div class="flex justify-between text-slate-400">
                                    <span>Daily Rate</span>
                                    <span class="text-white font-mono">${{ number_format($vehicle->daily_rate, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-slate-400">
                                    <span>Collision & Damage Waiver ($50/d)</span>
                                    <span id="quote-insurance" class="text-white font-mono">$150.00</span>
                                </div>
                                <div class="flex justify-between text-slate-400">
                                    <span>State Sales Tax (7%)</span>
                                    <span id="quote-tax" class="text-white font-mono">$210.00</span>
                                </div>
                                <div class="flex justify-between text-sm font-bold text-white pt-2 border-t border-white/10">
                                    <span>Estimated Total</span>
                                    <span id="quote-total" class="text-electric-400 font-mono">$3,210.00</span>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-3 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-sm shadow-lg shadow-electric-500/25 transition">
                                Submit Rental Request
                            </button>
                        </form>
                    @else
                        <div class="p-4 rounded-xl bg-carbon-950 border border-white/10 text-center space-y-3">
                            <p class="text-xs text-slate-300">Sign in to your AutoDrive account or create a profile to reserve this vehicle.</p>
                            <a href="{{ route('login') }}" class="block w-full py-2.5 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-xs transition">
                                Sign In to Reserve
                            </a>
                        </div>
                    @endauth
                </div>
            @endif

            <!-- Purchase Request Panel -->
            @if(in_array($vehicle->listing_type, ['sale', 'both']))
                <div class="glass-card rounded-2xl p-6 border border-white/10 shadow-xl" id="purchase-section">
                    <h3 class="text-lg font-bold text-white mb-1">Acquisition Desk</h3>
                    <p class="text-xs text-slate-400 mb-4">Submit a formal purchase inquiry or offer.</p>

                    @if($vehicle->status === 'sold')
                        <div class="p-4 rounded-xl bg-rose-950/60 border border-rose-500/30 text-rose-300 text-xs">
                            This vehicle has been sold and its title is transferred. It is preserved for archival provenance.
                        </div>
                    @elseif($vehicle->status === 'reserved')
                        <div class="p-4 rounded-xl bg-amber-950/60 border border-amber-500/30 text-amber-300 text-xs">
                            This vehicle is currently under contract or reservation hold. You may submit a backup inquiry.
                        </div>
                    @else
                        @auth
                            <form method="POST" action="{{ route('customer.purchases.store') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

                                <div>
                                    <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Your Offering Price ($USD)</label>
                                    <input type="number" name="offering_price" step="1000"
                                           value="{{ old('offering_price', $vehicle->selling_price) }}" required
                                           class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3 py-2 text-sm text-white font-mono focus:outline-none focus:border-electric-400">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Purchase Notes & Conditions</label>
                                    <textarea name="customer_notes" rows="3" placeholder="e.g., Wire transfer payment, enclosed shipping request, trade-in details..."
                                              class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-electric-400">{{ old('customer_notes') }}</textarea>
                                </div>

                                <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-electric-500 to-indigo-600 hover:from-electric-400 hover:to-indigo-500 text-white font-bold text-sm shadow-lg shadow-electric-500/25 transition">
                                    Submit Purchase Offer
                                </button>
                            </form>
                        @else
                            <div class="p-4 rounded-xl bg-carbon-950 border border-white/10 text-center space-y-3">
                                <p class="text-xs text-slate-300">Sign in to submit a formal purchase acquisition request.</p>
                                <a href="{{ route('login') }}" class="block w-full py-2.5 rounded-xl bg-carbon-800 hover:bg-carbon-700 text-white font-bold text-xs border border-white/10 transition">
                                    Sign In to Inquire
                                </a>
                            </div>
                        @endauth
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Related Vehicles Section -->
    @if($relatedVehicles->count() > 0)
        <div class="pt-10 border-t border-white/10 space-y-6">
            <h2 class="text-2xl font-bold text-white font-display">
                Similar Vehicles in {{ $vehicle->category?->name }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedVehicles as $rel)
                    <x-vehicle-card :vehicle="$rel" />
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    const dailyRate = {{ (float)$vehicle->daily_rate }};

    function updateRentalQuote() {
        const startInput = document.getElementById('book-start-date');
        const endInput = document.getElementById('book-end-date');

        if (!startInput || !endInput) return;

        const start = new Date(startInput.value);
        const end = new Date(endInput.value);

        if (isNaN(start.getTime()) || isNaN(end.getTime())) return;

        const diffTime = Math.max(0, end - start);
        let days = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        if (days < 1) days = 1;

        const subtotal = days * dailyRate;
        const insurance = days * 50;
        const tax = (subtotal + insurance) * 0.07;
        const total = subtotal + insurance + tax;

        document.getElementById('quote-days').innerText = days + (days === 1 ? ' billable day' : ' billable days');
        document.getElementById('quote-insurance').innerText = '$' + insurance.toFixed(2);
        document.getElementById('quote-tax').innerText = '$' + tax.toFixed(2);
        document.getElementById('quote-total').innerText = '$' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    document.addEventListener('DOMContentLoaded', updateRentalQuote);
</script>
@endsection
