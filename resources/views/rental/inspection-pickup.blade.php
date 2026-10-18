@extends('layouts.dashboard')

@section('title', "Pickup Inspection — {$booking->booking_number}")
@section('page_title', "Vehicle Departure Inspection: {$booking->booking_number}")
@section('page_subtitle', "Record departure odometer, fuel level, and condition before customer release")

@section('content')
<div class="max-w-3xl space-y-6">
    <!-- Vehicle & Customer Snapshot -->
    <div class="glass-card rounded-2xl p-6 border border-white/10 flex flex-wrap items-center justify-between gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-electric-400 block mb-1">Handover Asset</span>
            <h2 class="text-xl font-bold text-white">{{ $booking->vehicle?->full_name }}</h2>
            <div class="text-xs text-slate-400 mt-1 font-mono">
                Plate: <strong class="text-white">{{ $booking->vehicle?->license_plate }}</strong> &bull;
                Current Odometer: {{ number_format($booking->vehicle?->mileage) }} mi
            </div>
        </div>

        <div class="text-right text-xs">
            <span class="text-slate-400 block">Renter / Driver:</span>
            <div class="font-bold text-white text-sm">{{ $booking->user?->name }}</div>
            <div class="text-slate-400">{{ $booking->user?->phone }}</div>
            <div class="font-mono text-slate-500">DL: {{ $booking->user?->driver_license_number ?: 'Not provided' }}</div>
        </div>
    </div>

    <!-- Inspection Form -->
    <div class="glass-card rounded-2xl p-8 border border-white/10 shadow-2xl">
        <form method="POST" action="{{ route('rental.inspections.pickup.store', $booking) }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Departure Odometer (Miles) *</label>
                    <input type="number" name="odometer_reading" value="{{ old('odometer_reading', $booking->vehicle->mileage) }}" required min="0"
                           class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white font-mono focus:outline-none focus:border-electric-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Fuel / Charge Level *</label>
                    <select name="fuel_level" required class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                        <option value="Full (100%)">Full (100%)</option>
                        <option value="90%">90%</option>
                        <option value="80%">80%</option>
                        <option value="75%">75%</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Exterior Body Condition *</label>
                    <input type="text" name="exterior_condition" value="{{ old('exterior_condition', 'Pristine, 100% scratch-free') }}" required
                           class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Interior Cockpit Condition *</label>
                    <input type="text" name="interior_condition" value="{{ old('interior_condition', 'Sanitized, detailed, clean leather') }}" required
                           class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Pre-Existing Imperfection Notes</label>
                <textarea name="damages_notes" rows="3" placeholder="Note any minor rock chips, wheel curbage, or pre-existing blemishes..."
                          class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">{{ old('damages_notes', 'None. Zero pre-existing body or rim imperfections noted.') }}</textarea>
            </div>

            <div class="p-4 rounded-xl bg-blue-950/40 border border-blue-500/30 text-xs text-blue-200">
                Submitting this inspection will transition booking <strong>#{{ $booking->booking_number }}</strong> to <span class="font-bold uppercase text-white">ACTIVE</span> and set the vehicle status to <span class="font-bold uppercase text-white">RENTED</span>.
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-white/10">
                <a href="{{ route('rental.dashboard') }}" class="px-5 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 text-xs font-semibold">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm shadow-lg shadow-blue-600/25 transition">
                    Complete Departure Inspection & Activate
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
