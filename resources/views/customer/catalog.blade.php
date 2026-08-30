<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlyDine - Juanda International Airport</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js untuk interaktivitas -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif; background-color: #f8fafc; }
        
        /* Kustomisasi Scrollbar agar rapi */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        
        .menu-scroll::-webkit-scrollbar { width: 4px; }
        .menu-scroll::-webkit-scrollbar-track { background: transparent; }
        .menu-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .menu-scroll:hover::-webkit-scrollbar-thumb { background: #94a3b8; }
        
        /* Safe area padding untuk mobile */
        @supports (padding-bottom: env(safe-area-inset-bottom)) {
            .pb-safe { padding-bottom: calc(env(safe-area-inset-bottom) + 1rem); }
        }
    </style>
    <script>
        function changeLanguage(lang) {
            document.querySelectorAll('[data-id]').forEach(function(element) {
                element.innerHTML = lang === 'en' ? element.getAttribute('data-en') : element.getAttribute('data-id');
            });
        }
    </script>
</head>

<body class="text-slate-800 flex flex-col min-h-screen selection:bg-[#005ea2] selection:text-white relative" x-data="{ mobileMenuOpen: false }">

    <!-- Header / Navigation -->
    <header class="sticky top-0 z-50 bg-white border-b border-slate-200 shadow-sm transition-all duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Logo & Location -->
                <div class="flex items-center space-x-3 group cursor-pointer">
                    <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-2xl bg-gradient-to-tr from-[#005ea2] to-blue-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight leading-none">FlyDine<span class="text-[#8dc63f]">.</span></h1>
                        <p class="text-[10px] sm:text-xs text-slate-500 font-bold tracking-widest uppercase mt-0.5">Juanda Airport</p>
                    </div>
                </div>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center space-x-6">
                    <div class="bg-slate-100/80 p-1 rounded-full flex items-center">
                        <button onclick="changeLanguage('id')" class="px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-white text-[#005ea2] shadow-sm">ID</button>
                        <button onclick="changeLanguage('en')" class="px-4 py-1.5 rounded-full text-xs font-bold transition-all text-slate-500 hover:text-slate-700">EN</button>
                    </div>
                    
                    <a href="{{ route('customer.cart') }}" class="flex items-center space-x-2 text-sm font-bold text-slate-600 hover:text-[#005ea2] transition-colors group">
                        <div class="relative">
                            @if(session('cart') && count(session('cart')) > 0)
                                <span class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-rose-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center">{{ count(session('cart')) }}</span>
                            @endif
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        </div>
                        <span data-id="PESANAN" data-en="CART">PESANAN</span>
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
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
            <div x-show="mobileMenuOpen" x-collapse class="md:hidden border-t border-slate-100 bg-white">
                <div class="py-4 space-y-4 px-2">
                    <div class="bg-slate-50 p-1.5 rounded-full flex items-center w-max mx-auto mb-4">
                        <button onclick="changeLanguage('id')" class="px-6 py-2 rounded-full text-xs font-bold transition-all bg-white text-[#005ea2] shadow-sm">ID</button>
                        <button onclick="changeLanguage('en')" class="px-6 py-2 rounded-full text-xs font-bold transition-all text-slate-500">EN</button>
                    </div>
                    
                    <a href="{{ route('customer.cart') }}" class="flex items-center justify-center space-x-2 text-sm font-bold text-slate-700 bg-slate-50 py-3 rounded-xl hover:bg-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        <span data-id="PESANAN SAYA" data-en="MY CART">PESANAN SAYA</span>
                    </a>
                    
                    <a href="{{ route('login') }}" class="flex items-center justify-center space-x-2 text-sm font-bold text-white bg-[#005ea2] py-3 rounded-xl hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                        <span data-id="PORTAL LOGIN TENANT" data-en="TENANT LOGIN PORTAL">PORTAL LOGIN TENANT</span>
                    </a>

                    <!-- Other Links -->
                    <div class="pt-4 mt-4 border-t border-slate-100 grid grid-cols-2 gap-3">
                        <a href="{{ route('page.cara-pesan') }}" class="text-xs font-semibold text-slate-600 hover:text-[#005ea2]">Cara Pesan</a>
                        <a href="{{ route('page.faq') }}" class="text-xs font-semibold text-slate-600 hover:text-[#005ea2]">Pusat Bantuan</a>
                        <a href="{{ route('page.terms') }}" class="text-xs font-semibold text-slate-600 hover:text-[#005ea2]">Syarat & Ketentuan</a>
                        <a href="{{ route('page.privacy') }}" class="text-xs font-semibold text-slate-600 hover:text-[#005ea2]">Kebijakan Privasi</a>
                        <a href="{{ route('page.daftar-tenant') }}" class="text-xs font-semibold text-slate-600 hover:text-[#005ea2]">Daftar Tenant</a>
                        <a href="{{ route('page.promosi') }}" class="text-xs font-semibold text-slate-600 hover:text-[#005ea2]">Promosi</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col relative">
        
        <!-- HD Hero Background -->
        <div class="absolute top-0 inset-x-0 h-[340px] md:h-[420px] -z-10 bg-cover bg-center bg-no-repeat" style="background-image: linear-gradient(to bottom, rgba(0,59,102,0.9), rgba(0,94,162,0.6), rgba(248,250,252,1)), url('{{ asset('images/juanda.jpg') }}');"></div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16">
            
            <!-- Hero Section -->
            <div class="text-center md:text-left mb-8 md:mb-14 text-white">
                <div class="inline-flex items-center space-x-2 bg-[#8dc63f]/20 border border-[#8dc63f]/30 px-3 py-1.5 rounded-full mb-5 backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-[#8dc63f] animate-pulse shadow-[0_0_8px_#8dc63f]"></span>
                    <span class="text-[10px] font-extrabold text-[#8dc63f] tracking-wider uppercase drop-shadow-md" data-id="DAPATKAN LOKASI TERBAIK" data-en="GET THE RIGHT LOCATION">Get The Right Location</span>
                </div>
                <h2 class="text-4xl md:text-6xl font-extrabold tracking-tight leading-[1.1] mb-4 drop-shadow-lg uppercase" data-id="Shopping &<br><span class='text-[#8dc63f]'>Dining</span>" data-en="Shopping &<br><span class='text-[#8dc63f]'>Dining</span>">
                    Shopping &<br><span class="text-[#8dc63f]">Dining</span>
                </h2>
                <p class="font-medium max-w-lg mx-auto md:mx-0 text-sm md:text-base text-blue-100 drop-shadow-md" data-id="Temukan hidangan favorit Anda sebelum penerbangan." data-en="Find your favorite meals before your flight.">Temukan hidangan favorit Anda sebelum penerbangan.</p>
            </div>

            <!-- Search & Filter (Floating Island) -->
            <div class="mb-10 sticky top-[72px] sm:top-24 z-40 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <form method="GET" action="{{ route('customer.menu') }}" class="bg-white/95 backdrop-blur-xl p-3 sm:p-2 rounded-3xl sm:rounded-full shadow-xl shadow-slate-200/50 border border-white flex flex-col md:flex-row md:items-center justify-between gap-3">
                    
                    <input type="hidden" name="terminal" id="terminalInput" value="{{ request('terminal', 'semua') }}">
                    
                    <!-- Chips / Pills Filter -->
                    <div class="flex space-x-2 overflow-x-auto scrollbar-hide pb-1 md:pb-0 md:pl-2 order-2 md:order-1">
                        @php $activeCategory = request('terminal', 'semua'); @endphp
                        <button type="button" onclick="document.getElementById('terminalInput').value='semua'; this.closest('form').submit();" class="px-5 py-2.5 rounded-full border text-sm transition-all whitespace-nowrap {{ $activeCategory === 'semua' ? 'bg-[#005ea2] text-white font-bold shadow-md shadow-blue-500/20 border-transparent' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100 font-medium' }}">
                            Semua Terminal
                        </button>
                        <button type="button" onclick="document.getElementById('terminalInput').value='t1'; this.closest('form').submit();" class="px-5 py-2.5 rounded-full border text-sm transition-all whitespace-nowrap flex items-center {{ $activeCategory === 't1' ? 'bg-[#005ea2] text-white font-bold shadow-md shadow-blue-500/20 border-transparent' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100 font-medium' }}">
                            <span class="w-2 h-2 rounded-full mr-2 {{ $activeCategory === 't1' ? 'bg-white' : 'bg-amber-400' }}"></span> Terminal 1
                        </button>
                        <button type="button" onclick="document.getElementById('terminalInput').value='t2'; this.closest('form').submit();" class="px-5 py-2.5 rounded-full border text-sm transition-all whitespace-nowrap flex items-center {{ $activeCategory === 't2' ? 'bg-[#005ea2] text-white font-bold shadow-md shadow-blue-500/20 border-transparent' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100 font-medium' }}">
                            <span class="w-2 h-2 rounded-full mr-2 {{ $activeCategory === 't2' ? 'bg-white' : 'bg-purple-400' }}"></span> Terminal 2
                        </button>
                    </div>

                    <!-- Search Bar -->
                    <div class="relative w-full md:max-w-xs lg:max-w-sm group order-1 md:order-2">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 group-focus-within:text-[#005ea2] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input name="search" value="{{ request('search') }}" oninput="clearTimeout(this.timer); this.timer = setTimeout(() => { this.closest('form').submit(); }, 500)" type="text" placeholder="Cari nama restoran..." class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-full text-sm font-medium focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-[#005ea2] focus:bg-white transition-all placeholder:text-slate-400">
                    </div>
                </form>
            </div>

            <!-- Tenant Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 pb-10">
                
                @forelse($tenants as $tenant)
                <div class="tenant-card bg-white rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex-col group">
                    
                    <!-- Cover Image Area -->
                    <div class="h-48 bg-gradient-to-br from-slate-100 to-slate-50 relative flex items-center justify-center p-6">
                        <!-- Pseudo-Logo -->
                        <div class="h-24 w-24 bg-white rounded-full shadow-sm flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                            <h2 class="text-4xl font-black text-[#005ea2] tracking-tighter">
                                {{ strtoupper(substr(str_replace([' ', "'"], '', $tenant->name), 0, 2)) }}
                            </h2>
                        </div>

                        <!-- Status Label -->
                        @php
                            $open = $tenant->isOpen();
                        @endphp
                        
                        @if($open)
                            <div class="absolute top-4 right-4 bg-white/95 text-emerald-600 text-[10px] font-extrabold px-3 py-1.5 rounded-full shadow-sm border border-emerald-100 tracking-wider flex items-center space-x-1.5 z-10">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> 
                                <span>OPEN</span>
                            </div>
                        @else
                            <div class="absolute top-4 right-4 bg-white/95 text-rose-500 text-[10px] font-extrabold px-3 py-1.5 rounded-full shadow-sm border border-rose-100 tracking-wider flex items-center space-x-1.5 z-10">
                                <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span> 
                                <span>CLOSED</span>
                            </div>
                            <!-- Grayscale Overlay when closed -->
                            <div class="absolute inset-0 bg-white/40 backdrop-grayscale z-0"></div>
                        @endif
                        
                        <!-- Location Badge -->
                        <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur text-slate-600 text-[10px] font-bold px-3 py-1.5 rounded-full shadow-sm border border-slate-100 tracking-wider flex items-center space-x-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-amber-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                            <span>T1 - {{ $tenant->floor_location ?? 'Lounge' }}</span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 sm:p-7 flex flex-col flex-grow bg-white">
                        <h3 class="font-extrabold text-xl text-slate-800 tracking-tight mb-4 group-hover:text-[#005ea2] transition-colors line-clamp-1">{{ $tenant->name }}</h3>
                        
                        <!-- Preview Menu -->
                        <div class="space-y-3 mb-6 flex-grow">
                            @forelse($tenant->products->take(3) as $product)
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-slate-500 font-medium truncate pr-3">{{ $product->name }}</span>
                                    <span class="font-bold text-slate-800 whitespace-nowrap">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                </div>
                            @empty
                                <div class="text-xs text-slate-400 font-medium italic py-3 bg-slate-50 rounded-xl border border-slate-100 text-center">
                                    <span data-id="Katalog menu belum tersedia." data-en="Menu catalog is not available.">Katalog menu belum tersedia.</span>
                                </div>
                            @endforelse
                            @if($tenant->products->count() > 3)
                                <p class="text-xs text-[#005ea2] font-bold pt-2">+ {{ $tenant->products->count() - 3 }} menu lainnya</p>
                            @endif
                        </div>

                        <!-- CTA Button -->
                        @if($open)
                            <a href="{{ route('customer.tenant.show', $tenant->id) }}" class="mt-auto w-full bg-[#005ea2] hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl text-sm transition-all shadow-md shadow-blue-500/20 hover:-translate-y-0.5 flex items-center justify-center group/btn relative z-10">
                                <span data-id="Lihat Menu & Pesan" data-en="View Menu & Order">Lihat Menu & Pesan</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 group-hover/btn:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </a>
                        @else
                            <div class="mt-auto w-full bg-slate-100 text-slate-400 font-bold py-3.5 rounded-xl text-sm flex items-center justify-center cursor-not-allowed relative z-10 border border-slate-200">
                                <span data-id="Restoran Tutup" data-en="Restaurant Closed">Restoran Tutup</span>
                            </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-full flex flex-col items-center justify-center py-20 bg-white border border-dashed border-slate-200 rounded-[2rem] empty-state">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    </div>
                    <p class="text-slate-500 font-bold text-sm" data-id="Tidak ada restoran yang sesuai pencarian." data-en="No restaurant matched your search.">Tidak ada restoran yang sesuai pencarian.</p>
                </div>
                @endforelse

            </div>

            <!-- Pagination Links -->
            <div class="pb-12">
                {{ $tenants->links() }}
            </div>
        </div>
    </main>

    <!-- FlyDine Custom Footer -->
    <footer class="bg-slate-100 border-t-4 border-[#005ea2] pt-16 mt-auto">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Top Footer Links & Newsletter -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 mb-12 border-b border-slate-100 pb-12">
                <div class="lg:col-span-1">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="h-10 w-10 rounded-2xl bg-gradient-to-tr from-[#005ea2] to-blue-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-black text-slate-800 tracking-tight">FlyDine<span class="text-[#8dc63f]">.</span></h2>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed font-medium mb-6">
                        Layanan pesan antar makanan eksklusif untuk penumpang di Bandara Internasional Juanda. Pesan langsung dari genggaman Anda.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="h-10 w-10 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center hover:bg-[#005ea2] hover:text-white transition-colors"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                        <a href="#" class="h-10 w-10 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center hover:bg-[#005ea2] hover:text-white transition-colors"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.7-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                        <a href="#" class="h-10 w-10 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center hover:bg-[#005ea2] hover:text-white transition-colors"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg></a>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-bold text-sm text-slate-800 mb-5 uppercase tracking-wider">Layanan Pelanggan</h4>
                    <ul class="space-y-3 text-sm font-medium text-slate-500">
                        <li><a href="{{ route('page.cara-pesan') }}" class="hover:text-[#005ea2] hover:underline transition-all">Cara Pesan</a></li>
                        <li><a href="{{ route('page.faq') }}" class="hover:text-[#005ea2] hover:underline transition-all">Pusat Bantuan (FAQ)</a></li>
                        <li><a href="{{ route('page.terms') }}" class="hover:text-[#005ea2] hover:underline transition-all">Syarat & Ketentuan</a></li>
                        <li><a href="{{ route('page.privacy') }}" class="hover:text-[#005ea2] hover:underline transition-all">Kebijakan Privasi</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-sm text-slate-800 mb-5 uppercase tracking-wider">Kemitraan</h4>
                    <ul class="space-y-3 text-sm font-medium text-slate-500">
                        <li><a href="{{ route('page.daftar-tenant') }}" class="hover:text-[#005ea2] hover:underline transition-all">Daftar Menjadi Tenant</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-[#005ea2] hover:underline transition-all text-[#005ea2] font-bold">Portal Login Tenant</a></li>
                        <li><a href="{{ route('page.promosi') }}" class="hover:text-[#005ea2] hover:underline transition-all">Promosi Kolaborasi</a></li>
                    </ul>
                </div>
                
                <div class="lg:col-span-1">
                    <h4 class="font-bold text-sm text-slate-800 mb-5 uppercase tracking-wider">Newsletter</h4>
                    <p class="text-xs mb-4 text-slate-500">Dapatkan update diskon dan menu terbaru langsung di email Anda.</p>
                    <form action="#" class="flex shadow-sm" onsubmit="event.preventDefault()">
                        <input type="email" placeholder="Email Anda" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-[#005ea2] rounded-l-xl" required>
                        <button type="submit" class="bg-[#005ea2] px-5 py-3 rounded-r-xl border border-[#005ea2] hover:bg-blue-700 transition-colors text-white font-bold">
                            Kirim
                        </button>
                    </form>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div class="pb-safe py-6 flex flex-col md:flex-row items-center justify-between text-xs font-medium text-slate-400 gap-4 text-center md:text-left">
                <p>&copy; {{ date('Y') }} FlyDine Juanda. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- End Main Content -->

</body>
</html>