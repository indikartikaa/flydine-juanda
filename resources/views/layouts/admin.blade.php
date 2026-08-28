<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Portal') - FlyDine Juanda</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js CDN for interactive modals & tabs -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts: Plus Jakarta Sans & Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif; }
        /* Custom scrollbar for modern feel */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-800 flex h-full overflow-hidden antialiased">

    <!-- Sidebar Admin (Tema Clean & Bright Gojek/Traveloka Style) -->
    <aside class="w-64 bg-white border-r border-slate-200/80 flex flex-col h-full shadow-sm z-20 shrink-0">
        
        <!-- Logo Area -->
        <div class="h-20 flex items-center px-6 border-b border-slate-100">
            <a href="{{ url('/admin/dashboard') }}" class="flex items-center space-x-3 group">
                <div class="h-10 w-10 rounded-2xl bg-gradient-to-tr from-[#005ea2] to-blue-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-extrabold tracking-tight text-slate-900 leading-tight">
                        FlyDine<span class="text-[#8dc63f]">.</span>
                    </h2>
                    <p class="text-[11px] font-semibold text-slate-400 tracking-wide uppercase">Admin Portal</p>
                </div>
            </a>
        </div>
        
        <!-- Menu Navigasi -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Menu Utama</p>
            
            <a href="{{ url('/admin/dashboard') }}" 
               class="flex items-center px-4 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->is('*admin/dashboard*') ? 'bg-[#005ea2]/10 text-[#005ea2] font-bold shadow-sm ring-1 ring-[#005ea2]/20' : 'text-slate-600 hover:bg-slate-50 hover:text-[#005ea2]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 shrink-0 {{ request()->is('*admin/dashboard*') ? 'text-[#005ea2]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Dashboard
            </a>

            <a href="{{ url('/admin/tenants-management') }}" 
               class="flex items-center px-4 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->is('*admin/tenants-management*') ? 'bg-[#005ea2]/10 text-[#005ea2] font-bold shadow-sm ring-1 ring-[#005ea2]/20' : 'text-slate-600 hover:bg-slate-50 hover:text-[#005ea2]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 shrink-0 {{ request()->is('*admin/tenants-management*') ? 'text-[#005ea2]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Manajemen Tenant
            </a>

            <a href="{{ url('/admin/complaints') }}" 
               class="flex items-center justify-between px-4 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->is('*admin/complaints*') ? 'bg-[#005ea2]/10 text-[#005ea2] font-bold shadow-sm ring-1 ring-[#005ea2]/20' : 'text-slate-600 hover:bg-slate-50 hover:text-[#005ea2]' }}">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 shrink-0 {{ request()->is('*admin/complaints*') ? 'text-[#005ea2]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Komplain Pelanggan</span>
                </div>
                <span class="px-2 py-0.5 text-[11px] font-extrabold rounded-full bg-rose-500 text-white animate-pulse">2</span>
            </a>
            
        </nav>
        
        <!-- Area Logout (Tombol Halus Membulat) -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            <form method="POST" action="{{ route('logout') }}">
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
        
        <!-- Header Atas (Clean Glassmorphic) -->
        <header class="bg-white/90 backdrop-blur-md shadow-xs h-20 flex items-center justify-between px-8 shrink-0 z-10 border-b border-slate-200/80">
            <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">@yield('title')</h1>
            
            <div class="flex items-center space-x-5">
                <!-- Notification Bell -->
                <button class="relative p-2 text-slate-400 hover:text-[#005ea2] hover:bg-slate-100/80 rounded-xl transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-rose-500 ring-2 ring-white"></span>
                </button>

                <div class="h-6 w-px bg-slate-200"></div>

                <!-- Admin Profile Card -->
                <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 cursor-pointer group hover:bg-slate-50 p-1.5 pr-3 rounded-2xl transition-colors">
                    <div class="relative">
                        <div class="h-10 w-10 bg-gradient-to-tr from-[#005ea2] to-blue-600 rounded-2xl flex items-center justify-center text-white font-extrabold text-sm shadow-md shadow-blue-600/20 group-hover:scale-105 transition-transform">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 bg-[#8dc63f] border-2 border-white rounded-full"></span>
                    </div>
                    <div class="hidden sm:block text-left">
                        <span class="block text-xs font-bold text-slate-800 group-hover:text-[#005ea2] transition-colors">{{ auth()->user()->name }}</span>
                        <span class="block text-[11px] font-semibold text-slate-400">Admin Ops</span>
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