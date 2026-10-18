@extends('layouts.dashboard')

@section('title', "Edit {$vehicle->full_name}")
@section('page_title', "Edit {$vehicle->full_name}")
@section('page_subtitle', 'Update technical specifications, rates, and fleet attributes')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="glass-card rounded-2xl p-8 border border-white/10 shadow-xl">
        <form method="POST" action="{{ route('admin.vehicles.update', $vehicle) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Basic Identifiers -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-electric-400 border-b border-white/10 pb-2 mb-4">
                    1. Basic Identification
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Brand / Make *</label>
                        <input type="text" name="brand" value="{{ old('brand', $vehicle->brand) }}" required
                               class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-electric-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Model *</label>
                        <input type="text" name="model" value="{{ old('model', $vehicle->model) }}" required
                               class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-electric-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Model Year *</label>
                        <input type="number" name="year" value="{{ old('year', $vehicle->year) }}" required min="1990" max="2030"
                               class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-electric-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Category *</label>
                        <select name="category_id" required class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-electric-400">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $vehicle->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">VIN Number *</label>
                        <input type="text" name="vin" value="{{ old('vin', $vehicle->vin) }}" required
                               class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white font-mono focus:outline-none focus:border-electric-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">License Plate *</label>
                        <input type="text" name="license_plate" value="{{ old('license_plate', $vehicle->license_plate) }}" required
                               class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white font-mono focus:outline-none focus:border-electric-400">
                    </div>
                </div>
            </div>

            <!-- Section 2: Technical Specifications -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-electric-400 border-b border-white/10 pb-2 mb-4">
                    2. Technical Specifications
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Transmission *</label>
                        <select name="transmission" required class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-electric-400">
                            <option value="Automatic" {{ old('transmission', $vehicle->transmission) === 'Automatic' ? 'selected' : '' }}>Automatic</option>
                            <option value="Dual-Clutch" {{ old('transmission', $vehicle->transmission) === 'Dual-Clutch' ? 'selected' : '' }}>Dual-Clutch</option>
                            <option value="Manual" {{ old('transmission', $vehicle->transmission) === 'Manual' ? 'selected' : '' }}>Manual</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Fuel / Power *</label>
                        <select name="fuel_type" required class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-electric-400">
                            <option value="Petrol" {{ old('fuel_type', $vehicle->fuel_type) === 'Petrol' ? 'selected' : '' }}>Petrol</option>
                            <option value="Electric" {{ old('fuel_type', $vehicle->fuel_type) === 'Electric' ? 'selected' : '' }}>Electric</option>
                            <option value="Hybrid" {{ old('fuel_type', $vehicle->fuel_type) === 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                            <option value="Diesel" {{ old('fuel_type', $vehicle->fuel_type) === 'Diesel' ? 'selected' : '' }}>Diesel</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Seating Capacity *</label>
                        <input type="number" name="seating_capacity" value="{{ old('seating_capacity', $vehicle->seating_capacity) }}" required min="1" max="12"
                               class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-electric-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Odometer / Mileage *</label>
                        <input type="number" name="mileage" value="{{ old('mileage', $vehicle->mileage) }}" required min="0"
                               class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white font-mono focus:outline-none focus:border-electric-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Engine Output / Specs</label>
                        <input type="text" name="engine_capacity" value="{{ old('engine_capacity', $vehicle->engine_capacity) }}"
                               class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-electric-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Color / Finish</label>
                        <input type="text" name="color" value="{{ old('color', $vehicle->color) }}"
                               class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-electric-400">
                    </div>
                </div>
            </div>

            <!-- Section 3: Pricing & Inventory Control -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-electric-400 border-b border-white/10 pb-2 mb-4">
                    3. Pricing & Listing Control
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Daily Rental Rate ($) *</label>
                        <input type="number" step="0.01" name="daily_rate" value="{{ old('daily_rate', $vehicle->daily_rate) }}" required
                               class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white font-mono focus:outline-none focus:border-electric-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Selling Price ($)</label>
                        <input type="number" step="0.01" name="selling_price" value="{{ old('selling_price', $vehicle->selling_price) }}"
                               class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white font-mono focus:outline-none focus:border-electric-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Listing Type *</label>
                        <select name="listing_type" required class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-electric-400">
                            <option value="rental" {{ old('listing_type', $vehicle->listing_type) === 'rental' ? 'selected' : '' }}>Rental Only</option>
                            <option value="sale" {{ old('listing_type', $vehicle->listing_type) === 'sale' ? 'selected' : '' }}>Sale Only</option>
                            <option value="both" {{ old('listing_type', $vehicle->listing_type) === 'both' ? 'selected' : '' }}>Both Rental & Sale</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Inventory Status *</label>
                        <select name="status" required class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-electric-400">
                            <option value="available" {{ old('status', $vehicle->status) === 'available' ? 'selected' : '' }}>Available</option>
                            <option value="rented" {{ old('status', $vehicle->status) === 'rented' ? 'selected' : '' }}>Rented</option>
                            <option value="reserved" {{ old('status', $vehicle->status) === 'reserved' ? 'selected' : '' }}>Reserved</option>
                            <option value="sold" {{ old('status', $vehicle->status) === 'sold' ? 'selected' : '' }}>Sold</option>
                            <option value="maintenance" {{ old('status', $vehicle->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Condition *</label>
                        <select name="condition" required class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-electric-400">
                            <option value="new" {{ old('condition', $vehicle->condition) === 'new' ? 'selected' : '' }}>Brand New</option>
                            <option value="certified_preowned" {{ old('condition', $vehicle->condition) === 'certified_preowned' ? 'selected' : '' }}>Certified Pre-Owned</option>
                            <option value="used" {{ old('condition', $vehicle->condition) === 'used' ? 'selected' : '' }}>Pre-Owned</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Main Image URL</label>
                        <input type="url" name="main_image" value="{{ old('main_image', $vehicle->main_image) }}"
                               class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-electric-400">
                    </div>
                </div>
            </div>

            <!-- Section 4: Narrative Description & Options -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-electric-400 border-b border-white/10 pb-2 mb-4">
                    4. Description & Key Options
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Description / Provenance</label>
                        <textarea name="description" rows="3"
                                  class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-electric-400">{{ old('description', $vehicle->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Equipment / Features (One per line)</label>
                        @php
                            $featuresText = is_array($vehicle->features) ? implode("\n", $vehicle->features) : '';
                        @endphp
                        <textarea name="features_list" rows="3"
                                  class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-electric-400">{{ old('features_list', $featuresText) }}</textarea>
                    </div>

                    <label class="flex items-center gap-2 cursor-pointer pt-2">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $vehicle->is_featured) ? 'checked' : '' }}
                               class="rounded bg-carbon-900 border-white/20 text-electric-500 focus:ring-0">
                        <span class="text-xs font-semibold text-slate-300">Feature prominently on public homepage and marketplace headers</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/10">
                <a href="{{ route('admin.vehicles.index') }}" class="px-5 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 text-xs font-semibold">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-sm shadow-lg shadow-electric-500/25 transition">
                    Save Vehicle Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
