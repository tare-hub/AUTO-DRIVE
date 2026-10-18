@extends('layouts.dashboard')

@section('title', 'Customer Dashboard')
@section('page_title', 'Client Portal')
@section('page_subtitle', "Welcome back, {$user->name}")

@section('content')
<div class="space-y-8">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="glass-card rounded-2xl p-6 border border-white/10 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Active Rentals</span>
                <div class="text-3xl font-extrabold text-white mt-1">{{ $activeBookingsCount }}</div>
                <span class="text-xs text-electric-400 mt-1 block">Vehicles in your possession</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6 border border-white/10 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Pending Purchases</span>
                <div class="text-3xl font-extrabold text-white mt-1">{{ $pendingPurchasesCount }}</div>
                <span class="text-xs text-amber-400 mt-1 block">Under review by sales</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6 border border-white/10 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Spent</span>
                <div class="text-3xl font-extrabold text-white mt-1">${{ number_format($totalSpent, 2) }}</div>
                <span class="text-xs text-emerald-400 mt-1 block">Verified payments recorded</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Active & Recent Rentals -->
    <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-electric-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Recent Rental Bookings
            </h2>
            <a href="{{ route('customer.bookings') }}" class="text-xs text-electric-400 hover:text-electric-300 font-semibold transition">
                View All Bookings &rarr;
            </a>
        </div>

        @if($recentBookings->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="text-slate-400 uppercase tracking-wider border-b border-white/10 bg-white/5">
                        <tr>
                            <th class="py-3 px-4">Booking #</th>
                            <th class="py-3 px-4">Vehicle</th>
                            <th class="py-3 px-4">Dates</th>
                            <th class="py-3 px-4">Total</th>
                            <th class="py-3 px-4">Paid</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($recentBookings as $booking)
                            @php
                                $statusBadge = match($booking->status) {
                                    'active' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                                    'confirmed' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                                    'completed' => 'bg-slate-700 text-slate-300 border-slate-600',
                                    'cancelled' => 'bg-rose-500/20 text-rose-300 border-rose-500/30',
                                    default => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
                                };
                            @endphp
                            <tr class="hover:bg-white/5 transition">
                                <td class="py-3.5 px-4 font-mono font-bold text-white">{{ $booking->booking_number }}</td>
                                <td class="py-3.5 px-4 font-medium text-slate-200">{{ $booking->vehicle?->full_name }}</td>
                                <td class="py-3.5 px-4 text-slate-400">
                                    {{ $booking->start_date->format('M d') }} – {{ $booking->end_date->format('M d, Y') }}
                                    <span class="block text-[10px] text-slate-500">({{ $booking->total_days }} days)</span>
                                </td>
                                <td class="py-3.5 px-4 font-mono font-bold text-white">${{ number_format($booking->total_amount, 2) }}</td>
                                <td class="py-3.5 px-4 font-mono text-emerald-400">${{ number_format($booking->paid_amount, 2) }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $statusBadge }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <a href="{{ route('customer.bookings') }}" class="px-2.5 py-1 rounded-lg bg-carbon-800 hover:bg-carbon-700 text-slate-200 border border-white/10 text-xs">
                                        Manage
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-xs text-slate-400 py-4 text-center">No active or previous rental bookings recorded. <a href="{{ route('vehicles.rent') }}" class="text-electric-400 font-bold hover:underline">Explore the rental fleet</a></p>
        @endif
    </div>

    <!-- Recent Purchases & Recent Payments Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Purchase Requests -->
        <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-electric-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Vehicle Purchase Requests
                </h2>
                <a href="{{ route('customer.purchases') }}" class="text-xs text-electric-400 hover:text-electric-300 font-semibold transition">
                    View All &rarr;
                </a>
            </div>

            @if($recentPurchases->count() > 0)
                <div class="divide-y divide-white/5 text-xs">
                    @foreach($recentPurchases as $pr)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <div class="font-bold text-white">{{ $pr->vehicle?->full_name }}</div>
                                <span class="text-[10px] text-slate-400 font-mono">Offer: ${{ number_format($pr->offering_price, 2) }}</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white/5 border border-white/10 text-slate-300">
                                {{ str_replace('_', ' ', $pr->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-400 py-4 text-center">No purchase requests submitted yet.</p>
            @endif
        </div>

        <!-- Recent Payments -->
        <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-electric-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Recent Payments & Receipts
                </h2>
                <a href="{{ route('customer.payments') }}" class="text-xs text-electric-400 hover:text-electric-300 font-semibold transition">
                    View All &rarr;
                </a>
            </div>

            @if($recentPayments->count() > 0)
                <div class="divide-y divide-white/5 text-xs">
                    @foreach($recentPayments as $payment)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <div class="font-mono font-bold text-white">{{ $payment->transaction_reference }}</div>
                                <span class="text-[10px] text-slate-400">{{ $payment->paid_at?->format('M d, Y h:i A') }}</span>
                            </div>
                            <div class="text-right">
                                <div class="font-mono font-bold text-emerald-400">+${{ number_format($payment->amount, 2) }}</div>
                                <a href="{{ route('payments.receipt', $payment) }}" class="text-[10px] text-electric-400 hover:underline">Print Receipt</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-400 py-4 text-center">No payment transactions recorded yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection
