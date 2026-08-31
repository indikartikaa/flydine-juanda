<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tenant Portal') - FlyDine Juanda</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-800 flex h-full overflow-hidden antialiased">

    <!-- Sidebar Tenant -->
    <aside class="w-64 bg-white border-r border-slate-200/80 flex flex-col h-full shadow-sm z-20 shrink-0">
        
        <!-- Logo Area -->
        <div class="h-20 flex items-center px-6 border-b border-slate-100">
            <a href="{{ url('/tenant/dashboard') }}" class="flex items-center space-x-3 group">
                <div class="h-10 w-10 rounded-2xl bg-gradient-to-tr from-[#005ea2] to-blue-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-extrabold tracking-tight text-slate-900 leading-tight">
                        FlyDine<span class="text-[#8dc63f]">.</span>
                    </h2>
                    <p class="text-[11px] font-semibold text-slate-400 tracking-wide uppercase">Tenant Portal</p>
                </div>
            </a>
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
                <button type="submit" class="w-full flex items-center justify-center px-4 py-2.5 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 hover:border-rose-300 rounded-xl transition-all shadow-sm text-xs font-bold group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden bg-[#f8fafc]">
        
        <!-- Header Atas -->
        <header class="bg-white/90 backdrop-blur-md shadow-xs h-20 flex items-center justify-between px-8 shrink-0 z-10 border-b border-slate-200/80">
            <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">@yield('title')</h1>
            
            <div class="flex items-center space-x-5">
                <!-- Tenant Profile Card -->
                <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 cursor-pointer group hover:bg-slate-50 p-1.5 pr-3 rounded-2xl transition-colors">
                    <div class="relative">
                        <div class="h-10 w-10 bg-gradient-to-tr from-[#005ea2] to-blue-600 rounded-2xl flex items-center justify-center text-white font-extrabold text-sm shadow-md shadow-blue-600/20 group-hover:scale-105 transition-transform">
                            {{ auth()->user() ? strtoupper(substr(auth()->user()->name, 0, 2)) : 'TN' }}
                        </div>
                        <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 bg-[#8dc63f] border-2 border-white rounded-full"></span>
                    </div>
                    <div class="hidden sm:block text-left">
                        <span class="block text-xs font-bold text-slate-800 group-hover:text-[#005ea2] transition-colors">{{ auth()->user()->name ?? 'Tenant User' }}</span>
                        <span class="block text-[11px] font-semibold text-slate-400">Mitra FlyDine</span>
                    </div>
                </a>
            </div>
        </header>

        <!-- Area Konten Utama -->
        <div class="flex-1 overflow-y-auto p-6 md:p-8">
            @yield('content')
        </div>
    </main>

</body>
</html>