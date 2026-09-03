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

<body class="text-slate-800 flex flex-col min-h-screen selection:bg-[#005ea2] selection:text-white relative" x-data="{ mobileMenuOpen: false, lang: 'id' }">

    <!-- Header / Navigation -->
    <header class="sticky top-0 z-50 bg-white border-b border-slate-200 shadow-sm transition-all duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Logo & Location -->
                <a href="{{ route('customer.menu') }}" class="group cursor-pointer">
                    <img src="{{ asset('images/logo-flydine.png') }}" alt="FlyDine Juanda Airport" class="h-16 sm:h-20 w-auto object-contain group-hover:scale-105 transition-transform duration-300">
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center space-x-6">
                    <div class="bg-slate-100/80 p-1 rounded-full flex items-center">
                        <button @click="lang = 'id'; changeLanguage('id')" :class="lang === 'id' ? 'bg-white text-[#005ea2] shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-1.5 rounded-full text-xs font-bold transition-all">ID</button>
                        <button @click="lang = 'en'; changeLanguage('en')" :class="lang === 'en' ? 'bg-white text-[#005ea2] shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-1.5 rounded-full text-xs font-bold transition-all">EN</button>
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

                    <a href="{{ route('customer.history') }}" class="flex items-center space-x-2 text-sm font-bold text-slate-600 hover:text-[#005ea2] transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
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
                        <button @click="lang = 'id'; changeLanguage('id')" :class="lang === 'id' ? 'bg-white text-[#005ea2] shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-6 py-2 rounded-full text-xs font-bold transition-all">ID</button>
                        <button @click="lang = 'en'; changeLanguage('en')" :class="lang === 'en' ? 'bg-white text-[#005ea2] shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-6 py-2 rounded-full text-xs font-bold transition-all">EN</button>
                    </div>
                    
                    <a href="{{ route('customer.cart') }}" class="flex items-center justify-center space-x-2 text-sm font-bold text-slate-700 bg-slate-50 py-3 rounded-xl hover:bg-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        <span data-id="PESANAN SAYA" data-en="MY CART">PESANAN SAYA</span>
                    </a>
                    
                    <a href="{{ route('login') }}" class="flex items-center justify-center space-x-2 text-sm font-bold text-white bg-[#005ea2] py-3 rounded-xl hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                        <span data-id="PORTAL LOGIN TENANT" data-en="TENANT LOGIN PORTAL">PORTAL LOGIN TENANT</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col relative">
        
        <!-- HD Hero Background (Dari Desain Asli) -->
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
            <div class="mb-8 sticky top-[72px] sm:top-24 z-40 max-w-5xl mx-auto">
                <form method="GET" action="{{ route('customer.menu') }}" class="bg-white/95 backdrop-blur-xl p-3 sm:p-2 rounded-3xl sm:rounded-full shadow-xl shadow-slate-200/50 border border-white flex flex-col md:flex-row md:items-center justify-between gap-3">
                    
                    <input type="hidden" name="terminal" id="terminalInput" value="{{ request('terminal', 'semua') }}">
                    
                    <!-- Chips / Pills Filter (Terminal) -->
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
                <div class="tenant-card bg-white rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group relative">
                    
                    <!-- Cover Image Area -->
                    <div class="h-44 bg-gradient-to-br from-slate-100 to-slate-50 relative flex items-center justify-center p-6">
                        <!-- Pseudo-Logo -->
                        <div class="h-20 w-20 bg-white rounded-full shadow-sm flex items-center justify-center group-hover:scale-110 transition-transform duration-500 border border-slate-100">
                            <h2 class="text-3xl font-black text-[#005ea2] tracking-tighter">
                                {{ strtoupper(substr(str_replace([' ', "'"], '', $tenant->name), 0, 2)) }}
                            </h2>
                        </div>

                        <!-- Status Label -->
                        @php $open = $tenant->isOpen(); @endphp
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
                            <div class="absolute inset-0 bg-white/50 backdrop-grayscale z-0"></div>
                        @endif
                        
                        <!-- Location Badge -->
                        <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur text-slate-600 text-[10px] font-bold px-3 py-1.5 rounded-full shadow-sm border border-slate-100 tracking-wider flex items-center space-x-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#005ea2]" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                            <span>T1 - {{ $tenant->floor_location ?? 'Lounge' }}</span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 sm:p-7 flex flex-col flex-grow bg-white">
                        <h3 class="font-extrabold text-xl text-slate-800 tracking-tight group-hover:text-[#005ea2] transition-colors line-clamp-1">{{ $tenant->name }}</h3>
                        
                        <!-- KOMPONEN BARU: Rating & Estimasi Waktu ala Aplikasi Delivery -->
                        <div class="flex items-center space-x-3 mt-2 mb-5 text-xs font-bold text-slate-600">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-400 mr-1 pb-0.5" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                4.8
                            </div>
                            <div class="w-1 h-1 bg-slate-300 rounded-full"></div>
                            <div class="flex items-center text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                10 - 15 min
                            </div>
                            <div class="w-1 h-1 bg-slate-300 rounded-full"></div>
                            <div class="text-slate-400 font-medium">$$$</div>
                        </div>
                        
                        <!-- Preview Menu (Dari Desain Asli) -->
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
                            <a href="{{ route('customer.tenant.show', $tenant->id) }}" class="mt-auto w-full bg-white border-2 border-[#005ea2] text-[#005ea2] hover:bg-[#005ea2] hover:text-white font-bold py-3 rounded-xl text-sm transition-all flex items-center justify-center group/btn relative z-10">
                                <span data-id="Pilih Menu" data-en="Select Menu">Pilih Menu</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 group-hover/btn:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </a>
                        @else
                            <div class="mt-auto w-full bg-slate-50 text-slate-400 font-bold py-3 rounded-xl text-sm flex items-center justify-center cursor-not-allowed relative z-10 border border-slate-200">
                                <span data-id="Tutup" data-en="Closed">Tutup</span>
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

          <div class="pt-10 pb-2 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="text-center mb-8 relative">
                    <span class="text-xs font-extrabold tracking-[0.2em] text-slate-400 uppercase mb-2 block">Pilihan Tersedia</span>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight" data-id="Kategori Favorit" data-en="Favorite Cuisines">Kategori Favorit</h3>
                    <div class="w-12 h-1.5 bg-gradient-to-r from-[#005ea2] to-[#8dc63f] rounded-full mx-auto mt-4"></div>
                </div>
                
                <div class="flex overflow-x-auto scrollbar-hide space-x-4 sm:space-x-6 md:justify-center pb-4 px-4 -mx-4 snap-x snap-mandatory">
                    
                    <button type="button" class="flex flex-col items-center flex-shrink-0 group w-24 sm:w-28 outline-none focus:outline-none snap-start cursor-pointer" style="-webkit-tap-highlight-color: transparent;">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 shrink-0 aspect-square rounded-full p-[3px] bg-slate-100 group-hover:bg-gradient-to-tr group-hover:from-[#005ea2] group-hover:to-blue-400 group-active:scale-95 transition-all duration-300 shadow-sm group-hover:shadow-xl group-hover:shadow-blue-500/30 group-hover:-translate-y-1.5">
                            <div class="w-full h-full bg-white rounded-full p-1.5 relative overflow-hidden">
                                <img src="{{ asset('images/makanan_berat.jpg') }}" alt="Makanan Berat" class="w-full h-full object-cover rounded-full transition-transform duration-700 group-hover:scale-110">
                            </div>
                        </div>
                        <span class="mt-3 px-3 py-1.5 rounded-full text-[11px] sm:text-xs font-bold text-slate-600 bg-transparent group-hover:bg-blue-50 group-hover:text-[#005ea2] transition-all duration-300 text-center leading-snug">
                            Makanan Berat
                        </span>
                    </button>

                    <button type="button" class="flex flex-col items-center flex-shrink-0 group w-24 sm:w-28 outline-none focus:outline-none snap-start cursor-pointer" style="-webkit-tap-highlight-color: transparent;">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 shrink-0 aspect-square rounded-full p-[3px] bg-slate-100 group-hover:bg-gradient-to-tr group-hover:from-[#005ea2] group-hover:to-blue-400 group-active:scale-95 transition-all duration-300 shadow-sm group-hover:shadow-xl group-hover:shadow-blue-500/30 group-hover:-translate-y-1.5">
                            <div class="w-full h-full bg-white rounded-full p-1.5 relative overflow-hidden">
                                <img src="{{ asset('images/cepat_saji.jpg') }}" alt="Cepat Saji" class="w-full h-full object-cover rounded-full transition-transform duration-700 group-hover:scale-110">
                            </div>
                        </div>
                        <span class="mt-3 px-3 py-1.5 rounded-full text-[11px] sm:text-xs font-bold text-slate-600 bg-transparent group-hover:bg-blue-50 group-hover:text-[#005ea2] transition-all duration-300 text-center leading-snug">
                            Cepat Saji
                        </span>
                    </button>

                    <button type="button" class="flex flex-col items-center flex-shrink-0 group w-24 sm:w-28 outline-none focus:outline-none snap-start cursor-pointer" style="-webkit-tap-highlight-color: transparent;">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 shrink-0 aspect-square rounded-full p-[3px] bg-slate-100 group-hover:bg-gradient-to-tr group-hover:from-[#005ea2] group-hover:to-blue-400 group-active:scale-95 transition-all duration-300 shadow-sm group-hover:shadow-xl group-hover:shadow-blue-500/30 group-hover:-translate-y-1.5">
                            <div class="w-full h-full bg-white rounded-full p-1.5 relative overflow-hidden">
                                <img src="{{ asset('images/roti_kue.jpg') }}" alt="Roti & Kue" class="w-full h-full object-cover rounded-full transition-transform duration-700 group-hover:scale-110">
                            </div>
                        </div>
                        <span class="mt-3 px-3 py-1.5 rounded-full text-[11px] sm:text-xs font-bold text-slate-600 bg-transparent group-hover:bg-blue-50 group-hover:text-[#005ea2] transition-all duration-300 text-center leading-snug">
                            Roti & Kue
                        </span>
                    </button>

                    <button type="button" class="flex flex-col items-center flex-shrink-0 group w-24 sm:w-28 outline-none focus:outline-none snap-start cursor-pointer" style="-webkit-tap-highlight-color: transparent;">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 shrink-0 aspect-square rounded-full p-[3px] bg-slate-100 group-hover:bg-gradient-to-tr group-hover:from-[#005ea2] group-hover:to-blue-400 group-active:scale-95 transition-all duration-300 shadow-sm group-hover:shadow-xl group-hover:shadow-blue-500/30 group-hover:-translate-y-1.5">
                            <div class="w-full h-full bg-white rounded-full p-1.5 relative overflow-hidden">
                                <img src="{{ asset('images/minuman.jpg') }}" alt="Minuman" class="w-full h-full object-cover rounded-full transition-transform duration-700 group-hover:scale-110">
                            </div>
                        </div>
                        <span class="mt-3 px-3 py-1.5 rounded-full text-[11px] sm:text-xs font-bold text-slate-600 bg-transparent group-hover:bg-blue-50 group-hover:text-[#005ea2] transition-all duration-300 text-center leading-snug">
                            Minuman
                        </span>
                    </button>
                    
                </div>
            </div>
            
            <!-- SECTION: Cara Pesan (Modern Grid Layout - Jarak Dirapatkan) -->
            <div class="pt-10 pb-16 md:pb-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 border-t border-slate-100/60">
                
                <div class="text-center mb-12 max-w-3xl mx-auto">
                    <span class="text-[#005ea2] font-extrabold text-xs tracking-[0.2em] uppercase mb-4 block" data-id="PANDUAN" data-en="GUIDE">Panduan</span>
                    <h3 class="text-3xl md:text-4xl font-black text-slate-800 tracking-tight leading-tight" data-id="Cara Mudah Pesan Makanan" data-en="How to Order">Cara Mudah Pesan Makanan</h3>
                    <p class="text-slate-500 text-sm sm:text-base mt-4 font-medium leading-relaxed">Nikmati hidangan favorit Anda di Bandara Internasional Juanda tanpa perlu repot antre panjang. Ikuti langkah sederhana berikut.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-6">
                    <!-- Sisa kode card Langkah 1, 2, 3, 4 biarkan sama seperti sebelumnya -->
                    <!-- Step 1 -->
                    <div class="bg-white rounded-[2rem] p-7 shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-[#005ea2]/5 hover:-translate-y-2 transition-all duration-300 group relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                        <div class="w-12 h-12 bg-gradient-to-br from-[#005ea2] to-blue-600 rounded-2xl flex items-center justify-center text-white font-black text-xl shadow-md shadow-blue-500/20 mb-5 relative z-10">1</div>
                        <h4 class="text-lg font-extrabold text-slate-800 mb-2 group-hover:text-[#005ea2] transition-colors relative z-10">Pilih Lokasi</h4>
                        <p class="text-sm text-slate-500 leading-relaxed font-medium relative z-10">Pilih Terminal dan area keberangkatan Anda (contoh: Terminal 1) untuk menemukan restoran yang relevan.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="bg-white rounded-[2rem] p-7 shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-[#005ea2]/5 hover:-translate-y-2 transition-all duration-300 group relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                        <div class="w-12 h-12 bg-gradient-to-br from-[#005ea2] to-blue-600 rounded-2xl flex items-center justify-center text-white font-black text-xl shadow-md shadow-blue-500/20 mb-5 relative z-10">2</div>
                        <h4 class="text-lg font-extrabold text-slate-800 mb-2 group-hover:text-[#005ea2] transition-colors relative z-10">Tentukan Menu</h4>
                        <p class="text-sm text-slate-500 leading-relaxed font-medium mb-3 relative z-10">Jelajahi direktori dan pilih hidangan favorit Anda.</p>
                        <div class="inline-flex items-center space-x-1.5 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-100 relative z-10">
                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                            <span class="text-[9px] text-amber-700 font-extrabold uppercase tracking-wide">Maks 1 Restoran</span>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="bg-white rounded-[2rem] p-7 shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-[#005ea2]/5 hover:-translate-y-2 transition-all duration-300 group relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                        <div class="w-12 h-12 bg-gradient-to-br from-[#005ea2] to-blue-600 rounded-2xl flex items-center justify-center text-white font-black text-xl shadow-md shadow-blue-500/20 mb-5 relative z-10">3</div>
                        <h4 class="text-lg font-extrabold text-slate-800 mb-2 group-hover:text-[#005ea2] transition-colors relative z-10">Data Penerbangan</h4>
                        <p class="text-sm text-slate-500 leading-relaxed font-medium relative z-10">Masukkan Nomor Penerbangan dan Waktu Boarding agar pesanan Anda selesai tepat waktu.</p>
                    </div>

                    <!-- Step 4 -->
                    <div class="bg-white rounded-[2rem] p-7 shadow-sm border border-[#8dc63f]/30 hover:border-[#8dc63f] hover:shadow-xl hover:shadow-green-500/10 hover:-translate-y-2 transition-all duration-300 group relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 bg-green-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                        <div class="w-12 h-12 bg-gradient-to-br from-[#8dc63f] to-green-500 rounded-2xl flex items-center justify-center text-white font-black text-xl shadow-md shadow-green-500/30 mb-5 relative z-10 animate-pulse">4</div>
                        <h4 class="text-lg font-extrabold text-slate-800 mb-2 group-hover:text-[#8dc63f] transition-colors relative z-10">Lacak & Ambil</h4>
                        <p class="text-sm text-slate-500 leading-relaxed font-medium relative z-10">Lakukan pembayaran, pantau pesanan real-time, dan ambil di konter.</p>
                    </div>
                </div>
            </div>

            <x-footer />
    
</body>
</html>