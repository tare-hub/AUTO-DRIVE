@extends('layouts.dashboard')

@section('title', 'My Rental Bookings')
@section('page_title', 'My Rental Reservations')
@section('page_subtitle', 'Review your upcoming itineraries, active rentals, and past journeys')

@section('content')
<div class="space-y-6">
    <div class="glass-card rounded-2xl p-6 border border-white/10 overflow-hidden">
        @if($bookings->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="text-slate-400 uppercase tracking-wider border-b border-white/10 bg-white/5">
                        <tr>
                            <th class="py-3 px-4">Booking #</th>
                            <th class="py-3 px-4">Vehicle</th>
                            <th class="py-3 px-4">Rental Window</th>
                            <th class="py-3 px-4">Financials</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($bookings as $booking)
                            @php
                                $statusBadge = match($booking->status) {
                                    'active' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                                    'confirmed' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                                    'completed' => 'bg-slate-700 text-slate-300 border-slate-600',
                                    'cancelled' => 'bg-rose-500/20 text-rose-300 border-rose-500/30',
                                    default => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
                                };
                            @endphp
                            <tr class="hover:bg-white/5 transition align-top">
                                <td class="py-4 px-4 font-mono font-bold text-white">
                                    {{ $booking->booking_number }}
                                    <span class="block text-[10px] text-slate-500 font-sans mt-0.5">Created {{ $booking->created_at->format('M d, Y') }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-bold text-slate-200 text-sm">{{ $booking->vehicle?->full_name }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">
                                        Pickup: <span class="text-slate-300">{{ $booking->pickup_location }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="text-slate-200 font-medium">
                                        {{ $booking->start_date->format('M d, Y') }} &rarr; {{ $booking->end_date->format('M d, Y') }}
                                    </div>
                                    <div class="text-[11px] text-electric-400 mt-0.5 font-semibold">
                                        {{ $booking->total_days }} billable day(s) &bull; ${{ number_format($booking->daily_rate, 2) }}/d
                                    </div>
                                </td>
                                <td class="py-4 px-4 font-mono">
                                    <div class="text-sm font-bold text-white">Total: ${{ number_format($booking->total_amount, 2) }}</div>
                                    <div class="text-[11px] text-emerald-400">Paid: ${{ number_format($booking->paid_amount, 2) }}</div>
                                    @if($booking->balance_due > 0)
                                        <div class="text-[11px] text-amber-400 font-semibold">Due: ${{ number_format($booking->balance_due, 2) }}</div>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $statusBadge }}">
                                        {{ $booking->status }}
                                    </span>
                                    @if($booking->cancellation_reason)
                                        <p class="text-[10px] text-rose-300 mt-1 max-w-xs">{{ $booking->cancellation_reason }}</p>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-right space-y-2">
                                    <!-- Pay Balance Action if Due -->
                                    @if($booking->balance_due > 0 && in_array($booking->status, ['pending', 'confirmed', 'active', 'completed']))
                                        <button type="button"
                                                onclick="openPaymentModal('{{ $booking->id }}', '{{ $booking->booking_number }}', '{{ $booking->balance_due }}')"
                                                class="inline-block px-3 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition">
                                            Pay Balance
                                        </button>
                                    @endif

                                    <!-- Cancel Booking Button if Eligible -->
                                    @if($booking->isCancellableByCustomer())
                                        <button type="button"
                                                onclick="openCancelModal('{{ $booking->id }}', '{{ $booking->booking_number }}')"
                                                class="inline-block px-3 py-1 rounded-lg bg-rose-950/80 hover:bg-rose-900 text-rose-300 border border-rose-700/50 text-xs transition">
                                            Cancel Booking
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-4 border-t border-white/10">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="text-center py-12 space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mx-auto text-slate-400">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white">No Rental Bookings Found</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto">You have not booked any rental vehicles yet. Find your drive in our curated exotic fleet.</p>
                <a href="{{ route('vehicles.rent') }}" class="inline-block px-5 py-2.5 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-xs shadow-lg shadow-electric-500/25 transition">
                    Browse Rental Fleet
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Modal: Cancel Rental Booking -->
<div id="cancel-modal" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="glass-card rounded-2xl p-6 border border-white/15 max-w-md w-full space-y-4">
        <h3 class="text-lg font-bold text-white">Cancel Reservation</h3>
        <p class="text-xs text-slate-400">
            Are you sure you want to cancel booking <strong id="cancel-booking-number" class="text-white"></strong>? The vehicle hold will be released immediately.
        </p>

        <form id="cancel-form" method="POST" action="" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase text-slate-300 mb-1.5">Reason for Cancellation</label>
                <textarea name="cancellation_reason" rows="3" required placeholder="Please let us know the reason for cancellation..."
                          class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-rose-400"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('cancel-modal').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 text-xs">
                    Keep Booking
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs transition">
                    Confirm Cancellation
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Pay Outstanding Balance -->
<div id="pay-modal" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="glass-card rounded-2xl p-6 border border-white/15 max-w-md w-full space-y-4">
        <h3 class="text-lg font-bold text-white">Pay Remaining Balance</h3>
        <p class="text-xs text-slate-400">
            Booking <strong id="pay-booking-number" class="text-white"></strong>. Complete simulated card or wire checkout.
        </p>

        <form id="pay-form" method="POST" action="" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Payment Amount ($USD)</label>
                <input type="number" step="0.01" name="amount" id="pay-amount" required
                       class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white font-mono focus:outline-none focus:border-electric-400">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Payment Method</label>
                <select name="payment_method" class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-electric-400">
                    <option value="simulated_card">Simulated Visa / Mastercard (Instant Verification)</option>
                    <option value="bank_transfer">Wire / ACH Bank Transfer</option>
                    <option value="cash">In-Terminal Cash Handover</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('pay-modal').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 text-xs">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md shadow-emerald-600/25 transition">
                    Submit Payment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openCancelModal(bookingId, bookingNumber) {
        document.getElementById('cancel-booking-number').innerText = bookingNumber;
        document.getElementById('cancel-form').action = '/bookings/' + bookingId + '/cancel';
        document.getElementById('cancel-modal').classList.remove('hidden');
    }

    function openPaymentModal(bookingId, bookingNumber, balanceDue) {
        document.getElementById('pay-booking-number').innerText = bookingNumber;
        document.getElementById('pay-amount').value = balanceDue;
        document.getElementById('pay-form').action = '/payments/booking/' + bookingId;
        document.getElementById('pay-modal').classList.remove('hidden');
    }
</script>
@endsection
