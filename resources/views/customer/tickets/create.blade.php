@extends('layouts.dashboard')

@section('title', 'Open Support Ticket')
@section('page_title', 'Open New Concierge Ticket')
@section('page_subtitle', 'Submit a formal inquiry to our fleet and customer support team')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="glass-card rounded-2xl p-8 border border-white/10 shadow-xl">
        <form method="POST" action="{{ route('customer.tickets.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Subject / Inquiry Title</label>
                <input type="text" name="subject" value="{{ old('subject') }}" required placeholder="e.g., FBO Tarmac Delivery for upcoming Porsche rental"
                       class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Category</label>
                    <select name="category" required class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                        <option value="rental" {{ old('category') === 'rental' ? 'selected' : '' }}>Rental Logistics & Itinerary</option>
                        <option value="sales" {{ old('category') === 'sales' ? 'selected' : '' }}>Vehicle Purchase / Acquisitions</option>
                        <option value="payment" {{ old('category') === 'payment' ? 'selected' : '' }}>Payments & Invoices</option>
                        <option value="general" {{ old('category') === 'general' ? 'selected' : '' }}>General Inquiry</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Priority Level</label>
                    <select name="priority" required class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                        <option value="low">Low (General guidance)</option>
                        <option value="medium" selected>Medium (Standard inquiry)</option>
                        <option value="high">High (Upcoming booking within 24h)</option>
                        <option value="urgent">Urgent (Immediate active rental assistance)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Detailed Message</label>
                <textarea name="message" rows="5" required placeholder="Describe your request or question in detail..."
                          class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-electric-400">{{ old('message') }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <a href="{{ route('customer.tickets.index') }}" class="px-5 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 text-xs font-semibold">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-sm shadow-lg shadow-electric-500/25 transition">
                    Submit Support Ticket
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
