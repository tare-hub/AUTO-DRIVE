<!DOCTYPE html>
<html lang="en" class="bg-carbon-950 text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Official Receipt — {{ $payment->payment_number }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-display { font-family: 'Space Grotesk', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; color: #111827 !important; }
            .receipt-card { border: 1px solid #e5e7eb !important; background: white !important; color: #111827 !important; box-shadow: none !important; }
            .text-white { color: #111827 !important; }
            .text-slate-300, .text-slate-400 { color: #4b5563 !important; }
            .bg-carbon-950, .bg-carbon-900 { background: #f9fafb !important; }
        }
    </style>
</head>
<body class="p-6 sm:p-12 min-h-screen flex items-center justify-center">

    <div class="max-w-3xl w-full receipt-card glass-card rounded-3xl p-8 sm:p-12 border border-white/10 shadow-2xl space-y-8">
        <!-- Top Action Bar -->
        <div class="no-print flex items-center justify-between pb-6 border-b border-white/10">
            <a href="javascript:history.back()" class="text-xs text-slate-400 hover:text-white flex items-center gap-1.5 transition">
                &larr; Back to Portal
            </a>
            <button type="button" onclick="window.print()" class="px-5 py-2 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-xs shadow-lg shadow-electric-500/25 flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print / Save PDF
            </button>
        </div>

        <!-- Receipt Header -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6">
            <div>
                <div class="flex items-center mb-2">
                    <x-application-logo variant="electric" class="h-8 w-auto object-contain" />
                </div>
                <p class="text-xs text-slate-400 leading-relaxed">
                    AutoDrive International Inc.<br>
                    100 Ocean Drive, Suite 500<br>
                    Miami Beach, FL 33139<br>
                    Tax ID: 85-92019482
                </p>
            </div>

            <div class="text-left sm:text-right">
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 inline-block mb-2">
                    Verified Transaction
                </span>
                <div class="text-sm font-mono font-bold text-white">{{ $payment->payment_number }}</div>
                <div class="text-xs text-slate-400 mt-1">Ref: <span class="font-mono text-slate-300">{{ $payment->transaction_reference }}</span></div>
                <div class="text-xs text-slate-400">Timestamp: {{ $payment->paid_at ? $payment->paid_at->format('M d, Y h:i A') : $payment->created_at->format('M d, Y') }}</div>
            </div>
        </div>

        <!-- Billed Customer Details -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-4 rounded-2xl bg-white/5 border border-white/5 text-xs">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Customer / Client</span>
                <div class="font-bold text-white text-sm">{{ $payment->user?->name }}</div>
                <div class="text-slate-400">{{ $payment->user?->email }}</div>
                <div class="text-slate-400">{{ $payment->user?->phone }}</div>
                @if($payment->user?->driver_license_number)
                    <div class="text-slate-400 mt-1 font-mono">DL: {{ $payment->user->driver_license_number }}</div>
                @endif
            </div>

            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Payment Method & Verification</span>
                <div class="font-mono font-bold text-white uppercase">{{ str_replace('_', ' ', $payment->payment_method) }}</div>
                <div class="text-emerald-400 font-semibold mt-0.5">Status: PAID & CLEARED</div>
                @if($payment->verifier)
                    <div class="text-slate-400 mt-1">Verified by: {{ $payment->verifier->name }} (Staff)</div>
                @endif
            </div>
        </div>

        <!-- Line Items Table -->
        <div>
            <table class="w-full text-left text-xs">
                <thead class="text-slate-400 uppercase tracking-wider border-b border-white/10">
                    <tr>
                        <th class="py-2.5">Description</th>
                        <th class="py-2.5 text-center">Type</th>
                        <th class="py-2.5 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <tr>
                        <td class="py-4">
                            @if($payment->payable instanceof \App\Models\RentalBooking)
                                <div class="font-bold text-white text-sm">Rental Booking #{{ $payment->payable->booking_number }}</div>
                                <div class="text-slate-400 text-xs mt-0.5">
                                    {{ $payment->payable->vehicle?->full_name }} &bull;
                                    {{ $payment->payable->start_date->format('M d, Y') }} to {{ $payment->payable->end_date->format('M d, Y') }}
                                    ({{ $payment->payable->total_days }} days)
                                </div>
                            @elseif($payment->payable instanceof \App\Models\SalesTransaction)
                                <div class="font-bold text-white text-sm">Vehicle Purchase Acquisition #{{ $payment->payable->transaction_number }}</div>
                                <div class="text-slate-400 text-xs mt-0.5">
                                    {{ $payment->payable->vehicle?->full_name }} &bull; Invoice #{{ $payment->payable->invoice_number }}
                                </div>
                            @else
                                <div class="font-bold text-white text-sm">Automotive Transaction</div>
                            @endif
                        </td>
                        <td class="py-4 text-center text-slate-300 uppercase font-mono text-[10px]">
                            {{ class_basename($payment->payable_type) }}
                        </td>
                        <td class="py-4 text-right font-mono font-bold text-white text-sm">
                            ${{ number_format($payment->amount, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Total Box -->
        <div class="border-t border-white/10 pt-4 flex justify-between items-center">
            <div class="text-xs text-slate-400">
                <span>Receipt Notes: {{ $payment->notes ?: 'Full payment processed.' }}</span>
            </div>
            <div class="text-right">
                <span class="text-xs uppercase tracking-wider text-slate-400 block">Total Amount Paid</span>
                <div class="text-2xl font-extrabold text-emerald-400 font-mono">
                    ${{ number_format($payment->amount, 2) }} USD
                </div>
            </div>
        </div>

        <!-- Legal Disclaimer & Sign-Off -->
        <div class="pt-6 border-t border-white/5 text-[10px] text-slate-500 leading-relaxed text-center">
            This electronic voucher constitutes a legally verified receipt for automotive services or vehicle acquisition delivered by AutoDrive International Inc. No additional funds are due for this specific recorded remittance.
        </div>
    </div>

</body>
</html>
