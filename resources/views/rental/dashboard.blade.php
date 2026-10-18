@extends('layouts.dashboard')

@section('title', 'Rental Operations Workspace')
@section('page_title', 'Rental Dispatch & Operations')
@section('page_subtitle', 'Oversee pending reservation requests, vehicle check-outs, return inspections, and fleet status')

@section('content')
<div class="space-y-8">
    <!-- Stat Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="glass-card rounded-2xl p-4 border border-white/10">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-amber-400">Pending Requests</span>
            <div class="text-2xl font-black text-white mt-1">{{ $stats['pending'] }}</div>
            <span class="text-[10px] text-slate-500 mt-0.5 block">Awaiting approval</span>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-white/10">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-emerald-400">Confirmed</span>
            <div class="text-2xl font-black text-white mt-1">{{ $stats['confirmed'] }}</div>
            <span class="text-[10px] text-slate-500 mt-0.5 block">Scheduled for pickup</span>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-white/10">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-blue-400">Active On Road</span>
            <div class="text-2xl font-black text-white mt-1">{{ $stats['active'] }}</div>
            <span class="text-[10px] text-slate-500 mt-0.5 block">With client</span>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-white/10">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Completed</span>
            <div class="text-2xl font-black text-white mt-1">{{ $stats['completed'] }}</div>
            <span class="text-[10px] text-slate-500 mt-0.5 block">Inspected & returned</span>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-white/10">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-emerald-400">Fleet Ready</span>
            <div class="text-2xl font-black text-emerald-400 mt-1">{{ $stats['vehicles_available'] }}</div>
            <span class="text-[10px] text-slate-500 mt-0.5 block">Available in hangar</span>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-white/10">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-blue-400">Fleet Out</span>
            <div class="text-2xl font-black text-blue-400 mt-1">{{ $stats['vehicles_rented'] }}</div>
            <span class="text-[10px] text-slate-500 mt-0.5 block">Rented status</span>
        </div>
    </div>

    <!-- Filter Pills Bar -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs font-semibold">
        <a href="{{ route('rental.dashboard', ['status' => 'all']) }}"
           class="px-3.5 py-1.5 rounded-xl transition {{ $status === 'all' ? 'bg-electric-500 text-white font-bold' : 'bg-carbon-900 text-slate-400 hover:text-white border border-white/10' }}">
            All Bookings
        </a>
        <a href="{{ route('rental.dashboard', ['status' => 'pending']) }}"
           class="px-3.5 py-1.5 rounded-xl transition {{ $status === 'pending' ? 'bg-amber-500 text-black font-bold' : 'bg-carbon-900 text-slate-400 hover:text-white border border-white/10' }}">
            Pending Action ({{ $stats['pending'] }})
        </a>
        <a href="{{ route('rental.dashboard', ['status' => 'confirmed']) }}"
           class="px-3.5 py-1.5 rounded-xl transition {{ $status === 'confirmed' ? 'bg-emerald-500 text-white font-bold' : 'bg-carbon-900 text-slate-400 hover:text-white border border-white/10' }}">
            Confirmed for Pickup ({{ $stats['confirmed'] }})
        </a>
        <a href="{{ route('rental.dashboard', ['status' => 'active']) }}"
           class="px-3.5 py-1.5 rounded-xl transition {{ $status === 'active' ? 'bg-blue-500 text-white font-bold' : 'bg-carbon-900 text-slate-400 hover:text-white border border-white/10' }}">
            Active Rentals ({{ $stats['active'] }})
        </a>
        <a href="{{ route('rental.dashboard', ['status' => 'completed']) }}"
           class="px-3.5 py-1.5 rounded-xl transition {{ $status === 'completed' ? 'bg-slate-700 text-white font-bold' : 'bg-carbon-900 text-slate-400 hover:text-white border border-white/10' }}">
            Completed History ({{ $stats['completed'] }})
        </a>
    </div>

    <!-- Bookings Operational Management Table -->
    <div class="glass-card rounded-2xl p-6 border border-white/10 overflow-hidden space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-slate-400 uppercase tracking-wider border-b border-white/10 bg-white/5">
                    <tr>
                        <th class="py-3 px-3">Booking #</th>
                        <th class="py-3 px-3">Customer</th>
                        <th class="py-3 px-3">Vehicle</th>
                        <th class="py-3 px-3">Dates</th>
                        <th class="py-3 px-3">Locations</th>
                        <th class="py-3 px-3">Financials</th>
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-3 text-right">Operational Workflow</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($bookings as $b)
                        @php
                            $statusBadge = match($b->status) {
                                'active' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                                'confirmed' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                                'completed' => 'bg-slate-700 text-slate-300 border-slate-600',
                                'cancelled' => 'bg-rose-500/20 text-rose-300 border-rose-500/30',
                                default => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
                            };
                        @endphp
                        <tr class="hover:bg-white/5 transition align-top">
                            <td class="py-4 px-3 font-mono font-bold text-white">
                                {{ $b->booking_number }}
                                <span class="block text-[10px] text-slate-500 font-sans">{{ $b->created_at->format('M d, H:i') }}</span>
                            </td>
                            <td class="py-4 px-3">
                                <div class="font-bold text-white">{{ $b->user?->name }}</div>
                                <span class="text-[10px] text-slate-400">{{ $b->user?->phone }}</span>
                                @if($b->user?->driver_license_number)
                                    <div class="text-[10px] text-slate-500 font-mono">DL: {{ $b->user->driver_license_number }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-3">
                                <div class="font-bold text-slate-200">{{ $b->vehicle?->full_name }}</div>
                                <span class="text-[10px] text-slate-400 font-mono">Plate: {{ $b->vehicle?->license_plate }} &bull; Rate: ${{ number_format($b->daily_rate, 0) }}/d</span>
                            </td>
                            <td class="py-4 px-3">
                                <div class="text-slate-200 font-medium">
                                    {{ $b->start_date->format('M d') }} &rarr; {{ $b->end_date->format('M d, Y') }}
                                </div>
                                <span class="text-[10px] text-electric-400 font-bold font-mono">{{ $b->total_days }} days</span>
                            </td>
                            <td class="py-4 px-3 text-[11px] text-slate-400 max-w-[150px]">
                                <div class="truncate" title="{{ $b->pickup_location }}">Out: {{ $b->pickup_location }}</div>
                                <div class="truncate text-slate-500" title="{{ $b->return_location }}">In: {{ $b->return_location }}</div>
                            </td>
                            <td class="py-4 px-3 font-mono">
                                <div class="text-white font-bold">${{ number_format($b->total_amount, 2) }}</div>
                                <div class="text-[10px] text-emerald-400">Paid: ${{ number_format($b->paid_amount, 2) }}</div>
                                @if($b->balance_due > 0)
                                    <div class="text-[10px] text-amber-400 font-bold">Due: ${{ number_format($b->balance_due, 2) }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $statusBadge }}">
                                    {{ $b->status }}
                                </span>
                            </td>
                            <td class="py-4 px-3 text-right space-y-1.5">
                                @if($b->status === 'pending')
                                    <!-- Approve & Reject buttons -->
                                    <div class="flex items-center justify-end gap-1.5">
                                        <form method="POST" action="{{ route('rental.bookings.approve', $b) }}">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-sm transition">
                                                Approve
                                            </button>
                                        </form>

                                        <button type="button"
                                                onclick="openRejectModal('{{ $b->id }}', '{{ $b->booking_number }}')"
                                                class="px-2.5 py-1 rounded-lg bg-rose-950/80 hover:bg-rose-900 text-rose-300 text-xs border border-rose-700/50 transition">
                                            Reject
                                        </button>
                                    </div>
                                @elseif($b->status === 'confirmed')
                                    <!-- Execute Pickup Inspection -->
                                    <a href="{{ route('rental.inspections.pickup', $b) }}"
                                       class="inline-block px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-md shadow-blue-600/25 transition">
                                        Pickup & Inspection &rarr;
                                    </a>
                                @elseif($b->status === 'active')
                                    <!-- Execute Return Inspection -->
                                    <a href="{{ route('rental.inspections.return', $b) }}"
                                       class="inline-block px-3 py-1.5 rounded-lg bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow-md shadow-purple-600/25 transition">
                                        Inspect Return & Check-in &rarr;
                                    </a>
                                @elseif($b->status === 'completed')
                                    <span class="inline-block px-2.5 py-1 rounded bg-white/5 text-slate-400 text-[10px]">
                                        Archived ({{ $b->inspections->count() }} inspections)
                                    </span>
                                @else
                                    <span class="text-[10px] text-slate-500">Cancelled</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">No rental bookings currently under selected criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 border-t border-white/10">
            {{ $bookings->links() }}
        </div>
    </div>
</div>

<!-- Modal: Reject Rental Booking -->
<div id="reject-modal" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="glass-card rounded-2xl p-6 border border-white/15 max-w-md w-full space-y-4">
        <h3 class="text-lg font-bold text-white">Reject Rental Booking</h3>
        <p class="text-xs text-slate-400">
            Provide a mandatory rejection reason for booking <strong id="reject-booking-number" class="text-white"></strong>.
        </p>

        <form id="reject-form" method="POST" action="" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Reason for Rejection *</label>
                <textarea name="reason" rows="3" required placeholder="e.g., Driver license verification failed, vehicle booked for private charter..."
                          class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-400"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 text-xs">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs transition">
                    Reject Booking
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openRejectModal(bookingId, bookingNumber) {
        document.getElementById('reject-booking-number').innerText = bookingNumber;
        document.getElementById('reject-form').action = '/rental/bookings/' + bookingId + '/reject';
        document.getElementById('reject-modal').classList.remove('hidden');
    }
</script>
@endsection
