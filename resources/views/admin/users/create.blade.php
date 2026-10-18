@extends('layouts.dashboard')

@section('title', 'Add New Staff / User')
@section('page_title', 'Create User / Staff Account')
@section('page_subtitle', 'Provision accounts and assign administrative or operational privileges')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="glass-card rounded-2xl p-8 border border-white/10 shadow-xl">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Full Legal Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Email Address *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Assigned Role *</label>
                    <select name="role" required class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                        <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>Customer (Standard client)</option>
                        <option value="rental_manager" {{ old('role') === 'rental_manager' ? 'selected' : '' }}>Rental Manager (Inspections & Handovers)</option>
                        <option value="sales_manager" {{ old('role') === 'sales_manager' ? 'selected' : '' }}>Sales Manager (Purchases & Escrows)</option>
                        <option value="customer_support" {{ old('role') === 'customer_support' ? 'selected' : '' }}>Customer Support (Inquiries)</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>System Administrator (Full access)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Phone Number *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="+1 (555) 000-0000"
                           class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Driver's License #</label>
                    <input type="text" name="driver_license_number" value="{{ old('driver_license_number') }}" placeholder="DL-12345678"
                           class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Initial Password *</label>
                    <input type="password" name="password" required value="password"
                           class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Address</label>
                <textarea name="address" rows="2" class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-electric-400">{{ old('address') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-white/10">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 text-xs font-semibold">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-sm shadow-lg shadow-electric-500/25 transition">
                    Create User Account
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
