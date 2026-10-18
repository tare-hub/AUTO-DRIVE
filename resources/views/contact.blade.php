@extends('layouts.app')

@section('title', 'Contact Concierge — AutoDrive')

@section('content')
    <div class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <span class="text-xs font-bold uppercase tracking-widest text-electric-400 block">Get in Touch</span>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-white font-display tracking-tight">
                Connect With Our Automotive Concierge
            </h1>
            <p class="text-slate-300 text-sm leading-relaxed">
                Whether inquiring about airport private terminal tarmac deliveries, corporate fleet partnerships, or bespoke acquisitions, our team is at your service.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Contact Info -->
            <div class="space-y-6">
                <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-electric-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Miami Flagship Terminal
                    </h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        100 Ocean Drive, Suite 500<br>
                        Miami Beach, FL 33139<br>
                        United States
                    </p>
                </div>

                <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-electric-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        Direct Lines
                    </h3>
                    <p class="text-xs text-slate-400 leading-relaxed space-y-1">
                        <span class="block"><strong class="text-white">VIP Hotline:</strong> +1 (800) 555-DRIVE</span>
                        <span class="block"><strong class="text-white">Acquisitions Desk:</strong> +1 (555) 017-7431</span>
                        <span class="block"><strong class="text-white">Direct Email:</strong> concierge@autodrive.com</span>
                    </p>
                </div>

                <div class="glass-card rounded-2xl p-6 border border-white/10 space-y-2">
                    <h3 class="text-lg font-bold text-white">Hours of Operation</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        <span class="block"><strong class="text-slate-300">Terminal Hangar:</strong> 7:00 AM – 11:00 PM EST</span>
                        <span class="block"><strong class="text-slate-300">Emergency Concierge:</strong> 24 Hours / 7 Days</span>
                    </p>
                </div>
            </div>

            <!-- Inquiry Form -->
            <div class="lg:col-span-2 glass-card rounded-2xl p-8 border border-white/10">
                <h3 class="text-xl font-bold text-white mb-2">Send an Official Message</h3>
                <p class="text-xs text-slate-400 mb-6">Our senior advisors respond promptly to all verified requests.</p>

                <form method="POST" action="{{ route('contact.submit') }}" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Full Name</label>
                            <input type="text" name="name" required value="{{ old('name', auth()->user()?->name) }}"
                                   class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Email Address</label>
                            <input type="email" name="email" required value="{{ old('email', auth()->user()?->email) }}"
                                   class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Subject / Topic</label>
                        <input type="text" name="subject" required value="{{ old('subject') }}" placeholder="e.g., Extended Luxury Charter Quote / Aircraft FBO Delivery"
                               class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-electric-400">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Your Inquiry</label>
                        <textarea name="message" rows="5" required
                                  class="w-full bg-carbon-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-electric-400"
                                  placeholder="Please provide trip dates, preferred models, or specific questions...">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="px-6 py-3 rounded-xl bg-electric-500 hover:bg-electric-400 text-white font-bold text-sm shadow-lg shadow-electric-500/25 transition">
                        Submit Inquiry
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
