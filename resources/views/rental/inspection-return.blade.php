@extends('layouts.dashboard')

@section('title', "Return Inspection — {$booking->booking_number}")
@section('page_title', "Vehicle Return Inspection: {$booking->booking_number}")
@section('page_subtitle', "Check-in diagnostic scan, damage charge assessment, late fee calculation, and inventory release")

@section('content')
<div class="max-w-3xl space-y-6">
    <!-- Vehicle & Booking Summary -->
    <div class="glass-card rounded-2xl p-6 border border-white/10 flex flex-wrap items-center justify-between gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-purple-400 block mb-1">Returning Asset</span>
            <h2 class="text-xl font-bold text-white">{{ $booking->vehicle?->full_name }}</h2>
            <div class="text-xs text-slate-400 mt-1 font-mono">
                Departure Mileage: {{ number_format($booking->vehicle?->mileage) }} mi &bull;
                Scheduled End Date: <strong class="text-white">{{ $booking->end_date->format('M d, Y') }}</strong>
            </div>
        </div>

        <div class="text-right text-xs">
            <span class="text-slate-400 block">Renter:</span>
            <div class="font-bold text-white text-sm">{{ $booking->user?->name }}</div>
            <div class="text-slate-400">{{ $booking->user?->phone }}</div>
            <div class="font-mono text-emerald-400 font-bold mt-1">Paid to date: ${{ number_format($booking->paid_amount, 2) }}</div>
        </div>
    </div>

    <!-- Return Inspection Form -->
    <div class="glass-card rounded-2xl p-8 border border-white/10 shadow-2xl">
        <form method="POST" action="{{ route('rental.inspections.return.store', $booking) }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Final Odometer Reading (Miles) *</label>
                    <input type="number" name="odometer_reading" value="{{ old('odometer_reading', $booking->vehicle->mileage + 120) }}" required min="{{ $booking->vehicle->mileage }}"
                           class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white font-mono focus:outline-none focus:border-purple-400">
                    <span class="text-[11px] text-slate-500 mt-1 block">Must be equal or greater than departure mileage ({{ number_format($booking->vehicle->mileage) }} mi).</span>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Return Fuel / Battery Level *</label>
                    <select name="fuel_level" required class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-purple-400">
                        <option value="Full (100%)">Full (100%) - Returned as received</option>
                        <option value="75%">75%</option>
                        <option value="50%">50% (Refuel fee applies)</option>
                        <option value="25%">25% (Refuel fee applies)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Exterior Return Condition *</label>
                    <input type="text" name="exterior_condition" value="{{ old('exterior_condition', 'Clean, zero post-rental body defects') }}" required
                           class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-purple-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Interior Return Condition *</label>
                    <input type="text" name="interior_condition" value="{{ old('interior_condition', 'Clean, no cabin stains') }}" required
                           class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-purple-400">
                </div>
            </div>

            <!-- Damage Charge & Overdue Rule Note -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Assessed Damage Charges ($USD)</label>
                    <input type="number" step="0.01" name="damage_charge" value="{{ old('damage_charge', 0.00) }}" min="0"
                           class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white font-mono focus:outline-none focus:border-purple-400">
                    <span class="text-[11px] text-slate-500 mt-1 block">Leave 0 if vehicle returned in acceptable condition.</span>
                </div>

                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="requires_maintenance" value="1" {{ old('requires_maintenance') ? 'checked' : '' }}
                               class="rounded bg-carbon-900 border-white/20 text-purple-500 focus:ring-0">
                        <span class="text-xs font-semibold text-slate-200">Send vehicle to Maintenance Workshop instead of returning to Available inventory</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Detailed Inspection Notes</label>
                <textarea name="damages_notes" rows="3" placeholder="Enter diagnostic observations, tire wear, or damage itemization..."
                          class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-purple-400">{{ old('damages_notes', 'All diagnostic codes clear. Braking, powertrain, and chassis verified.') }}</textarea>
            </div>

            <div class="p-4 rounded-xl bg-purple-950/40 border border-purple-500/30 text-xs text-purple-200 space-y-1">
                <div class="font-bold text-white uppercase">Automated Return Protocol:</div>
                <p>1. Overdue late fees ($150 penalty + daily rate per day) are calculated automatically if current date is beyond scheduled return.</p>
                <p>2. Vehicle will be returned to <strong>AVAILABLE</strong> inventory immediately for public booking (or Maintenance if selected).</p>
                <p>3. Booking status will be marked <strong>COMPLETED</strong>.</p>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-white/10">
                <a href="{{ route('rental.dashboard') }}" class="px-5 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 text-xs font-semibold">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-sm shadow-lg shadow-purple-600/25 transition">
                    Complete Return & Release Vehicle
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
