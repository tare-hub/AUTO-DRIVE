@extends('layouts.dashboard')

@section('title', 'Profile Settings')
@section('page_title', 'Client Profile')
@section('page_subtitle', 'Manage contact information and driver documentation')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="glass-card rounded-2xl p-8 border border-white/10 shadow-xl">
        <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Legal Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Email Address</label>
                <input type="email" value="{{ $user->email }}" disabled
                       class="w-full bg-carbon-950 border border-white/5 rounded-xl px-4 py-2.5 text-sm text-slate-400 cursor-not-allowed">
                <span class="text-[11px] text-slate-500 mt-1 block">To update your verified email address, please submit a customer support inquiry.</span>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Direct Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required
                       class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Driver's License / Identification #</label>
                <input type="text" name="driver_license_number" value="{{ old('driver_license_number', $user->driver_license_number) }}"
                       placeholder="e.g. DL-10294812"
                       class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Residential / Delivery Address</label>
                <textarea name="address" rows="3"
                          class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">{{ old('address', $user->address) }}</textarea>
            </div>

            <button type="submit" class="px-6 py-2.5 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-sm shadow-lg shadow-electric-500/25 transition">
                Save Profile Changes
            </button>
        </form>
    </div>
</div>
@endsection
