@extends('layouts.dashboard')

@section('title', 'Admin Executive Console')
@section('page_title', 'Executive Management Console')
@section('page_subtitle', 'System-wide fleet status, revenue intelligence, operations, and audit trails')

@section('content')
<div class="space-y-8">
    <!-- Top KPI Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <!-- Total Vehicles -->
        <div class="glass-card rounded-2xl p-4 border border-white/10">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Total Fleet</span>
            <div class="text-2xl font-black text-white mt-1">{{ $metrics['total_vehicles'] }}</div>
            <span class="text-[10px] text-slate-500 mt-0.5 block">Assets registered</span>
        </div>

        <!-- Available for Rental -->
        <div class="glass-card rounded-2xl p-4 border border-white/10">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Available Rental</span>
            <div class="text-2xl font-black text-emerald-400 mt-1">{{ $metrics['available_rental'] }}</div>
            <span class="text-[10px] text-emerald-500 mt-0.5 block">Ready for handoff</span>
        </div>

        <!-- Currently Rented -->
        <div class="glass-card rounded-2xl p-4 border border-white/10">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Currently Rented</span>
            <div class="text-2xl font-black text-blue-400 mt-1">{{ $metrics['currently_rented'] }}</div>
            <span class="text-[10px] text-blue-500 mt-0.5 block">On the road</span>
        </div>

        <!-- Under Maintenance -->
        <div class="glass-card rounded-2xl p-4 border border-white/10">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Maintenance</span>
            <div class="text-2xl font-black text-rose-400 mt-1">{{ $metrics['under_maintenance'] }}</div>
            <span class="text-[10px] text-rose-500 mt-0.5 block">In workshop</span>
        </div>

        <!-- Available For Sale -->
        <div class="glass-card rounded-2xl p-4 border border-white/10">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">For Sale</span>
            <div class="text-2xl font-black text-amber-400 mt-1">{{ $metrics['available_sale'] }}</div>
            <span class="text-[10px] text-amber-500 mt-0.5 block">In showroom</span>
        </div>

        <!-- Sold Vehicles -->
        <div class="glass-card rounded-2xl p-4 border border-white/10">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Vehicles Sold</span>
            <div class="text-2xl font-black text-purple-400 mt-1">{{ $metrics['sold_vehicles'] }}</div>
            <span class="text-[10px] text-purple-500 mt-0.5 block">Acquisitions closed</span>
        </div>
    </div>

    <!-- Revenue Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-2">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Recorded Rental Revenue</span>
            <div class="text-3xl font-extrabold text-white font-mono">
                ${{ number_format($metrics['total_rental_revenue'], 2) }}
            </div>
            <div class="flex justify-between text-xs text-slate-400 pt-2 border-t border-white/5">
                <span>Verified Cleared Payments:</span>
                <span class="text-emerald-400 font-mono font-semibold">${{ number_format($metrics['verified_rental_payments'], 2) }}</span>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-2">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Recorded Sales Revenue</span>
            <div class="text-3xl font-extrabold text-electric-400 font-mono">
                ${{ number_format($metrics['total_sales_revenue'], 2) }}
            </div>
            <div class="flex justify-between text-xs text-slate-400 pt-2 border-t border-white/5">
                <span>Verified Cleared Payments:</span>
                <span class="text-emerald-400 font-mono font-semibold">${{ number_format($metrics['verified_sales_payments'], 2) }}</span>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-2">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Combined Verified Cash Flow</span>
            <div class="text-3xl font-extrabold text-emerald-400 font-mono">
                ${{ number_format($metrics['total_verified_revenue'], 2) }}
            </div>
            <div class="flex justify-between text-xs text-slate-400 pt-2 border-t border-white/5">
                <span>Pending Rental + Sale Requests:</span>
                <span class="text-amber-400 font-bold font-mono">{{ $metrics['pending_rentals'] + $metrics['pending_purchases'] }} pending</span>
            </div>
        </div>
    </div>

    <!-- Category Fleet Distribution & Booking Status Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Fleet by Category -->
        <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-4">
            <h3 class="text-base font-bold text-white flex items-center justify-between">
                <span>Fleet Distribution by Category</span>
                <span class="text-xs text-slate-400 font-normal">Active Models</span>
            </h3>
            <div class="space-y-3">
                @foreach($inventoryByCategory as $cat)
                    @php
                        $percent = $metrics['total_vehicles'] > 0 ? round(($cat['count'] / $metrics['total_vehicles']) * 100) : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between text-xs text-slate-300 mb-1">
                            <span>{{ $cat['name'] }}</span>
                            <span class="font-mono">{{ $cat['count'] }} vehicles ({{ $percent }}%)</span>
                        </div>
                        <div class="w-full bg-carbon-900 rounded-full h-2 overflow-hidden border border-white/5">
                            <div class="bg-gradient-to-r from-electric-500 to-indigo-500 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Booking Status Breakdown -->
        <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-4">
            <h3 class="text-base font-bold text-white flex items-center justify-between">
                <span>Rental Operations Status Distribution</span>
                <span class="text-xs text-slate-400 font-normal">Lifetime Volume</span>
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-carbon-900 p-4 rounded-xl border border-white/5">
                    <span class="text-xs text-amber-400 uppercase font-semibold">Pending Requests</span>
                    <div class="text-2xl font-bold text-white mt-1">{{ $bookingDistribution['pending'] ?? 0 }}</div>
                </div>
                <div class="bg-carbon-900 p-4 rounded-xl border border-white/5">
                    <span class="text-xs text-emerald-400 uppercase font-semibold">Confirmed Scheduled</span>
                    <div class="text-2xl font-bold text-white mt-1">{{ $bookingDistribution['confirmed'] ?? 0 }}</div>
                </div>
                <div class="bg-carbon-900 p-4 rounded-xl border border-white/5">
                    <span class="text-xs text-blue-400 uppercase font-semibold">Currently Active</span>
                    <div class="text-2xl font-bold text-white mt-1">{{ $bookingDistribution['active'] ?? 0 }}</div>
                </div>
                <div class="bg-carbon-900 p-4 rounded-xl border border-white/5">
                    <span class="text-xs text-slate-400 uppercase font-semibold">Completed Cleanly</span>
                    <div class="text-2xl font-bold text-white mt-1">{{ $bookingDistribution['completed'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Operations & Activity Stream -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Bookings (2 Cols) -->
        <div class="lg:col-span-2 glass-card rounded-2xl p-6 border border-white/10 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-bold text-white">Recent Rental Bookings</h3>
                <a href="{{ route('rental.dashboard') }}" class="text-xs text-electric-400 hover:text-electric-300 font-semibold">
                    Manage Rentals &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="text-slate-400 uppercase tracking-wider border-b border-white/10 bg-white/5">
                        <tr>
                            <th class="py-2.5 px-3">Booking #</th>
                            <th class="py-2.5 px-3">Customer</th>
                            <th class="py-2.5 px-3">Vehicle</th>
                            <th class="py-2.5 px-3">Total</th>
                            <th class="py-2.5 px-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($recentBookings as $b)
                            <tr class="hover:bg-white/5 transition">
                                <td class="py-3 px-3 font-mono font-bold text-white">{{ $b->booking_number }}</td>
                                <td class="py-3 px-3 text-slate-300">{{ $b->user?->name }}</td>
                                <td class="py-3 px-3 text-slate-200">{{ $b->vehicle?->full_name }}</td>
                                <td class="py-3 px-3 font-mono text-emerald-400">${{ number_format($b->total_amount, 2) }}</td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-white/5 border border-white/10 text-slate-300">
                                        {{ $b->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- System Audit Trail (1 Col) -->
        <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-4">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-electric-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Audit Activity Stream
            </h3>

            <div class="divide-y divide-white/5 text-xs">
                @foreach($recentActivities as $act)
                    <div class="py-2.5 space-y-1">
                        <div class="flex items-center justify-between text-slate-400">
                            <span class="font-mono text-[10px] text-electric-400 font-bold">{{ $act->action }}</span>
                            <span class="text-[10px]">{{ $act->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-slate-300 text-[11px] leading-relaxed">{{ $act->description }}</p>
                        @if($act->user)
                            <span class="text-[10px] text-slate-500 block">By {{ $act->user->name }} ({{ $act->user->role }})</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
