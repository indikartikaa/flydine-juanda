<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tenant Portal') - FlyDine Juanda</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-800 flex h-full overflow-hidden antialiased font-sans" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false" 
         x-cloak
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 lg:hidden transition-opacity" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"></div>

    <!-- Sidebar Tenant (Responsive Mobile Drawer & Desktop Static) -->
    <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200/80 flex flex-col h-full shadow-2xl transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 lg:static lg:w-64 lg:shadow-sm shrink-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
        
        <!-- Logo Area & Mobile Close Button (Presisi Sejajar Header Utama h-20) -->
        <div class="h-20 flex items-center justify-between px-6 border-b border-slate-200/80 shrink-0">
            <a href="{{ url('/tenant/dashboard') }}" class="flex items-center group w-full justify-center">
                <img src="{{ asset('images/logo-flydine.png') }}" alt="FlyDine Logo" class="w-44 sm:w-48 object-contain group-hover:scale-105 transition-transform drop-shadow-sm">
            </a>
            <!-- Mobile Close Button -->
            <button type="button" 
                    @click="sidebarOpen = false" 
                    class="lg:hidden p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition cursor-pointer"
                    aria-label="Tutup Menu">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <!-- Menu Navigasi -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Menu Utama</p>
            
            <a href="{{ url('/tenant/dashboard') }}" 
               class="flex items-center px-4 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->is('*tenant/dashboard*') || request()->is('tenant') ? 'bg-[#005ea2]/10 text-[#005ea2] font-bold shadow-sm ring-1 ring-[#005ea2]/20' : 'text-slate-600 hover:bg-slate-50 hover:text-[#005ea2]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 shrink-0 {{ request()->is('*tenant/dashboard*') || request()->is('tenant') ? 'text-[#005ea2]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Dashboard
            </a>

            <a href="{{ url('/tenant/orders') }}" 
               class="flex items-center px-4 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->is('tenant/orders') ? 'bg-[#005ea2]/10 text-[#005ea2] font-bold shadow-sm ring-1 ring-[#005ea2]/20' : 'text-slate-600 hover:bg-slate-50 hover:text-[#005ea2]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 shrink-0 {{ request()->is('tenant/orders') ? 'text-[#005ea2]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                Manajemen Pesanan
            </a>

            <a href="{{ url('/tenant/orders/history') }}" 
               class="flex items-center px-4 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->is('*tenant/orders/history*') ? 'bg-[#005ea2]/10 text-[#005ea2] font-bold shadow-sm ring-1 ring-[#005ea2]/20' : 'text-slate-600 hover:bg-slate-50 hover:text-[#005ea2]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 shrink-0 {{ request()->is('*tenant/orders/history*') ? 'text-[#005ea2]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Riwayat Pesanan
            </a>

            <a href="{{ url('/tenant/products') }}" 
               class="flex items-center px-4 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->is('*tenant/products*') ? 'bg-[#005ea2]/10 text-[#005ea2] font-bold shadow-sm ring-1 ring-[#005ea2]/20' : 'text-slate-600 hover:bg-slate-50 hover:text-[#005ea2]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 shrink-0 {{ request()->is('*tenant/products*') ? 'text-[#005ea2]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Katalog Produk
            </a>
            
        </nav>
        
        <!-- Area Logout -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            <form method="POST" action="{{ route('logout') ?? '#' }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center px-4 py-2.5 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 hover:border-rose-300 rounded-xl transition-all shadow-sm text-xs font-bold group cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden bg-[#f8fafc] min-w-0 w-full">
        
        <!-- Header Atas (Clean Glassmorphic, Sejajar Presisi h-20) -->
        <header class="bg-white/95 backdrop-blur-md shadow-xs h-20 flex items-center justify-between px-4 sm:px-6 lg:px-8 shrink-0 z-10 border-b border-slate-200/80">
            <div class="flex items-center gap-2.5 sm:gap-4 min-w-0 flex-1 mr-2">
                <!-- Hamburger Button on Mobile -->
                <button type="button" 
                        @click="sidebarOpen = true" 
                        class="lg:hidden p-2 -ml-1 rounded-xl text-slate-700 hover:text-slate-900 hover:bg-slate-100 active:bg-slate-200 transition cursor-pointer shrink-0"
                        aria-label="Buka Menu Navigasi">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h1 class="text-base sm:text-xl font-extrabold text-slate-900 tracking-tight truncate">@yield('title')</h1>
            </div>
            
            <div class="flex items-center space-x-2 sm:space-x-4 shrink-0">
                <!-- Tenant Profile Card -->
                <a href="{{ route('profile.edit') }}" class="flex items-center space-x-2.5 sm:space-x-3 cursor-pointer group hover:bg-slate-50 p-1 sm:p-1.5 sm:pr-3 rounded-2xl transition-colors">
                    <div class="relative shrink-0">
                        @if(auth()->user()?->tenant?->logo)
                            <img src="{{ asset(auth()->user()->tenant->logo) }}" alt="Avatar" class="h-9 w-9 sm:h-10 sm:w-10 rounded-xl sm:rounded-2xl object-cover shadow-sm border border-slate-200 group-hover:scale-105 transition-transform">
                        @else
                            <div class="h-9 w-9 sm:h-10 sm:w-10 bg-gradient-to-tr from-[#005ea2] to-blue-600 rounded-xl sm:rounded-2xl flex items-center justify-center text-white font-extrabold text-xs sm:text-sm shadow-md shadow-blue-600/20 group-hover:scale-105 transition-transform">
                                {{ auth()->user() ? strtoupper(substr(auth()->user()->name, 0, 2)) : 'TN' }}
                            </div>
                        @endif
                        <span class="absolute -bottom-0.5 -right-0.5 h-2.5 w-2.5 sm:h-3 sm:w-3 bg-[#8dc63f] border-2 border-white rounded-full"></span>
                    </div>
                    <div class="hidden sm:block text-left">
                        <span class="block text-xs font-bold text-slate-800 group-hover:text-[#005ea2] transition-colors truncate max-w-[140px]">{{ auth()->user()->tenant?->name ?? auth()->user()->name }}</span>
                        <span class="block text-[11px] font-semibold text-slate-400">Mitra FlyDine</span>
                    </div>
                </a>
            </div>
        </header>

        <!-- Area Konten Utama -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-6 md:p-8">
            @if(session('success'))
            <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 sm:px-5 sm:py-3.5 rounded-xl text-xs sm:text-sm font-bold flex items-center shadow-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 mr-2.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 sm:px-5 sm:py-3.5 rounded-xl text-xs sm:text-sm font-bold flex items-center shadow-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-600 mr-2.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            @if(isset($errors) && $errors->any())
            <div class="mb-5 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 sm:px-5 sm:py-3.5 rounded-xl text-xs sm:text-sm font-bold shadow-xs">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @yield('content')
        </div>
    </main>

</body>
</html>