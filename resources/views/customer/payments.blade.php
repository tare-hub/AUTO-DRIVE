@extends('layouts.dashboard')

@section('title', 'Payment Records & Receipts')
@section('page_title', 'Payment Ledger & Invoices')
@section('page_subtitle', 'Verified financial transactions, deposits, and printable receipts')

@section('content')
<div class="space-y-6">
    <div class="glass-card rounded-2xl p-6 border border-white/10 overflow-hidden">
        @if($payments->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="text-slate-400 uppercase tracking-wider border-b border-white/10 bg-white/5">
                        <tr>
                            <th class="py-3 px-4">Payment #</th>
                            <th class="py-3 px-4">Reference</th>
                            <th class="py-3 px-4">Related Booking / Sale</th>
                            <th class="py-3 px-4">Amount</th>
                            <th class="py-3 px-4">Method</th>
                            <th class="py-3 px-4">Date</th>
                            <th class="py-3 px-4 text-right">Official Receipt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($payments as $payment)
                            <tr class="hover:bg-white/5 transition">
                                <td class="py-3.5 px-4 font-mono font-bold text-white">{{ $payment->payment_number }}</td>
                                <td class="py-3.5 px-4 font-mono text-slate-300">{{ $payment->transaction_reference }}</td>
                                <td class="py-3.5 px-4 text-slate-200">
                                    @if($payment->payable instanceof \App\Models\RentalBooking)
                                        <span class="text-blue-400 font-semibold">Rental Booking #{{ $payment->payable->booking_number }}</span>
                                    @elseif($payment->payable instanceof \App\Models\SalesTransaction)
                                        <span class="text-amber-400 font-semibold">Sale Transaction #{{ $payment->payable->transaction_number }}</span>
                                    @else
                                        <span>Automotive Services</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 font-mono font-bold text-emerald-400 text-sm">
                                    ${{ number_format($payment->amount, 2) }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-300">
                                    <span class="px-2 py-0.5 rounded bg-white/5 border border-white/10 font-mono text-[10px]">
                                        {{ str_replace('_', ' ', strtoupper($payment->payment_method)) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-400 font-mono">
                                    {{ $payment->paid_at ? $payment->paid_at->format('M d, Y h:i A') : $payment->created_at->format('M d, Y') }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <a href="{{ route('payments.receipt', $payment) }}" target="_blank"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-carbon-800 hover:bg-carbon-700 text-white border border-white/10 text-xs font-semibold transition">
                                        <svg class="w-3.5 h-3.5 text-electric-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                        </svg>
                                        Print Receipt
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-4 border-t border-white/10">
                {{ $payments->links() }}
            </div>
        @else
            <div class="text-center py-12 space-y-3">
                <div class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center mx-auto text-slate-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-white">No Payment Records Found</h3>
                <p class="text-xs text-slate-400">Receipts and verified invoices will be archived here upon payment confirmation.</p>
            </div>
        @endif
    </div>
</div>
@endsection
