@extends('layouts.dashboard')

@section('title', "Edit {$user->name}")
@section('page_title', "Edit User Account: {$user->name}")
@section('page_subtitle', 'Modify assigned role permissions, credentials, and account activation status')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="glass-card rounded-2xl p-8 border border-white/10 shadow-xl">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Full Legal Name *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Email Address *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Role Privileges *</label>
                    <select name="role" required class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                        <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Customer (Standard client)</option>
                        <option value="rental_manager" {{ old('role', $user->role) === 'rental_manager' ? 'selected' : '' }}>Rental Manager</option>
                        <option value="sales_manager" {{ old('role', $user->role) === 'sales_manager' ? 'selected' : '' }}>Sales Manager</option>
                        <option value="customer_support" {{ old('role', $user->role) === 'customer_support' ? 'selected' : '' }}>Customer Support</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>System Administrator</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Account Status *</label>
                    <select name="status" required class="w-full bg-carbon-900 border border-white/15 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                        <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Phone Number *</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required
                           class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Driver's License #</label>
                    <input type="text" name="driver_license_number" value="{{ old('driver_license_number', $user->driver_license_number) }}"
                           class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Reset Password (Leave blank to keep unchanged)</label>
                <input type="password" name="password" placeholder="New password..."
                       class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Address</label>
                <textarea name="address" rows="2" class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-electric-400">{{ old('address', $user->address) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-white/10">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 text-xs font-semibold">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-sm shadow-lg shadow-electric-500/25 transition">
                    Save Account Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
