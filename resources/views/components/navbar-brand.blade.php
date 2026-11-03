@props([
    'href' => route('customer.menu'),
    'sidebar' => false,
])

{{-- Juanda International Airport & FlyDine Co-Branding (Native HD SVG & Official Assets) --}}
<a href="{{ $href }}" 
   class="brand-banner-link relative inline-flex items-center flex-nowrap shrink-0 select-none focus:outline-none hover:opacity-95 transition-opacity pl-0 h-full self-stretch"
   title="Juanda International Airport by InJourney Airports &bull; FlyDine"
   aria-label="Juanda International Airport dan FlyDine">
    
    <!-- Authentic InJourney Corner Ribbon Waves (Pita Lengkungan Hijau & Biru di Pojok Kiri Atas) -->
    <div class="h-full {{ $sidebar ? 'w-8 shrink-0' : 'w-9 sm:w-16 md:w-20 lg:w-24' }} shrink-0 self-stretch relative overflow-hidden select-none pointer-events-none -mr-0.5 sm:mr-0">
        <svg viewBox="0 0 120 100" class="w-full h-full block" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <!-- Ribbon 3: Light Cyan Accent -->
            <path d="M0 0 L120 0 C96 18 56 52 20 100 L0 100 Z" fill="#6bc8f5" opacity="0.85" />
            <!-- Ribbon 1: InJourney Sky Blue -->
            <path d="M0 0 L88 0 C68 20 36 54 8 100 L0 100 Z" fill="#009be0" />
            <!-- Ribbon 2: InJourney Green -->
            <path d="M0 0 L48 0 C32 26 18 60 0 100 Z" fill="#60bb2d" />
        </svg>
    </div>

    <!-- Co-Branded Logos & Vertical Divider Container -->
    <div class="flex items-center {{ $sidebar ? 'space-x-2 pl-1.5' : 'space-x-2 sm:space-x-3 md:space-x-3.5 pl-1.5 sm:pl-2.5 md:pl-3' }} shrink-0 py-1">
        <!-- Juanda International Airport by InJourney Airports (HD Official Transparent) -->
        <img src="{{ asset('images/logo-juanda.png') }}" 
             alt="Juanda International Airport by InJourney Airports" 
             class="{{ $sidebar ? '' : 'h-[31px] sm:h-[clamp(34px,3.4vw,44px)]' }} w-auto object-contain block shrink-0 select-none"
             style="{{ $sidebar ? 'height: 25px;' : '' }}"
             width="1000"
             height="291"
             loading="eager"
             decoding="async">

        <!-- Vertical Divider -->
        <div class="{{ $sidebar ? 'h-5' : 'h-[18px] sm:h-7 md:h-8' }} w-px bg-slate-300/80 shrink-0"></div>

        <!-- FlyDine Official Logo (Proporsional & Seimbang dengan Juanda) -->
        <img src="{{ asset('images/logo-flydine.png') }}" 
             alt="FlyDine" 
             class="w-auto object-contain block shrink-0 select-none {{ $sidebar ? '-ml-1.5' : '-ml-1 sm:-ml-3.5 md:-ml-4' }} {{ $sidebar ? '' : 'h-[35px] sm:h-[clamp(54px,5.8vw,70px)]' }}"
             style="{{ $sidebar ? 'height: 38px;' : '' }}"
             width="1376"
             height="768"
             loading="eager"
             decoding="async">
    </div>
</a>
