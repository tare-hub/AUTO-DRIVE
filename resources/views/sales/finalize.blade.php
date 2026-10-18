@extends('layouts.dashboard')

@section('title', "Finalize Sale — {$request->request_number}")
@section('page_title', "Finalize Vehicle Acquisition: {$request->request_number}")
@section('page_subtitle', "Execute formal bill of sale, verify funds, and permanently lock vehicle status to SOLD")

@section('content')
<div class="max-w-3xl space-y-6">
    <!-- Purchase Offer Summary -->
    <div class="glass-card rounded-2xl p-6 border border-white/10 flex flex-wrap items-center justify-between gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-amber-400 block mb-1">Asset Acquisition</span>
            <h2 class="text-xl font-bold text-white">{{ $request->vehicle?->full_name }}</h2>
            <div class="text-xs text-slate-400 mt-1 font-mono">
                VIN: <strong class="text-white">{{ $request->vehicle?->vin }}</strong> &bull;
                Mileage: {{ number_format($request->vehicle?->mileage) }} mi
            </div>
        </div>

        <div class="text-right text-xs">
            <span class="text-slate-400 block">Buyer:</span>
            <div class="font-bold text-white text-sm">{{ $request->user?->name }}</div>
            <div class="text-slate-400">{{ $request->user?->email }} &bull; {{ $request->user?->phone }}</div>
            <div class="font-mono text-electric-400 font-bold mt-1">Agreed Offer: ${{ number_format($request->offering_price, 2) }}</div>
        </div>
    </div>

    <!-- Finalize Sale Form -->
    <div class="glass-card rounded-2xl p-8 border border-white/10 shadow-2xl">
        <form method="POST" action="{{ route('sales.requests.finalize.store', $request) }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Final Agreed Base Price ($USD) *</label>
                    <input type="number" step="0.01" name="sale_price" id="sale-price" required
                           value="{{ old('sale_price', $request->offering_price) }}"
                           oninput="calculateSaleTotal()"
                           class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white font-mono focus:outline-none focus:border-electric-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Initial Cleared Remittance ($USD) *</label>
                    <input type="number" step="0.01" name="paid_amount" id="paid-amount" required
                           value="{{ old('paid_amount', $request->offering_price * 1.07 + 1500) }}"
                           class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white font-mono focus:outline-none focus:border-electric-400">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Verified Remittance Method *</label>
                <select name="payment_method" required class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                    <option value="bank_transfer">Wire Transfer (Fedwire / SWIFT Cleared)</option>
                    <option value="simulated_card">Simulated Black Card</option>
                    <option value="cash">Certified Cashier's Check</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Transaction Notes & Escrow Conditions</label>
                <textarea name="notes" rows="3" placeholder="Notes regarding title handoff, transport delivery, insurance binder..."
                          class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">{{ old('notes', 'Title transferred to buyer. Clean bill of sale notarized. Enclosed carrier dispatched.') }}</textarea>
            </div>

            <!-- Estimated Calculation Box -->
            <div class="bg-carbon-950 p-4 rounded-xl border border-white/10 space-y-1.5 text-xs">
                <div class="flex justify-between text-slate-400">
                    <span>Base Vehicle Sale Price</span>
                    <span id="display-base" class="text-white font-mono font-medium">${{ number_format($request->offering_price, 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-400">
                    <span>State Sales Tax (7.00%)</span>
                    <span id="display-tax" class="text-white font-mono">${{ number_format($request->offering_price * 0.07, 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-400">
                    <span>Documentation, Title & Registration Fee</span>
                    <span class="text-white font-mono">$1,500.00</span>
                </div>
                <div class="flex justify-between text-sm font-bold text-white pt-2 border-t border-white/10">
                    <span>Final Bill of Sale Total</span>
                    <span id="display-total" class="text-electric-400 font-mono">${{ number_format($request->offering_price * 1.07 + 1500, 2) }}</span>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-rose-950/40 border border-rose-500/30 text-xs text-rose-200">
                <strong class="text-white uppercase">Critical Irreversible Action:</strong>
                Submitting this contract will generate an official sales invoice, create a verified payment record, and permanently switch vehicle <strong>{{ $request->vehicle?->full_name }}</strong> to <span class="font-bold text-white uppercase">SOLD</span>. It will be removed from all public active listings.
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-white/10">
                <a href="{{ route('sales.dashboard') }}" class="px-5 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 text-xs font-semibold">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-sm shadow-lg shadow-emerald-600/25 transition">
                    Execute Sale & Issue Bill of Sale
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function calculateSaleTotal() {
        const priceInput = document.getElementById('sale-price');
        const paidInput = document.getElementById('paid-amount');
        if (!priceInput) return;

        const base = parseFloat(priceInput.value) || 0;
        const tax = base * 0.07;
        const fees = 1500;
        const total = base + tax + fees;

        document.getElementById('display-base').innerText = '$' + base.toFixed(2);
        document.getElementById('display-tax').innerText = '$' + tax.toFixed(2);
        document.getElementById('display-total').innerText = '$' + total.toFixed(2);

        if (paidInput && !paidInput.dataset.manuallyEdited) {
            paidInput.value = total.toFixed(2);
        }
    }

    document.getElementById('paid-amount')?.addEventListener('input', function() {
        this.dataset.manuallyEdited = "true";
    });
</script>
@endsection
