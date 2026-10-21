<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $tenant->name }} - FlyDine</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="text-slate-800 flex flex-col min-h-screen selection:bg-[#005ea2] selection:text-white relative" x-data="{ mobileMenuOpen: false, lang: 'id' }">

    <!-- Header / Navigation (Consistent with Catalog, Cart, & History) -->
    <header class="sticky top-0 z-50 bg-white border-b border-slate-200 shadow-sm transition-all duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Logo & Location -->
                <a href="{{ route('customer.menu') }}" class="group cursor-pointer">
                    <img src="{{ asset('images/logo-flydine.png') }}" alt="FlyDine Juanda Airport" class="w-40 sm:w-48 object-contain group-hover:scale-105 transition-transform duration-300 drop-shadow-sm">
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center space-x-6">
                    <div class="bg-slate-100/80 p-1 rounded-full flex items-center">
                        <button type="button" @click="lang = 'id'; changeLanguage('id')" :class="lang === 'id' ? 'bg-white text-[#005ea2] shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-1.5 rounded-full text-xs font-bold transition-all">ID</button>
                        <button type="button" @click="lang = 'en'; changeLanguage('en')" :class="lang === 'en' ? 'bg-white text-[#005ea2] shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-1.5 rounded-full text-xs font-bold transition-all">EN</button>
                    </div>
                    
                    <a href="{{ route('customer.cart') }}" class="flex items-center space-x-2 text-sm font-bold text-slate-600 hover:text-[#005ea2] transition-colors group">
                        <div class="relative" id="cart-icon-container">
                            <span id="cart-badge" class="{{ (session('cart') && count(session('cart')) > 0) ? 'flex' : 'hidden' }} absolute -top-1.5 -right-1.5 w-4 h-4 bg-rose-500 text-white text-[9px] font-bold rounded-full items-center justify-center transition-all duration-300">
                                {{ session('cart') ? count(session('cart')) : 0 }}
                            </span>
                            <svg id="cart-icon-svg" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        </div>
                        <span data-id="PESANAN" data-en="CART">PESANAN</span>
                    </a>

                    <a href="{{ route('customer.history') }}" class="flex items-center space-x-2 text-sm font-bold text-slate-600 hover:text-[#005ea2] transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        <span data-id="RIWAYAT" data-en="HISTORY">RIWAYAT</span>
                    </a>

                    @if(session('order_code'))
                    <a href="{{ route('customer.tracking', ['order' => session('order_code')]) }}" class="flex items-center space-x-2 text-sm font-bold text-amber-500 hover:text-amber-600 transition-colors group bg-amber-50 px-3 py-1.5 rounded-full border border-amber-200 shadow-sm">
                        <div class="relative">
                            <span class="absolute -top-1 -right-1 w-2 h-2 bg-amber-500 rounded-full animate-ping"></span>
                            <span class="absolute -top-1 -right-1 w-2 h-2 bg-amber-500 rounded-full"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <span data-id="LACAK" data-en="TRACK">LACAK</span>
                    </a>
                    @endif

                    <div class="h-6 w-px bg-slate-200"></div>
                    <a href="{{ route('login') }}" class="flex items-center space-x-2 text-sm font-bold text-slate-600 hover:text-[#005ea2] transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:translate-x-0.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                        <span data-id="PORTAL LOGIN" data-en="PORTAL LOGIN">PORTAL LOGIN</span>
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                    <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    <svg x-show="mobileMenuOpen" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            
            <!-- Mobile Menu Dropdown -->
            <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-slate-100 bg-white">
                <div class="py-4 space-y-3 px-2">
                    <div class="bg-slate-50 p-1.5 rounded-full flex items-center w-max mx-auto mb-3">
                        <button type="button" @click="lang = 'id'; changeLanguage('id')" :class="lang === 'id' ? 'bg-white text-[#005ea2] shadow-sm' : 'text-slate-500'" class="px-5 py-1.5 rounded-full text-xs font-bold transition-all">ID</button>
                        <button type="button" @click="lang = 'en'; changeLanguage('en')" :class="lang === 'en' ? 'bg-white text-[#005ea2] shadow-sm' : 'text-slate-500'" class="px-5 py-1.5 rounded-full text-xs font-bold transition-all">EN</button>
                    </div>

                    <a href="{{ route('customer.menu') }}" class="flex items-center space-x-2 text-sm font-bold text-slate-700 bg-slate-50 py-3 px-4 rounded-xl hover:bg-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        <span>KATALOG RESTORAN</span>
                    </a>
                    
                    <a href="{{ route('customer.cart') }}" class="flex items-center space-x-2 text-sm font-bold text-slate-700 bg-slate-50 py-3 px-4 rounded-xl hover:bg-slate-100">
                        <div class="relative">
                            <span id="mobile-cart-badge" class="{{ (session('cart') && count(session('cart')) > 0) ? 'flex' : 'hidden' }} absolute -top-1.5 -right-1.5 w-4 h-4 bg-rose-500 text-white text-[9px] font-bold rounded-full items-center justify-center transition-all duration-300">
                                {{ session('cart') ? count(session('cart')) : 0 }}
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        </div>
                        <span data-id="PESANAN SAYA" data-en="MY CART">PESANAN SAYA</span>
                    </a>

                    <a href="{{ route('customer.history') }}" class="flex items-center space-x-2 text-sm font-bold text-slate-700 bg-slate-50 py-3 px-4 rounded-xl hover:bg-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        <span>RIWAYAT & LACAK</span>
                    </a>
                    
                    <a href="{{ route('login') }}" class="flex items-center justify-center space-x-2 text-sm font-bold text-white bg-[#005ea2] py-3 rounded-xl hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                        <span data-id="PORTAL LOGIN TENANT" data-en="TENANT LOGIN PORTAL">PORTAL LOGIN TENANT</span>
                    </a>

                    <!-- Other Links -->
                    <div class="pt-3 border-t border-slate-100 grid grid-cols-2 gap-2 text-xs font-semibold text-slate-600">
                        <a href="{{ route('page.cara-pesan') }}" class="py-1 hover:text-[#005ea2]">Cara Pesan</a>
                        <a href="{{ route('page.faq') }}" class="py-1 hover:text-[#005ea2]">Pusat Bantuan</a>
                        <a href="{{ route('page.terms') }}" class="py-1 hover:text-[#005ea2]">Syarat & Ketentuan</a>
                        <a href="{{ route('page.privacy') }}" class="py-1 hover:text-[#005ea2]">Kebijakan Privasi</a>
                        <a href="{{ route('page.daftar-tenant') }}" class="py-1 hover:text-[#005ea2]">Daftar Tenant</a>
                        <a href="{{ route('page.promosi') }}" class="py-1 hover:text-[#005ea2]">Promosi</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Grand Tenant Hero Showcase -->
    <section class="relative bg-gradient-to-br from-[#072540] via-[#004a80] to-[#005ea2] text-white py-10 sm:py-14 overflow-hidden shadow-inner" style="background: linear-gradient(135deg, #072540 0%, #004a80 50%, #005ea2 100%);">
        <!-- Ambient Glow Elements -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-sky-400/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-[#8dc63f]/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute inset-0 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:24px_24px] opacity-10 pointer-events-none"></div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Back Button directly inside Hero -->
            <div class="mb-6">
                <a href="{{ route('customer.menu') }}" class="inline-flex items-center space-x-2 text-xs font-bold text-blue-100 hover:text-white bg-white/10 hover:bg-white/20 backdrop-blur-md px-4 py-2 rounded-full border border-white/20 transition-all shadow-sm group w-fit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span data-id="Kembali ke Katalog Restoran" data-en="Back to Restaurant Catalog">Kembali ke Katalog Restoran</span>
                </a>
            </div>

            <div class="flex flex-col md:flex-row items-center md:items-start gap-6 sm:gap-8">
                
                <!-- Tenant Avatar / Monogram Card -->
                <div class="w-28 h-28 sm:w-36 sm:h-36 rounded-3xl bg-white p-3 shadow-2xl ring-4 ring-white/15 shrink-0 flex items-center justify-center transform md:-rotate-1 hover:rotate-0 transition-transform duration-300">
                    <div class="w-full h-full rounded-2xl bg-gradient-to-tr from-slate-50 to-blue-50/50 flex flex-col items-center justify-center overflow-hidden border border-slate-100 relative">
                        @if($tenant->logo)
                            <img src="{{ asset($tenant->logo) }}" alt="{{ $tenant->name }}" class="w-full h-full object-contain p-1" loading="lazy" decoding="async">
                        @else
                            <span class="text-3xl sm:text-4xl font-black text-[#005ea2] tracking-tighter">{{ strtoupper(substr(str_replace([' ', "'"], '', $tenant->name), 0, 2)) }}</span>
                            <span class="text-[9px] font-extrabold text-[#8dc63f] uppercase tracking-widest mt-1">JUANDA</span>
                        @endif
                    </div>
                </div>

                <!-- Tenant Details & Badges -->
                <div class="flex-grow text-center md:text-left">
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 sm:gap-2.5 mb-3">
                        <!-- Terminal Badge -->
                        <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white/15 backdrop-blur-md border border-white/20 text-white shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-sky-300" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                            <span>{{ $tenant->terminal ?? 'T1' }} • {{ $tenant->floor_location ?? 'Area Komersial' }}</span>
                        </span>

                        <!-- Category Badge -->
                        <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white/15 backdrop-blur-md border border-white/20 text-blue-100 shadow-sm">
                            <span>🍽️ {{ $tenant->category ?? 'Fast Food & Restaurant' }}</span>
                        </span>

                        <!-- Live Status Badge -->
                        @php $open = $tenant->isOpen(); @endphp
                        @if($open)
                            <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-black bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                <span data-id="Buka Sekarang" data-en="Open Now">Buka Sekarang</span>
                            </span>
                        @else
                            <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-black bg-rose-500/20 text-rose-300 border border-rose-400/30">
                                <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                                <span data-id="Tutup Sementara" data-en="Temporarily Closed">Tutup Sementara</span>
                            </span>
                        @endif

                        <!-- SLA Guarantee Pill -->
                        <span class="inline-flex items-center space-x-1 px-3 py-1 rounded-full text-xs font-bold bg-amber-400/20 text-amber-200 border border-amber-300/30">
                            <span>⚡ Standar SLA ≤ 15 Menit</span>
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight text-white drop-shadow-md mb-2.5">
                        {{ $tenant->name }}
                    </h1>

                    <p class="text-blue-100/90 text-sm sm:text-base font-normal max-w-2xl leading-relaxed mx-auto md:mx-0 mb-4">
                        {{ $tenant->description ?: 'Nikmati sajian lezat dan segar dari ' . $tenant->name . ' di Terminal Bandara Internasional Juanda. Pesan lebih awal tanpa antre langsung untuk dinikmati sebelum jadwal penerbangan Anda.' }}
                    </p>

                    <!-- Compact Quick Meta Info -->
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 text-xs font-semibold text-blue-100/90 pt-3 border-t border-white/10">
                        <div class="flex items-center space-x-1.5">
                            <span class="text-sky-300">🍴</span>
                            <span>{{ $tenant->products->count() }} Pilihan Menu</span>
                        </div>
                        <span class="text-white/20 hidden sm:inline">&bull;</span>
                        <div class="flex items-center space-x-1.5">
                            <span class="text-amber-300">⏱️</span>
                            <span>Estimasi Siap 10–15 Mnt</span>
                        </div>
                        <span class="text-white/20 hidden sm:inline">&bull;</span>
                        <div class="flex items-center space-x-1.5">
                            <span class="text-emerald-300">🛍️</span>
                            <span>Ambil Cepat / Antar Gate</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col relative bg-slate-50/60 pb-20">
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-10">
            
            <!-- Controls Bar: Title & Dedicated Search Bar -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight" data-id="Daftar Menu" data-en="Menu List">Daftar Menu</h2>
                    <p class="text-xs sm:text-sm text-slate-400 font-medium">
                        <span data-id="Pilih hidangan untuk ditambahkan ke pesanan Anda" data-en="Select dishes to add to your order">Pilih hidangan untuk ditambahkan ke pesanan Anda</span>
                        <span id="menu-counter-text" class="text-slate-400"> • <strong id="menu-counter-num" class="text-[#005ea2] font-bold">{{ $tenant->products->count() }}</strong> menu tersedia</span>
                    </p>
                </div>

                <!-- Menu Search Form with Dedicated Search Button -->
                <div class="w-full sm:w-80 md:w-96 relative">
                    <form id="menu-search-form" onsubmit="event.preventDefault(); window.performMenuSearch(document.getElementById('menu-search-input').value); return false;" class="flex items-center bg-white rounded-full border border-slate-200 shadow-sm hover:border-[#005ea2]/50 focus-within:border-[#005ea2] focus-within:ring-4 focus-within:ring-blue-500/10 transition-all p-1">
                        <div class="relative flex-grow flex items-center pl-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 shrink-0 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input id="menu-search-input" 
                                   type="text" 
                                   placeholder="Cari menu..." 
                                   oninput="window.handleSearchInput(this.value)"
                                   class="w-full pl-2.5 pr-2 py-1.5 bg-transparent text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none placeholder:text-slate-400 placeholder:font-normal">
                            <!-- Clear Button -->
                            <button id="menu-search-clear-btn" 
                                    type="button" 
                                    onclick="window.clearMenuSearch()" 
                                    class="hidden p-1 text-slate-400 hover:text-slate-600 rounded-full transition-colors mr-1 cursor-pointer"
                                    aria-label="Hapus pencarian">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <!-- Tombol Pencarian / Search Button -->
                        <button id="menu-search-btn"
                                type="button" 
                                onclick="window.performMenuSearch(document.getElementById('menu-search-input').value)" 
                                class="bg-gradient-to-r from-[#005ea2] to-blue-600 hover:from-blue-700 hover:to-[#005ea2] text-white px-4 py-2 rounded-full font-bold text-xs flex items-center space-x-1.5 shadow-sm shadow-blue-500/20 active:scale-95 transition-all cursor-pointer shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span data-id="Cari" data-en="Search">Cari</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Products Grid with Prepared Frame for Tenant Uploads -->
            <div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-7">
                @forelse($tenant->products as $product)
                @php
                    $hasProductImage = false;
                    $productImageUrl = null;
                    if (!empty($product->image)) {
                        if (\Illuminate\Support\Str::startsWith($product->image, ['http://', 'https://'])) {
                            $hasProductImage = true;
                            $productImageUrl = $product->image;
                        } elseif (\Illuminate\Support\Str::startsWith($product->image, 'images/')) {
                            if (file_exists(public_path($product->image))) {
                                $hasProductImage = true;
                                $productImageUrl = asset($product->image);
                            }
                        } else {
                            if (file_exists(public_path('storage/' . $product->image)) || file_exists(storage_path('app/public/' . $product->image))) {
                                $hasProductImage = true;
                                $productImageUrl = asset('storage/' . $product->image);
                            }
                        }
                    }
                    $isAvailable = $product->is_available && $product->stock > 0;
                    $isLowStock = $isAvailable && $product->stock <= 5;
                @endphp
                <div class="menu-item-card bg-white rounded-[1.75rem] border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-blue-300 transition-all duration-300 p-4 sm:p-5 flex flex-col group relative overflow-hidden {{ !$isAvailable ? 'opacity-85 bg-slate-50/40' : '' }}"
                     data-name="{{ strtolower($product->name) }}"
                     data-desc="{{ strtolower($product->description ?? '') }}"
                     data-category="{{ strtolower($product->category ?? '') }}">
                    
                    <!-- Dedicated Image Frame (Fits Uploaded Images or Clean Frame Placeholder) -->
                    <div class="h-44 sm:h-48 rounded-2xl overflow-hidden relative mb-4 bg-slate-50 border border-slate-100 flex items-center justify-center">
                        @if(!$isAvailable)
                            <!-- High-Contrast Out-of-Stock Center Overlay -->
                            <div class="absolute inset-0 z-30 flex flex-col items-center justify-center p-3 text-center" style="background-color: rgba(15, 23, 42, 0.45); backdrop-filter: blur(2px);">
                                <div class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-full shadow-lg border border-white/30" style="background-color: #dc2626; color: #ffffff;">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                    <span class="text-xs font-black uppercase tracking-wider" data-id="Stok Habis" data-en="Sold Out">Stok Habis</span>
                                </div>
                            </div>
                        @elseif($isLowStock)
                            <!-- Floating Low Stock Warning Badge -->
                            <div class="absolute top-3 left-3 z-20 inline-flex items-center space-x-1 px-2.5 py-1 rounded-full shadow-md" style="background-color: #d97706; color: #ffffff;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <span class="text-[10px] font-bold tracking-wide">Sisa {{ $product->stock }} porsi</span>
                            </div>
                        @endif

                        @if($hasProductImage)
                            <img src="{{ $productImageUrl }}" 
                                 alt="{{ $product->name }}" 
                                 loading="lazy" 
                                 onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.classList.remove('hidden');" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 {{ !$isAvailable ? 'grayscale opacity-60' : '' }}">
                            <!-- Graceful fallback if image fails to load -->
                            <div class="hidden w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100/70 text-slate-300">
                                <div class="w-12 h-12 rounded-2xl bg-white shadow-xs border border-slate-200/60 flex items-center justify-center text-slate-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="text-[10px] font-semibold text-slate-400 mt-2 uppercase tracking-wider">Foto Belum Ditambahkan</span>
                            </div>
                        @else
                            <!-- Clean Neutral Empty Frame Ready for Tenant Upload -->
                            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100/70 text-slate-300">
                                <div class="w-12 h-12 rounded-2xl bg-white shadow-xs border border-slate-200/60 flex items-center justify-center text-slate-300 group-hover:text-slate-400 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="text-[10px] font-semibold text-slate-400 mt-2 uppercase tracking-wider">Foto Belum Ditambahkan</span>
                            </div>
                        @endif
                    </div>

                    <!-- Title & Optional Description -->
                    <h3 class="font-extrabold text-base sm:text-lg text-slate-900 tracking-tight mb-1.5 group-hover:text-[#005ea2] transition-colors line-clamp-1 {{ !$isAvailable ? 'text-slate-400' : '' }}">
                        {{ $product->name }}
                    </h3>
                    
                    @if($product->description)
                        <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed mb-4 flex-grow line-clamp-2">
                            {{ $product->description }}
                        </p>
                    @else
                        <div class="flex-grow mb-4"></div>
                    @endif

                    <!-- Price & Action Button -->
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100 mt-auto">
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Harga</span>
                            <span class="font-black text-lg sm:text-xl {{ $isAvailable ? 'text-[#005ea2]' : 'text-slate-400 line-through decoration-slate-300' }} tracking-tight">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                        
                        @if($isAvailable)
                            <button type="button" 
                                    onclick="addToCart({{ $product->id }}, {{ $tenant->id }}, this)" 
                                    aria-label="Tambah {{ $product->name }}" 
                                    class="add-to-cart-btn inline-flex items-center space-x-1.5 bg-[#005ea2] hover:bg-blue-700 text-white font-extrabold text-xs px-4 py-2.5 rounded-xl shadow-md shadow-blue-500/20 active:scale-95 transition-all cursor-pointer group/btn">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover/btn:scale-110 transition-transform pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="pointer-events-none">Pesan</span>
                            </button>
                        @else
                            <button type="button" 
                                    disabled 
                                    class="inline-flex items-center space-x-1.5 font-black text-xs px-4 py-2.5 rounded-xl cursor-not-allowed border shadow-none"
                                    style="background-color: #fff1f2; color: #dc2626; border-color: #fecdd3;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                <span data-id="Stok Habis" data-en="Sold Out">Stok Habis</span>
                            </button>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-full flex flex-col items-center justify-center py-24 bg-white border border-dashed border-slate-200 rounded-[2rem]">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    </div>
                    <p class="text-slate-500 font-bold text-sm" data-id="Belum ada menu yang tersedia." data-en="No menu available yet.">Belum ada menu yang tersedia.</p>
                </div>
                @endforelse

                <!-- Search No-Result State -->
                <div id="search-no-result" class="hidden col-span-full flex flex-col items-center justify-center py-16 px-6 bg-white border border-dashed border-slate-200 rounded-[2rem] text-center shadow-xs">
                    <div class="w-16 h-16 bg-blue-50 text-[#005ea2] rounded-2xl flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h4 class="text-base font-extrabold text-slate-800 mb-1" data-id="Menu Tidak Ditemukan" data-en="Menu Not Found">Menu Tidak Ditemukan</h4>
                    <p class="text-xs sm:text-sm text-slate-400 max-w-md mx-auto mb-5">
                        <span data-id="Tidak ada menu yang sesuai dengan kata kunci" data-en="No menu matches the search query">Tidak ada menu yang sesuai dengan kata kunci</span> &ldquo;<strong id="search-query-text" class="text-slate-700"></strong>&rdquo;. <span data-id="Silakan coba kata kunci lain." data-en="Please try another keyword.">Silakan coba kata kunci lain.</span>
                    </p>
                    <button type="button" 
                            onclick="window.clearMenuSearch()" 
                            class="inline-flex items-center space-x-1.5 bg-[#005ea2] hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm transition-all cursor-pointer">
                        <span data-id="Tampilkan Semua Menu" data-en="Show All Menus">Tampilkan Semua Menu</span>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- FlyDine Corporate Footer (Standard Component) -->
    <x-footer />

    <!-- Toast Notification (Pesanan Ditambahkan) -->
    <div id="cart-toast" class="fixed bottom-6 right-6 z-50 transform translate-y-28 opacity-0 transition-all duration-500 ease-out pointer-events-none max-w-sm w-full px-4 sm:px-0">
        <div class="bg-slate-900/95 backdrop-blur-md text-white p-4 rounded-2xl shadow-2xl border border-slate-700/60 flex items-center justify-between pointer-events-auto">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-full bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-emerald-400 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-white">Menu Berhasil Ditambahkan!</p>
                    <p id="toast-message" class="text-[11px] text-slate-300 truncate max-w-[180px]">Item masuk ke pesanan</p>
                </div>
            </div>
            <a href="{{ route('customer.cart') }}" class="ml-3 bg-gradient-to-r from-[#005ea2] to-blue-600 hover:from-blue-700 hover:to-[#005ea2] text-white text-xs font-bold px-3.5 py-2 rounded-xl shadow-md transition-all flex items-center space-x-1 shrink-0 group">
                <span>Pesanan</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
            </a>
        </div>
    </div>

    <script nonce="{{ $cspNonce }}">
        let toastTimeout;

        // Menu Real-Time Search & Filter Engine
        window.handleSearchInput = function(value) {
            const clearBtn = document.getElementById('menu-search-clear-btn');
            if (clearBtn) {
                if (value && value.trim().length > 0) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                }
            }
            window.performMenuSearch(value);
        };

        window.performMenuSearch = function(query) {
            const rawQuery = (query || '').trim();
            const q = rawQuery.toLowerCase();
            let count = 0;
            const cards = document.querySelectorAll('.menu-item-card');
            
            cards.forEach(card => {
                const name = (card.getAttribute('data-name') || '').toLowerCase();
                const desc = (card.getAttribute('data-desc') || '').toLowerCase();
                const cat = (card.getAttribute('data-category') || '').toLowerCase();
                
                const matches = !q || name.includes(q) || desc.includes(q) || cat.includes(q);
                if (matches) {
                    card.style.display = '';
                    count++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Update Counter
            const counterNum = document.getElementById('menu-counter-num');
            if (counterNum) {
                counterNum.textContent = count;
            }

            // Empty State
            const noResult = document.getElementById('search-no-result');
            const querySpan = document.getElementById('search-query-text');
            if (noResult) {
                if (count === 0 && q !== '') {
                    if (querySpan) querySpan.textContent = rawQuery;
                    noResult.classList.remove('hidden');
                } else {
                    noResult.classList.add('hidden');
                }
            }
        };

        window.clearMenuSearch = function() {
            const input = document.getElementById('menu-search-input');
            if (input) {
                input.value = '';
                input.focus();
            }
            const clearBtn = document.getElementById('menu-search-clear-btn');
            if (clearBtn) clearBtn.classList.add('hidden');
            window.performMenuSearch('');
        };

        function addToCart(productId, tenantId, btnElement = null, forceReplace = 0) {
            let originalContent = '';
            if (btnElement) {
                originalContent = btnElement.innerHTML;
                btnElement.disabled = true;
                btnElement.classList.add('scale-75');
            }

            fetch('{{ route('customer.cart.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    tenant_id: tenantId,
                    force_replace: forceReplace
                })
            })
            .then(async response => {
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    if (response.status === 419) {
                        alert('Sesi telah kedaluwarsa. Silakan muat ulang (refresh) halaman.');
                        window.location.reload();
                        return null;
                    }
                    if (response.status === 409 || data.error === 'conflict') {
                        if (confirm(data.message || 'Anda memiliki pesanan dari restoran lain. Ingin mengganti restoran?')) {
                            addToCart(productId, tenantId, btnElement, 1);
                        }
                        return null;
                    }
                    throw new Error(data.message || 'Terjadi kesalahan pada sistem. Silakan coba lagi.');
                }
                return data;
            })
            .then(data => {
                if (!data) return;

                if (btnElement) {
                    btnElement.disabled = false;
                    btnElement.classList.remove('scale-75');
                }

                if (data.error === 'conflict') {
                    if (confirm(data.message)) {
                        addToCart(productId, tenantId, btnElement, 1);
                    }
                } else if (data.success) {
                    // 1. Button Success Animation (Morph to green checkmark)
                    if (btnElement) {
                        btnElement.classList.remove('bg-[#f8fafc]', 'text-[#005ea2]', 'border-slate-200');
                        btnElement.classList.add('bg-emerald-500', 'text-white', 'border-emerald-500', 'scale-110');
                        btnElement.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>`;

                        setTimeout(() => {
                            btnElement.classList.remove('bg-emerald-500', 'text-white', 'border-emerald-500', 'scale-110');
                            btnElement.classList.add('bg-[#f8fafc]', 'text-[#005ea2]', 'border-slate-200');
                            btnElement.innerHTML = originalContent;
                        }, 1200);
                    }

                    // 2. Update Cart Badge Count in Header
                    updateCartBadge(data.cart_count);

                    // 3. Header Cart Icon Bounce Animation
                    animateCartIcon();

                    // 4. Show Floating Toast Notification
                    showCartToast(data.message || 'Menu ditambahkan ke pesanan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (btnElement) {
                    btnElement.disabled = false;
                    btnElement.classList.remove('scale-75');
                    btnElement.innerHTML = originalContent;
                }
                alert(error.message || 'Terjadi kesalahan pada sistem. Silakan coba lagi.');
            });
        }

        function updateCartBadge(count) {
            const desktopBadge = document.getElementById('cart-badge');
            const mobileBadge = document.getElementById('mobile-cart-badge');

            [desktopBadge, mobileBadge].forEach(badge => {
                if (badge) {
                    badge.textContent = count;
                    if (count > 0) {
                        badge.classList.remove('hidden');
                        badge.classList.add('flex');
                    } else {
                        badge.classList.add('hidden');
                        badge.classList.remove('flex');
                    }
                }
            });
        }

        function animateCartIcon() {
            const iconSvg = document.getElementById('cart-icon-svg');
            const badge = document.getElementById('cart-badge');

            if (iconSvg) {
                iconSvg.classList.add('scale-125', 'text-[#005ea2]');
                setTimeout(() => {
                    iconSvg.classList.remove('scale-125', 'text-[#005ea2]');
                }, 350);
            }
            if (badge) {
                badge.classList.add('scale-150');
                setTimeout(() => {
                    badge.classList.remove('scale-150');
                }, 350);
            }
        }

        function showCartToast(message) {
            const toast = document.getElementById('cart-toast');
            const toastMessage = document.getElementById('toast-message');
            if (!toast) return;

            if (toastMessage && message) {
                toastMessage.textContent = message;
            }

            clearTimeout(toastTimeout);
            toast.classList.remove('translate-y-28', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');

            toastTimeout = setTimeout(() => {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('translate-y-28', 'opacity-0');
            }, 3000);
        }
    </script>
</body>
</html>
