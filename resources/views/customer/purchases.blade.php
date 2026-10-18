@extends('layouts.dashboard')

@section('title', 'My Vehicle Purchases')
@section('page_title', 'Acquisition Inquiries & Purchases')
@section('page_subtitle', 'Track formal purchase requests, manager reviews, and finalized vehicle sales')

@section('content')
<div class="space-y-6">
    <div class="glass-card rounded-2xl p-6 border border-white/10 overflow-hidden">
        @if($purchaseRequests->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="text-slate-400 uppercase tracking-wider border-b border-white/10 bg-white/5">
                        <tr>
                            <th class="py-3 px-4">Request #</th>
                            <th class="py-3 px-4">Vehicle</th>
                            <th class="py-3 px-4">Offering Price</th>
                            <th class="py-3 px-4">Review Status</th>
                            <th class="py-3 px-4">Manager Review Notes</th>
                            <th class="py-3 px-4 text-right">Transaction</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($purchaseRequests as $request)
                            @php
                                $statusBadge = match($request->status) {
                                    'completed' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                                    'approved' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                                    'under_review' => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
                                    'rejected' => 'bg-rose-500/20 text-rose-300 border-rose-500/30',
                                    default => 'bg-slate-700 text-slate-300 border-slate-600',
                                };
                            @endphp
                            <tr class="hover:bg-white/5 transition align-top">
                                <td class="py-4 px-4 font-mono font-bold text-white">
                                    {{ $request->request_number }}
                                    <span class="block text-[10px] text-slate-500 font-sans mt-0.5">{{ $request->created_at->format('M d, Y') }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-bold text-slate-200 text-sm">{{ $request->vehicle?->full_name }}</div>
                                    <div class="text-[11px] text-slate-400">VIN: {{ $request->vehicle?->vin }}</div>
                                </td>
                                <td class="py-4 px-4 font-mono font-bold text-electric-400 text-sm">
                                    ${{ number_format($request->offering_price, 2) }}
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $statusBadge }}">
                                        {{ str_replace('_', ' ', $request->status) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-slate-300 max-w-xs">
                                    {{ $request->staff_notes ?: 'Pending review by sales manager.' }}
                                </td>
                                <td class="py-4 px-4 text-right">
                                    @if($request->salesTransaction)
                                        <div class="space-y-1">
                                            <span class="font-mono text-emerald-400 font-bold block">{{ $request->salesTransaction->transaction_number }}</span>
                                            <span class="text-[10px] text-slate-400 block font-mono">Invoice: {{ $request->salesTransaction->invoice_number }}</span>
                                            @if($request->salesTransaction->balance_due > 0)
                                                <button type="button"
                                                        onclick="openTxPaymentModal('{{ $request->salesTransaction->id }}', '{{ $request->salesTransaction->transaction_number }}', '{{ $request->salesTransaction->balance_due }}')"
                                                        class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-[10px] shadow-sm transition">
                                                    Pay Balance (${{ number_format($request->salesTransaction->balance_due, 2) }})
                                                </button>
                                            @else
                                                <span class="inline-block px-2 py-0.5 rounded bg-emerald-950 text-emerald-300 border border-emerald-700/50 text-[10px] font-bold">
                                                    Fully Paid
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-[11px] text-slate-500">In Negotiation</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-4 border-t border-white/10">
                {{ $purchaseRequests->links() }}
            </div>
        @else
            <div class="text-center py-12 space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mx-auto text-slate-400">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white">No Purchase Requests Found</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto">Explore vehicles currently available for private acquisition in our showroom.</p>
                <a href="{{ route('vehicles.buy') }}" class="inline-block px-5 py-2.5 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-xs shadow-lg shadow-electric-500/25 transition">
                    Browse Vehicles for Sale
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Modal: Pay Sales Transaction Balance -->
<div id="tx-pay-modal" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="glass-card rounded-2xl p-6 border border-white/15 max-w-md w-full space-y-4">
        <h3 class="text-lg font-bold text-white">Sales Transaction Payment</h3>
        <p class="text-xs text-slate-400">
            Transaction <strong id="tx-pay-number" class="text-white font-mono"></strong>. Record verified card or wire payment.
        </p>

        <form id="tx-pay-form" method="POST" action="" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Payment Amount ($USD)</label>
                <input type="number" step="0.01" name="amount" id="tx-pay-amount" required
                       class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-sm text-white font-mono focus:outline-none focus:border-electric-400">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Payment Method</label>
                <select name="payment_method" class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-electric-400">
                    <option value="bank_transfer">Wire / Escrow Transfer (Recommended)</option>
                    <option value="simulated_card">Simulated Black Card</option>
                    <option value="cash">Certified Cashier's Check</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('tx-pay-modal').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 text-xs">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md shadow-emerald-600/25 transition">
                    Confirm Acquisition Payment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openTxPaymentModal(txId, txNumber, balanceDue) {
        document.getElementById('tx-pay-number').innerText = txNumber;
        document.getElementById('tx-pay-amount').value = balanceDue;
        document.getElementById('tx-pay-form').action = '/payments/transaction/' + txId;
        document.getElementById('tx-pay-modal').classList.remove('hidden');
    }
</script>
@endsection
