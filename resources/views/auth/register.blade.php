@extends('layouts.app')

@section('title', 'Register Account — AutoDrive')

@section('content')
<div class="py-16 max-w-lg mx-auto px-4 sm:px-6 space-y-8">
    <div class="text-center space-y-2">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-electric-500 to-indigo-600 flex items-center justify-center mx-auto text-white shadow-xl shadow-electric-500/25">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
        </div>
        <h1 class="text-3xl font-extrabold text-white font-display tracking-tight">Create Customer Profile</h1>
        <p class="text-xs text-slate-400">Unlock seamless exotic bookings and luxury purchase management.</p>
    </div>

    <div class="glass-card rounded-2xl p-8 border border-white/10 shadow-2xl">
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Full Legal Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="+1 (555) 000-0000"
                           class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Driver's License #</label>
                    <input type="text" name="driver_license_number" value="{{ old('driver_license_number') }}" placeholder="DL-12345678"
                           class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Password</label>
                    <input type="password" name="password" required
                           class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-sm shadow-lg shadow-electric-500/25 transition">
                    Register Account
                </button>
            </div>
        </form>

        <div class="mt-6 pt-6 border-t border-white/10 text-center text-xs text-slate-400">
            Already have an account?
            <a href="{{ route('login') }}" class="text-electric-400 font-bold hover:underline ml-1">Sign in here</a>
        </div>
    </div>
</div>
@endsection
