@props([
    'variant' => 'red',
    'class' => '',
    'alt' => 'AutoDrive — Premium Cars For Sale & Rent',
])

@if(in_array($variant, ['red', 'sports', 'nav', 'default']))
    <div {{ $attributes->merge(['class' => 'inline-flex flex-col items-center select-none group ' . $class]) }}>
        <!-- Red Aerodynamic Sports Car Silhouette -->
        <svg class="w-36 h-6 sm:h-7" viewBox="0 0 160 34" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="nav-red-grad" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#ff4444"/>
                    <stop offset="45%" stop-color="#ef4444"/>
                    <stop offset="100%" stop-color="#dc2626"/>
                </linearGradient>
                <linearGradient id="nav-red-accent" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#fca5a5"/>
                    <stop offset="100%" stop-color="#ef4444"/>
                </linearGradient>
            </defs>
            <!-- Sweeping roofline & coupe silhouette -->
            <path d="M 8 26 C 20 22, 36 15, 54 11 C 74 5, 96 4, 118 7 C 136 10, 148 16, 154 20"
                  stroke="url(#nav-red-grad)" stroke-width="2.8" stroke-linecap="round" />
            <!-- Inner cabin / windshield curve -->
            <path d="M 50 14 C 64 8, 86 6, 108 8 C 122 9, 134 13, 142 17"
                  stroke="url(#nav-red-accent)" stroke-width="2" stroke-linecap="round" />
            <!-- Front hood & bumper contour -->
            <path d="M 6 28 C 14 26, 26 23, 38 23 C 48 23, 56 25, 62 28"
                  stroke="url(#nav-red-grad)" stroke-width="2.2" stroke-linecap="round" />
            <!-- Rear spoiler flick -->
            <path d="M 134 20 C 142 19, 150 19, 156 22"
                  stroke="url(#nav-red-grad)" stroke-width="2.2" stroke-linecap="round" />
        </svg>

        <!-- Brand Name: AUTO (White) + DRIVE (Red) -->
        <div class="flex items-center text-[19px] sm:text-[21px] font-black tracking-wider font-display leading-none mt-0.5">
            <span class="text-white tracking-widest">AUTO</span><span class="text-[#ef4444] tracking-widest">DRIVE</span>
        </div>

        <!-- Subtitle: RENT • BUY • DRIVE -->
        <div class="flex items-center gap-1.5 text-[7.5px] sm:text-[8px] font-bold tracking-[0.28em] text-slate-300 uppercase mt-0.5">
            <span>RENT</span>
            <span class="text-[#ef4444] text-[6px]">•</span>
            <span>BUY</span>
            <span class="text-[#ef4444] text-[6px]">•</span>
            <span>DRIVE</span>
        </div>
    </div>
@elseif(in_array($variant, ['gold', 'luxury']))
    <div {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5 group select-none ' . $class]) }}>
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#f6cb82] via-[#e5a952] to-[#b37c2d] flex items-center justify-center p-1.5 shadow-md shadow-[#e5a952]/20 group-hover:scale-105 transition-transform">
            <svg class="w-full h-full text-slate-950 fill-current" viewBox="0 0 24 24">
                <path d="M12 2L2 22h4.5l2.2-5h6.6l2.2 5H22L12 2zm0 6l2.4 5.5H9.6L12 8z"/>
            </svg>
        </div>
        <div class="flex flex-col text-left">
            <span class="text-base font-extrabold tracking-wider text-white font-display leading-tight">AUTODRIVE</span>
            <span class="text-[7.5px] font-semibold tracking-widest text-[#e5a952] uppercase -mt-0.5">DRIVE YOUR AMBITION</span>
        </div>
    </div>
@elseif($variant === 'icon' || $variant === 'icon-gold')
    <div {{ $attributes->merge(['class' => 'w-8 h-8 rounded-lg bg-gradient-to-br from-[#f6cb82] via-[#e5a952] to-[#b37c2d] flex items-center justify-center p-1.5 shadow-md shadow-[#e5a952]/20 ' . $class]) }}>
        <svg class="w-full h-full text-slate-950 fill-current" viewBox="0 0 24 24">
            <path d="M12 2L2 22h4.5l2.2-5h6.6l2.2 5H22L12 2zm0 6l2.4 5.5H9.6L12 8z"/>
        </svg>
    </div>
@else
    @php
        $src = match ($variant) {
            'original', 'colored' => asset('images/logo.png'),
            'raw' => asset('images/logo-raw.png'),
            'electric', 'dark' => asset('images/logo-dark-theme.png'),
            'icon-dark' => asset('images/logo-icon-dark.png'),
            'white' => asset('images/logo-white.png'),
            default => asset('images/logo-dark-theme.png'),
        };
    @endphp
    <img src="{{ $src }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => $class]) }}>
@endif

