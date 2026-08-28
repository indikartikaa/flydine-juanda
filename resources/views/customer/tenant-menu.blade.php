<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tenant->name }} - FlyDine</title>
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
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
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
                <a href="{{ route('customer.menu') }}" class="flex items-center space-x-3 group cursor-pointer">
                    <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-2xl bg-gradient-to-tr from-[#005ea2] to-blue-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight leading-none">Katalog<span class="text-[#8dc63f]">.</span></h1>
                        <p class="text-[10px] sm:text-xs text-slate-500 font-bold tracking-widest uppercase mt-0.5" data-id="KEMBALI KE BERANDA" data-en="BACK TO HOME">KEMBALI KE BERANDA</p>
                    </div>
                </a>

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
    <main class="flex-grow flex flex-col relative" x-data="{ searchQuery: '' }">
        
        <!-- Tenant Cover -->
        <div class="h-64 md:h-80 bg-gradient-to-br from-slate-200 to-slate-100 relative flex items-center justify-center overflow-hidden">
            <!-- Background pattern for elegance -->
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#005ea2 1px, transparent 1px); background-size: 20px 20px;"></div>
            
            <div class="absolute -bottom-16 w-32 h-32 md:w-40 md:h-40 bg-white rounded-3xl shadow-xl flex items-center justify-center transform rotate-3 border-4 border-white z-10 overflow-hidden">
                <div class="w-full h-full flex items-center justify-center bg-slate-50 transform -rotate-3">
                    <h2 class="text-4xl md:text-5xl font-black text-[#005ea2] tracking-tighter">
                        {{ strtoupper(substr(str_replace([' ', "'"], '', $tenant->name), 0, 2)) }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 pt-24 md:pt-28 pb-16">
            
            <!-- Tenant Info -->
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-slate-800 mb-4">{{ $tenant->name }}</h1>
                
                <div class="flex items-center justify-center space-x-3 mb-6">
                    <div class="bg-amber-100/80 text-amber-700 text-xs font-bold px-3 py-1.5 rounded-full flex items-center space-x-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                        <span>Terminal {{ substr(strtolower($tenant->floor_location ?? '1'), 0, 1) == '1' ? '1' : '2' }} - {{ $tenant->floor_location ?? 'Lounge' }}</span>
                    </div>
                    
                    @php
                        $open = false;
                        if($tenant->opening_time && $tenant->closing_time){
                            $now = now()->format('H:i:s');
                            $open = $now >= $tenant->opening_time && $now <= $tenant->closing_time;
                        }
                    @endphp
                    
                    @if($open)
                        <div class="bg-emerald-100/80 text-emerald-700 text-xs font-bold px-3 py-1.5 rounded-full flex items-center space-x-1.5">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                            <span data-id="BUKA" data-en="OPEN">BUKA</span>
                        </div>
                    @else
                        <div class="bg-rose-100/80 text-rose-700 text-xs font-bold px-3 py-1.5 rounded-full flex items-center space-x-1.5">
                            <span class="w-2 h-2 bg-rose-500 rounded-full"></span>
                            <span data-id="TUTUP" data-en="CLOSED">TUTUP</span>
                        </div>
                    @endif
                </div>

                @if($tenant->description)
                <p class="text-slate-500 font-medium leading-relaxed">{{ $tenant->description }}</p>
                @endif
            </div>

            <!-- Menu Search -->
            <div class="max-w-xl mx-auto mb-10 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 group-focus-within:text-[#005ea2] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input x-model="searchQuery" type="text" placeholder="Cari hidangan di restoran ini..." class="w-full pl-11 pr-4 py-3.5 bg-white shadow-sm border border-slate-200 rounded-full text-sm font-medium focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-[#005ea2] transition-all placeholder:text-slate-400">
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @forelse($tenant->products as $product)
                <div x-show="searchQuery === '' || '{{ strtolower(addslashes($product->name)) }}'.includes(searchQuery.toLowerCase())" 
                     class="bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 p-5 flex flex-col group relative overflow-hidden">
                    
                    @if($product->image)
                        <div class="h-40 bg-slate-100 rounded-2xl mb-5 overflow-hidden">
                            <!-- Image implementation would go here -->
                        </div>
                    @else
                        <!-- No Image Placeholder -->
                        <div class="h-32 bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl mb-5 flex items-center justify-center border border-slate-100/50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                    @endif

                    <h3 class="font-bold text-lg text-slate-800 tracking-tight mb-2 group-hover:text-[#005ea2] transition-colors">{{ $product->name }}</h3>
                    
                    @if($product->description)
                        <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6 flex-grow line-clamp-2">{{ $product->description }}</p>
                    @else
                        <div class="flex-grow mb-6"></div>
                    @endif

                    <div class="flex items-center justify-between mt-auto">
                        <div class="font-black text-xl text-slate-800 tracking-tight">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        <button onclick="addToCart({{ $product->id }}, {{ $tenant->id }})" class="bg-[#f8fafc] hover:bg-[#005ea2] text-[#005ea2] hover:text-white border border-slate-200 hover:border-transparent rounded-full h-10 w-10 flex items-center justify-center transition-all shadow-sm group/btn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover/btn:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                        </button>
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
            </div>
            
        </div>
    </main>

    <!-- FlyDine Custom Footer -->
    <footer class="bg-slate-100 border-t-4 border-[#005ea2] pt-16 mt-auto">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 mb-12 border-b border-slate-200 pb-12">
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
                    <form action="#" class="flex shadow-sm" onsubmit="event.preventDefault()">
                        <input type="email" placeholder="Email Anda" class="w-full px-4 py-3 bg-white border border-slate-200 text-slate-800 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-[#005ea2] rounded-l-xl" required>
                        <button type="submit" class="bg-[#005ea2] px-5 py-3 rounded-r-xl border border-[#005ea2] hover:bg-blue-700 transition-colors text-white font-bold">Kirim</button>
                    </form>
                </div>
            </div>

            <div class="pb-safe py-6 flex flex-col md:flex-row items-center justify-between text-xs font-medium text-slate-400 gap-4 text-center md:text-left">
                <p>&copy; {{ date('Y') }} FlyDine Juanda. All rights reserved.</p>
                <p>Designed for Juanda International Airport</p>
            </div>
        </div>
    </footer>

    <script>
        function addToCart(productId, tenantId, forceReplace = 0) {
            fetch('{{ route('customer.cart.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    tenant_id: tenantId,
                    force_replace: forceReplace
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error === 'conflict') {
                    if (confirm(data.message)) {
                        addToCart(productId, tenantId, 1);
                    }
                } else if (data.success) {
                    // Show a toast or simple alert
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan pada sistem.');
            });
        }
    </script>
</body>
</html>
