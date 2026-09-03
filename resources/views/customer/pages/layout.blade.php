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
                <a href="{{ route('customer.menu') }}" class="group cursor-pointer">
                    <img src="{{ asset('images/logo-flydine.png') }}" alt="FlyDine Juanda Airport" class="h-16 sm:h-20 w-auto object-contain group-hover:scale-105 transition-transform duration-300">
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
    <div class="bg-slate-50 pt-8 pb-24 relative overflow-hidden">
        <!-- Subtle pattern -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNlMmU4ZjAiIGZpbGwtb3BhY2l0eT0iMC40Ii8+PC9zdmc+')] opacity-60"></div>
        <!-- Subtle gradients -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#005ea2]/5 rounded-full blur-3xl"></div>
        <div class="absolute top-12 -left-24 w-72 h-72 bg-[#8dc63f]/10 rounded-full blur-3xl"></div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <a href="{{ route('customer.menu') }}" class="inline-flex items-center space-x-2 text-slate-500 hover:text-[#005ea2] transition-colors mb-6 font-bold text-sm bg-white hover:bg-slate-100 border border-slate-200 px-4 py-2 rounded-full w-fit shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span>Kembali ke Beranda</span>
            </a>
            
            @hasSection('page_header')
                @yield('page_header')
            @else
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">@yield('title')</h1>
            @endif
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col relative container mx-auto px-4 sm:px-6 lg:px-8 -mt-8 mb-16">
        @yield('content')
    </main>

    <!-- FlyDine Custom Footer -->
    <x-footer />

</body>
</html>
