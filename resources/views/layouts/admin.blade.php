<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Portal') - FlyDine Juanda</title>
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

    <!-- Sidebar Admin -->
    <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200/80 flex flex-col h-full shadow-2xl transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 lg:static lg:w-64 lg:shadow-sm shrink-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
        
        <!-- Logo Area (Besar & Prominen Sesuai Desain Asli) -->
        <div class="h-20 flex items-center justify-between px-6 border-b border-slate-200/80 shrink-0">
            <a href="{{ url('/admin/dashboard') }}" class="flex items-center group w-full justify-center">
                <img src="{{ asset('images/logo-flydine.png') }}" alt="FlyDine Logo" class="w-44 sm:w-48 object-contain group-hover:scale-105 transition-transform drop-shadow-sm">
            </a>
            <!-- Mobile Close Button -->
            <button type="button" 
                    @click="sidebarOpen = false" 
                    class="lg:hidden p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition cursor-pointer"
                    aria-label="Tutup Sidebar">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
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
                @php
                    $openComplaints = \App\Models\Complaint::where('status', 'open')->count();
                @endphp
                @if($openComplaints > 0)
                    <span class="px-2 py-0.5 text-[11px] font-extrabold rounded-full bg-rose-500 text-white animate-pulse">{{ $openComplaints }}</span>
                @endif
            </a>

            <a href="{{ url('/admin/fraud-monitoring') }}" 
               class="flex items-center justify-between px-4 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->is('*admin/fraud-monitoring*') ? 'bg-[#005ea2]/10 text-[#005ea2] font-bold shadow-sm ring-1 ring-[#005ea2]/20' : 'text-slate-600 hover:bg-slate-50 hover:text-[#005ea2]' }}">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 shrink-0 {{ request()->is('*admin/fraud-monitoring*') ? 'text-[#005ea2]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Fraud Monitoring</span>
                </div>
                @php
                    $blockedCount = \App\Models\Customer::where('is_blocked', true)->count();
                @endphp
                @if($blockedCount > 0)
                    <span class="px-2 py-0.5 text-[11px] font-extrabold rounded-full bg-amber-500 text-white animate-pulse">{{ $blockedCount }}</span>
                @endif
            </a>

            <!-- Submenu Accordion: Analitik Operasional -->
            @php
                $isAnalyticsActive = request()->routeIs('admin.executive-dashboard*') || request()->routeIs('admin.analytics.*');
                $filterParams = request()->only(['start_date', 'end_date', 'tenant_id']);
            @endphp
            <div x-data="{ open: {{ $isAnalyticsActive ? 'true' : 'false' }} }" class="space-y-1">
                <button type="button" 
                        @click="open = !open" 
                        class="w-full flex items-center justify-between px-4 py-3 rounded-xl font-semibold text-sm transition-all duration-200 cursor-pointer {{ $isAnalyticsActive ? 'bg-[#005ea2]/10 text-[#005ea2] font-bold shadow-sm ring-1 ring-[#005ea2]/20' : 'text-slate-600 hover:bg-slate-50 hover:text-[#005ea2]' }}">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 shrink-0 {{ $isAnalyticsActive ? 'text-[#005ea2]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Analitik Operasional</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-4 w-4 transition-transform duration-200 {{ $isAnalyticsActive ? 'text-[#005ea2] rotate-180' : 'text-slate-400' }}" 
                         :class="open ? 'rotate-180' : ''" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-cloak
                     x-show="open" 
                     style="{{ $isAnalyticsActive ? '' : 'display: none;' }}"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="pl-3 pr-1 py-1 space-y-1">
                    <!-- 1. Ringkasan Eksekutif -->
                    <a href="{{ route('admin.executive-dashboard', $filterParams) }}" 
                       class="flex items-center px-4 py-2.5 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.executive-dashboard*') ? 'bg-[#005ea2] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-2 {{ request()->routeIs('admin.executive-dashboard*') ? 'bg-white' : 'bg-slate-400' }}"></span>
                        <span>Ringkasan Eksekutif</span>
                    </a>

                    <!-- 2. Drill-Down Pesanan -->
                    <a href="{{ route('admin.analytics.drilldown', $filterParams) }}" 
                       class="flex items-center px-4 py-2.5 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.analytics.drilldown*') ? 'bg-[#005ea2] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-2 {{ request()->routeIs('admin.analytics.drilldown*') ? 'bg-white' : 'bg-slate-400' }}"></span>
                        <span>Drill-Down Pesanan</span>
                    </a>

                    <!-- 3. Simulasi What-If -->
                    <a href="{{ route('admin.analytics.whatif', $filterParams) }}" 
                       class="flex items-center px-4 py-2.5 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.analytics.whatif*') ? 'bg-[#005ea2] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-2 {{ request()->routeIs('admin.analytics.whatif*') ? 'bg-white' : 'bg-slate-400' }}"></span>
                        <span>Simulasi What-If</span>
                    </a>

                    <!-- 4. Segmentasi Pelanggan -->
                    <a href="{{ route('admin.analytics.customers', $filterParams) }}" 
                       class="flex items-center px-4 py-2.5 rounded-lg text-xs font-bold transition-all {{ request()->routeIs('admin.analytics.customers*') ? 'bg-[#005ea2] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-2 {{ request()->routeIs('admin.analytics.customers*') ? 'bg-white' : 'bg-slate-400' }}"></span>
                        <span>Segmentasi Pelanggan</span>
                    </a>
                </div>
            </div>
            
        </nav>
        
        <!-- Area Logout (Tombol Halus Membulat) -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sistem?');">
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
        
        <!-- Header Atas (Clean Glassmorphic) -->
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
                <!-- Admin Profile Card -->
                <a href="{{ route('profile.edit') }}" class="flex items-center space-x-2.5 sm:space-x-3 cursor-pointer group hover:bg-slate-50 p-1 sm:p-1.5 sm:pr-3 rounded-2xl transition-colors">
                    <div class="relative shrink-0">
                        <div class="h-9 w-9 sm:h-10 sm:w-10 bg-gradient-to-tr from-[#005ea2] to-blue-600 rounded-xl sm:rounded-2xl flex items-center justify-center text-white font-extrabold text-xs sm:text-sm shadow-md shadow-blue-600/20 group-hover:scale-105 transition-transform">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <span class="absolute -bottom-0.5 -right-0.5 h-2.5 w-2.5 sm:h-3 sm:w-3 bg-[#8dc63f] border-2 border-white rounded-full"></span>
                    </div>
                    <div class="hidden sm:block text-left">
                        <span class="block text-xs font-bold text-slate-800 group-hover:text-[#005ea2] transition-colors truncate max-w-[140px]">{{ auth()->user()->name }}</span>
                        <span class="block text-[11px] font-semibold text-slate-400">Admin Ops</span>
                    </div>
                </a>
            </div>
        </header>

        <!-- Area Konten Utama -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-6">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </div>
    </main>

    @stack('scripts')
</body>
</html>
