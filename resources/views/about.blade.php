@extends('layouts.app')

@section('title', 'About Us — AutoDrive Provenance & Standards')

@section('content')
    <div class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        <!-- Header -->
        <div class="max-w-3xl space-y-4">
            <span class="text-xs font-bold uppercase tracking-widest text-electric-400 block">Our Heritage</span>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-white font-display tracking-tight">
                Crafting Automotive Experiences Since 2018.
            </h1>
            <p class="text-slate-300 text-base leading-relaxed">
                Headquartered at our flagship terminal in Miami Beach, AutoDrive was founded on a singular conviction: the acquisition and rental of high-performance automobiles should be as exhilarating and refined as the machines themselves.
            </p>
        </div>

        <!-- Pillars -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="glass-card rounded-2xl p-8 border border-white/10">
                <div class="w-12 h-12 rounded-xl bg-electric-500/20 text-electric-400 flex items-center justify-center mb-6">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Pristine Fleet Provenance</h3>
                <p class="text-sm text-slate-400 leading-relaxed">
                    We do not outsource our fleet. Every supercar and executive sedan is owned by AutoDrive, stored in climate-controlled hangars, and serviced by master technicians.
                </p>
            </div>

            <div class="glass-card rounded-2xl p-8 border border-white/10">
                <div class="w-12 h-12 rounded-xl bg-electric-500/20 text-electric-400 flex items-center justify-center mb-6">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">24/7 Concierge Support</h3>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Our clients receive 24/7 dedicated support, private airport tarmac delivery, child seats, and flexible customized return schedules.
                </p>
            </div>

            <div class="glass-card rounded-2xl p-8 border border-white/10">
                <div class="w-12 h-12 rounded-xl bg-electric-500/20 text-electric-400 flex items-center justify-center mb-6">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Seamless Sales Escrow</h3>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Acquiring a vehicle through our sales department is backed by rigorous title verification, transparent documentation fees, and verified funds reconciliation.
                </p>
            </div>
        </div>

        <!-- Terminal Showcase -->
        <div class="glass-card rounded-3xl p-8 sm:p-12 border border-white/10 grid grid-cols-1 lg:grid-cols-2 gap-8 items-center" id="inspection">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-electric-400 block mb-2">The Inspection Protocol</span>
                <h2 class="text-3xl font-extrabold text-white font-display mb-4">
                    Digital Check-In & Check-Out Guarantee
                </h2>
                <p class="text-slate-300 text-sm leading-relaxed mb-6">
                    Our system implements an immutable digital inspection ledger. When a vehicle is collected, the inspector records the odometer, fuel level, and pristine exterior condition. Upon return, a comprehensive inspection is conducted before the vehicle is returned to available inventory.
                </p>
                <div class="flex items-center gap-4 text-xs text-slate-400">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>Zero Surprise Charges</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-electric-400"></span>
                        <span>Real-Time Photos</span>
                    </div>
                </div>
            </div>
            <div>
                <img src="https://images.unsplash.com/photo-1541348263662-e0c8de4259ba?auto=format&fit=crop&w=800&q=80"
                     alt="Vehicle Inspection Perfection"
                     class="w-full h-72 object-cover rounded-2xl border border-white/10">
            </div>
        </div>
    </div>
@endsection
