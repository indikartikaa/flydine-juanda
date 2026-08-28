<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - FlyDine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif; background-color: #f8fafc; }
    </style>
</head>

<body class="text-slate-800 flex flex-col min-h-screen relative" x-data="{ mobileMenuOpen: false }">

    <!-- Header / Navigation -->
    <header class="sticky top-0 z-50 bg-white border-b border-slate-200 shadow-sm transition-all duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Logo & Location -->
                <a href="{{ route('customer.menu') }}" class="flex items-center space-x-3 group cursor-pointer">
                    <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-2xl bg-gradient-to-tr from-[#005ea2] to-blue-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight leading-none">FlyDine<span class="text-[#8dc63f]">.</span></h1>
                        <p class="text-[10px] sm:text-xs text-slate-500 font-bold tracking-widest uppercase mt-0.5">Juanda Airport</p>
                    </div>
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('customer.cart') }}" class="flex items-center space-x-2 text-sm font-bold text-slate-600 hover:text-[#005ea2] transition-colors group">
                        <span data-id="PESANAN" data-en="CART">PESANAN</span>
                    </a>

                    @if(session('order_code'))
                    <a href="{{ route('customer.tracking', ['order' => session('order_code')]) }}" class="flex items-center space-x-2 text-sm font-bold text-amber-500 hover:text-amber-600 transition-colors group bg-amber-50 px-3 py-1.5 rounded-full border border-amber-200 shadow-sm">
                        <span data-id="LACAK" data-en="TRACK">LACAK</span>
                    </a>
                    @endif

                    <div class="h-6 w-px bg-slate-200"></div>
                    <a href="{{ route('login') }}" class="flex items-center space-x-2 text-sm font-bold text-slate-600 hover:text-[#005ea2] transition-colors group">
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
                    <a href="{{ route('customer.cart') }}" class="flex items-center justify-center space-x-2 text-sm font-bold text-slate-700 bg-slate-50 py-3 rounded-xl hover:bg-slate-100">
                        <span data-id="PESANAN SAYA" data-en="MY CART">PESANAN SAYA</span>
                    </a>
                    <a href="{{ route('login') }}" class="flex items-center justify-center space-x-2 text-sm font-bold text-white bg-[#005ea2] py-3 rounded-xl hover:bg-blue-700">
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

    <!-- Header Top Section for Back Button & Title -->
    <div class="bg-gradient-to-r from-[#005ea2] to-blue-600 text-white pt-8 pb-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('customer.menu') }}" class="inline-flex items-center space-x-2 text-blue-100 hover:text-white transition-colors mb-6 font-medium text-sm bg-black/10 hover:bg-black/20 px-4 py-2 rounded-full w-fit">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span>Kembali ke Beranda</span>
            </a>
            
            @hasSection('page_header')
                @yield('page_header')
            @else
                <h1 class="text-3xl font-extrabold tracking-tight">@yield('title')</h1>
            @endif
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col relative container mx-auto px-4 sm:px-6 lg:px-8 -mt-8 mb-16">
        @yield('content')
    </main>

    <!-- FlyDine Custom Footer -->
    <footer class="bg-slate-100 border-t-4 border-[#005ea2] pt-16 mt-auto">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 mb-12 border-b border-slate-100 pb-12">
                <div class="lg:col-span-1">
                    <div class="flex items-center space-x-3 mb-6">
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
            </div>
            <div class="pb-safe py-6 flex flex-col md:flex-row items-center justify-between text-xs font-medium text-slate-400 gap-4 text-center md:text-left">
                <p>&copy; {{ date('Y') }} FlyDine Juanda. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>
