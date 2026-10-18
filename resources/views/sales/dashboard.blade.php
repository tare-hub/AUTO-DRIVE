@extends('layouts.dashboard')

@section('title', 'Sales Operations Workspace')
@section('page_title', 'Acquisitions & Sales Escrow')
@section('page_subtitle', 'Review purchase requests, reserve vehicle inventory, verify wire funds, and finalize vehicle sales')

@section('content')
<div class="space-y-8">
    <!-- Stat Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="glass-card rounded-2xl p-4 border border-white/10">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-amber-400">Pending Offers</span>
            <div class="text-2xl font-black text-white mt-1">{{ $stats['pending_requests'] }}</div>
            <span class="text-[10px] text-slate-500 mt-0.5 block">Awaiting manager review</span>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-white/10">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-blue-400">Under Review</span>
            <div class="text-2xl font-black text-white mt-1">{{ $stats['under_review'] }}</div>
            <span class="text-[10px] text-slate-500 mt-0.5 block">Negotiations in progress</span>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-white/10">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-emerald-400">Approved Offers</span>
            <div class="text-2xl font-black text-white mt-1">{{ $stats['approved_requests'] }}</div>
            <span class="text-[10px] text-slate-500 mt-0.5 block">Ready for contract finalization</span>
        </div>

        <div class="glass-card rounded-2xl p-4 border border-white/10">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-purple-400">Finalized Sales</span>
            <div class="text-2xl font-black text-white mt-1">{{ $stats['completed_sales'] }}</div>
            <span class="text-[10px] text-slate-500 mt-0.5 block">${{ number_format($stats['total_sales_revenue'], 0) }} gross</span>
        </div>
    </div>

    <!-- Purchase Requests Management Table -->
    <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-electric-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Incoming Client Purchase Requests & Offers
            </h3>
            <span class="text-xs text-slate-400">Review, reserve inventory, and finalize sales</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-slate-400 uppercase tracking-wider border-b border-white/10 bg-white/5">
                    <tr>
                        <th class="py-3 px-3">Request #</th>
                        <th class="py-3 px-3">Client</th>
                        <th class="py-3 px-3">Vehicle</th>
                        <th class="py-3 px-3">Offer Amount</th>
                        <th class="py-3 px-3">Client Notes</th>
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($purchaseRequests as $req)
                        @php
                            $statusBadge = match($req->status) {
                                'completed' => 'bg-purple-500/20 text-purple-300 border-purple-500/30',
                                'approved' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                                'under_review' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                                'rejected' => 'bg-rose-500/20 text-rose-300 border-rose-500/30',
                                default => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
                            };
                        @endphp
                        <tr class="hover:bg-white/5 transition align-top">
                            <td class="py-3.5 px-3 font-mono font-bold text-white">
                                {{ $req->request_number }}
                                <span class="block text-[10px] text-slate-500 font-sans">{{ $req->created_at->format('M d, H:i') }}</span>
                            </td>
                            <td class="py-3.5 px-3">
                                <div class="font-bold text-white">{{ $req->user?->name }}</div>
                                <span class="text-[10px] text-slate-400">{{ $req->user?->email }}</span>
                                <div class="text-[10px] text-slate-500">{{ $req->user?->phone }}</div>
                            </td>
                            <td class="py-3.5 px-3">
                                <div class="font-bold text-slate-200">{{ $req->vehicle?->full_name }}</div>
                                <span class="text-[10px] text-slate-400 font-mono">List: ${{ number_format($req->vehicle?->selling_price, 2) }}</span>
                                <span class="block text-[10px] text-slate-500">Status: {{ ucfirst($req->vehicle?->status) }}</span>
                            </td>
                            <td class="py-3.5 px-3 font-mono font-bold text-electric-400 text-sm">
                                ${{ number_format($req->offering_price, 2) }}
                            </td>
                            <td class="py-3.5 px-3 text-slate-300 max-w-xs">
                                {{ $req->customer_notes ?: 'No specific notes.' }}
                                @if($req->staff_notes)
                                    <div class="mt-1 text-[10px] text-electric-300 bg-white/5 p-1 rounded">
                                        Staff: {{ $req->staff_notes }}
                                    </div>
                                @endif
                            </td>
                            <td class="py-3.5 px-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $statusBadge }}">
                                    {{ str_replace('_', ' ', $req->status) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3 text-right space-y-1.5">
                                @if(in_array($req->status, ['pending', 'under_review']))
                                    <!-- Review Actions Dropdown / Buttons -->
                                    <div class="flex items-center justify-end gap-1.5">
                                        <form method="POST" action="{{ route('sales.requests.review', $req) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-sm transition" title="Approves offer and reserves vehicle">
                                                Approve & Reserve
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('sales.requests.review', $req) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-950/80 hover:bg-rose-900 text-rose-300 text-xs border border-rose-700/50 transition">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                @elseif($req->status === 'approved')
                                    <a href="{{ route('sales.requests.finalize', $req) }}"
                                       class="inline-block px-3 py-1.5 rounded-lg bg-gradient-to-r from-electric-500 to-indigo-600 hover:from-electric-400 hover:to-indigo-500 text-white font-bold text-xs shadow-md transition">
                                        Finalize Sale & Bill &rarr;
                                    </a>
                                @elseif($req->status === 'completed')
                                    <span class="inline-block px-2.5 py-1 rounded bg-purple-950/60 text-purple-300 border border-purple-700/50 text-[10px] font-bold">
                                        Sale Closed
                                    </span>
                                @else
                                    <span class="text-[10px] text-slate-500">Rejected</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">No purchase inquiries recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-3 border-t border-white/10">
            {{ $purchaseRequests->links() }}
        </div>
    </div>

    <!-- Completed Sales Transactions Table -->
    <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-4">
        <h3 class="text-base font-bold text-white flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Finalized Sales Transaction Archive
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-slate-400 uppercase tracking-wider border-b border-white/10 bg-white/5">
                    <tr>
                        <th class="py-3 px-3">Transaction #</th>
                        <th class="py-3 px-3">Invoice #</th>
                        <th class="py-3 px-3">Buyer</th>
                        <th class="py-3 px-3">Vehicle</th>
                        <th class="py-3 px-3">Sale Date</th>
                        <th class="py-3 px-3">Sale Price</th>
                        <th class="py-3 px-3">Total Billed</th>
                        <th class="py-3 px-3">Paid Amount</th>
                        <th class="py-3 px-3">Payment Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($salesTransactions as $sale)
                        <tr class="hover:bg-white/5 transition font-mono">
                            <td class="py-3 px-3 font-bold text-white">{{ $sale->transaction_number }}</td>
                            <td class="py-3 px-3 text-slate-400">{{ $sale->invoice_number }}</td>
                            <td class="py-3 px-3 font-sans text-slate-200">{{ $sale->customer?->name }}</td>
                            <td class="py-3 px-3 font-sans text-slate-300">{{ $sale->vehicle?->full_name }}</td>
                            <td class="py-3 px-3 text-slate-400">{{ $sale->sale_date->format('M d, Y') }}</td>
                            <td class="py-3 px-3 text-slate-300">${{ number_format($sale->sale_price, 2) }}</td>
                            <td class="py-3 px-3 font-bold text-electric-400">${{ number_format($sale->total_amount, 2) }}</td>
                            <td class="py-3 px-3 font-bold text-emerald-400">${{ number_format($sale->paid_amount, 2) }}</td>
                            <td class="py-3 px-3 font-sans">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $sale->payment_status === 'paid' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-amber-500/20 text-amber-300' }}">
                                    {{ $sale->payment_status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-6 text-center text-slate-400 font-sans">No completed sales recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-3 border-t border-white/10">
            {{ $salesTransactions->links() }}
        </div>
    </div>
</div>
@endsection
