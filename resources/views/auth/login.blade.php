@extends('layouts.app')

@section('title', 'Sign In — AutoDrive Console')

@section('content')
<div class="py-16 max-w-md mx-auto px-4 sm:px-6 space-y-8">
    <div class="text-center space-y-2">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-electric-500 to-indigo-600 flex items-center justify-center mx-auto text-white shadow-xl shadow-electric-500/25">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <h1 class="text-3xl font-extrabold text-white font-display tracking-tight">Access Your Account</h1>
        <p class="text-xs text-slate-400">Sign in to manage rentals, vehicle purchases, or access your staff console.</p>
    </div>

    <!-- Quick Demo Personas Box -->
    <div class="glass-card rounded-2xl p-5 border border-white/10 space-y-3">
        <div class="flex items-center justify-between text-xs">
            <span class="font-bold text-slate-200">Instant Demo Login:</span>
            <span class="text-slate-400">1-Click Role Switch</span>
        </div>
        <div class="grid grid-cols-2 gap-2 text-xs">
            <a href="{{ route('demo.login', 'admin') }}" class="p-2 rounded-xl bg-purple-950/60 hover:bg-purple-900 border border-purple-700/50 text-purple-200 text-center font-medium transition">
                Admin Console
            </a>
            <a href="{{ route('demo.login', 'rental') }}" class="p-2 rounded-xl bg-blue-950/60 hover:bg-blue-900 border border-blue-700/50 text-blue-200 text-center font-medium transition">
                Rental Manager
            </a>
            <a href="{{ route('demo.login', 'sales') }}" class="p-2 rounded-xl bg-amber-950/60 hover:bg-amber-900 border border-amber-700/50 text-amber-200 text-center font-medium transition">
                Sales Manager
            </a>
            <a href="{{ route('demo.login', 'support') }}" class="p-2 rounded-xl bg-teal-950/60 hover:bg-teal-900 border border-teal-700/50 text-teal-200 text-center font-medium transition">
                Customer Support
            </a>
        </div>
        <a href="{{ route('demo.login', 'customer') }}" class="block w-full p-2 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-600 text-slate-200 text-center text-xs font-medium transition">
            Customer Persona (Jonathan Reynolds)
        </a>
    </div>

    <!-- Main Sign In Form -->
    <div class="glass-card rounded-2xl p-8 border border-white/10 shadow-2xl">
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Email Address</label>
                <input type="email" name="email" value="{{ old('email', 'customer@autodrive.com') }}" required autofocus
                       class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Password</label>
                <input type="password" name="password" value="password" required
                       class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 text-slate-300 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded bg-carbon-900 border-white/20 text-electric-500 focus:ring-0">
                    <span>Keep me signed in</span>
                </label>
                <span class="text-slate-400">Demo pwd: <code class="text-electric-400">password</code></span>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-sm shadow-lg shadow-electric-500/25 transition">
                Sign In
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-white/10 text-center text-xs text-slate-400">
            Don't have an AutoDrive account?
            <a href="{{ route('register') }}" class="text-electric-400 font-bold hover:underline ml-1">Register now</a>
        </div>
    </div>
</div>
@endsection
