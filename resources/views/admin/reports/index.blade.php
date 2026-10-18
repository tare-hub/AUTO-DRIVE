@extends('layouts.dashboard')

@section('title', 'Financial Reports & Analytics')
@section('page_title', 'Business Intelligence & Financial Reports')
@section('page_subtitle', 'Audited revenue streams, performance trends, and compliance CSV streaming')

@section('content')
<div class="space-y-8">
    <!-- CSV Export Actions Bar -->
    <div class="glass-card rounded-2xl p-6 border border-white/10 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h3 class="text-base font-bold text-white">Instant Data Export</h3>
            <p class="text-xs text-slate-400">Download formatted CSV reports for compliance, accounting, or executive review.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reports.export', ['type' => 'rentals']) }}"
               class="px-3.5 py-2 rounded-xl bg-carbon-800 hover:bg-carbon-700 text-white text-xs font-semibold border border-white/10 flex items-center gap-1.5 transition">
                <svg class="w-4 h-4 text-electric-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export Rentals CSV
            </a>
            <a href="{{ route('admin.reports.export', ['type' => 'sales']) }}"
               class="px-3.5 py-2 rounded-xl bg-carbon-800 hover:bg-carbon-700 text-white text-xs font-semibold border border-white/10 flex items-center gap-1.5 transition">
                <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export Sales CSV
            </a>
            <a href="{{ route('admin.reports.export', ['type' => 'vehicles']) }}"
               class="px-3.5 py-2 rounded-xl bg-carbon-800 hover:bg-carbon-700 text-white text-xs font-semibold border border-white/10 flex items-center gap-1.5 transition">
                <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export Fleet Inventory CSV
            </a>
        </div>
    </div>

    <!-- Audited Financial Reconciliation Table -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Rental Revenue Streams</span>
            <div class="text-2xl font-black text-white font-mono">${{ number_format($metrics['total_rental_revenue'], 2) }}</div>
            <div class="space-y-1 text-xs pt-3 border-t border-white/5">
                <div class="flex justify-between text-slate-400">
                    <span>Verified Cleared:</span>
                    <span class="text-emerald-400 font-mono font-bold">${{ number_format($metrics['verified_rental_payments'], 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-400">
                    <span>Outstanding Receivables:</span>
                    <span class="text-amber-400 font-mono font-bold">${{ number_format(max(0, $metrics['total_rental_revenue'] - $metrics['verified_rental_payments']), 2) }}</span>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Sales Acquisitions Revenue</span>
            <div class="text-2xl font-black text-electric-400 font-mono">${{ number_format($metrics['total_sales_revenue'], 2) }}</div>
            <div class="space-y-1 text-xs pt-3 border-t border-white/5">
                <div class="flex justify-between text-slate-400">
                    <span>Verified Wire/Escrow:</span>
                    <span class="text-emerald-400 font-mono font-bold">${{ number_format($metrics['verified_sales_payments'], 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-400">
                    <span>Outstanding Escrow:</span>
                    <span class="text-amber-400 font-mono font-bold">${{ number_format(max(0, $metrics['total_sales_revenue'] - $metrics['verified_sales_payments']), 2) }}</span>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Consolidated Cash Position</span>
            <div class="text-2xl font-black text-emerald-400 font-mono">${{ number_format($metrics['total_verified_revenue'], 2) }}</div>
            <div class="space-y-1 text-xs pt-3 border-t border-white/5">
                <div class="flex justify-between text-slate-400">
                    <span>Active Rental Fleet:</span>
                    <span class="text-white font-mono font-bold">{{ $metrics['available_rental'] }} vehicles</span>
                </div>
                <div class="flex justify-between text-slate-400">
                    <span>Available Showroom:</span>
                    <span class="text-white font-mono font-bold">{{ $metrics['available_sale'] }} vehicles</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Rental Ledgers Table -->
    <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-4">
        <h3 class="text-base font-bold text-white">Audited Rental Ledger</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-slate-400 uppercase tracking-wider border-b border-white/10 bg-white/5">
                    <tr>
                        <th class="py-3 px-3">Booking #</th>
                        <th class="py-3 px-3">Client</th>
                        <th class="py-3 px-3">Vehicle</th>
                        <th class="py-3 px-3">Duration</th>
                        <th class="py-3 px-3">Subtotal</th>
                        <th class="py-3 px-3">Insurance</th>
                        <th class="py-3 px-3">Tax (7%)</th>
                        <th class="py-3 px-3">Total Amount</th>
                        <th class="py-3 px-3">Paid Amount</th>
                        <th class="py-3 px-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($rentalBookings as $b)
                        <tr class="hover:bg-white/5 transition font-mono">
                            <td class="py-3 px-3 font-bold text-white">{{ $b->booking_number }}</td>
                            <td class="py-3 px-3 font-sans text-slate-200">{{ $b->user?->name }}</td>
                            <td class="py-3 px-3 font-sans text-slate-300">{{ $b->vehicle?->full_name }}</td>
                            <td class="py-3 px-3 font-sans text-slate-400">{{ $b->total_days }} days</td>
                            <td class="py-3 px-3 text-slate-300">${{ number_format($b->subtotal, 2) }}</td>
                            <td class="py-3 px-3 text-slate-400">${{ number_format($b->insurance_fee, 2) }}</td>
                            <td class="py-3 px-3 text-slate-400">${{ number_format($b->tax_amount, 2) }}</td>
                            <td class="py-3 px-3 font-bold text-white">${{ number_format($b->total_amount, 2) }}</td>
                            <td class="py-3 px-3 font-bold text-emerald-400">${{ number_format($b->paid_amount, 2) }}</td>
                            <td class="py-3 px-3 font-sans uppercase text-[10px] text-slate-300 font-bold">{{ $b->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pt-3 border-t border-white/10">
            {{ $rentalBookings->links() }}
        </div>
    </div>

    <!-- Sales Transactions Ledger -->
    <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-4">
        <h3 class="text-base font-bold text-white">Audited Vehicle Sales Transactions</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-slate-400 uppercase tracking-wider border-b border-white/10 bg-white/5">
                    <tr>
                        <th class="py-3 px-3">Transaction #</th>
                        <th class="py-3 px-3">Invoice #</th>
                        <th class="py-3 px-3">Client</th>
                        <th class="py-3 px-3">Vehicle</th>
                        <th class="py-3 px-3">Sale Date</th>
                        <th class="py-3 px-3">Sale Price</th>
                        <th class="py-3 px-3">Tax & Fees</th>
                        <th class="py-3 px-3">Total Amount</th>
                        <th class="py-3 px-3">Paid Amount</th>
                        <th class="py-3 px-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($salesTransactions as $tx)
                        <tr class="hover:bg-white/5 transition font-mono">
                            <td class="py-3 px-3 font-bold text-white">{{ $tx->transaction_number }}</td>
                            <td class="py-3 px-3 text-slate-400">{{ $tx->invoice_number }}</td>
                            <td class="py-3 px-3 font-sans text-slate-200">{{ $tx->customer?->name }}</td>
                            <td class="py-3 px-3 font-sans text-slate-300">{{ $tx->vehicle?->full_name }}</td>
                            <td class="py-3 px-3 text-slate-400">{{ $tx->sale_date->format('Y-m-d') }}</td>
                            <td class="py-3 px-3 text-slate-300">${{ number_format($tx->sale_price, 2) }}</td>
                            <td class="py-3 px-3 text-slate-400">${{ number_format($tx->tax_amount + $tx->fees_amount, 2) }}</td>
                            <td class="py-3 px-3 font-bold text-electric-400">${{ number_format($tx->total_amount, 2) }}</td>
                            <td class="py-3 px-3 font-bold text-emerald-400">${{ number_format($tx->paid_amount, 2) }}</td>
                            <td class="py-3 px-3 font-sans uppercase text-[10px] text-slate-300 font-bold">{{ $tx->payment_status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pt-3 border-t border-white/10">
            {{ $salesTransactions->links() }}
        </div>
    </div>
</div>
@endsection
