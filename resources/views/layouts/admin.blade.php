<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Portal') - FlyDine Juanda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden">

    <!-- Sidebar Admin (Tema Clean & Bright) -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col h-full shadow-sm z-20 shrink-0">
        
        <!-- Logo Area -->
        <div class="h-16 flex items-center px-6 border-b border-gray-100">
            <h2 class="text-2xl font-extrabold tracking-wider flex items-center text-[#005ea2]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-2 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                FlyDine<span class="text-[#8dc63f]">.</span>
            </h2>
        </div>
        
        <!-- Menu Navigasi -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
            <p class="px-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3">Menu Utama</p>
            
            <a href="{{ url('/admin/dashboard') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->is('*admin/dashboard*') ? 'bg-blue-50 text-[#005ea2] font-bold shadow-sm ring-1 ring-blue-100' : 'text-gray-500 hover:bg-gray-50 hover:text-[#005ea2]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 shrink-0 {{ request()->is('*admin/dashboard*') ? 'text-[#005ea2]' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                Dashboard
            </a>

            <a href="{{ url('/admin/tenants-management') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->is('*admin/tenants-management*') ? 'bg-blue-50 text-[#005ea2] font-bold shadow-sm ring-1 ring-blue-100' : 'text-gray-500 hover:bg-gray-50 hover:text-[#005ea2]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 shrink-0 {{ request()->is('*admin/tenants-management*') ? 'text-[#005ea2]' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                Manajemen Tenant
            </a>

            <a href="{{ url('/admin/complaints') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->is('*admin/complaints*') ? 'bg-blue-50 text-[#005ea2] font-bold shadow-sm ring-1 ring-blue-100' : 'text-gray-500 hover:bg-gray-50 hover:text-[#005ea2]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 shrink-0 {{ request()->is('*admin/complaints*') ? 'text-[#005ea2]' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Komplain Pelanggan
            </a>
            
        </nav>
        
        <!-- Area Logout (Tombol Halus) -->
        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center px-4 py-2.5 bg-white border border-red-200 text-red-600 hover:bg-red-50 hover:border-red-300 rounded-lg transition-all shadow-sm text-sm font-bold group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden bg-[#f8fafc]">
        
        <!-- Header Atas -->
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 shrink-0 z-10 border-b border-gray-200">
            <h1 class="text-xl font-bold text-gray-800">@yield('title')</h1>
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-gray-600">Halo, <span class="text-[#005ea2] font-bold">Admin Ops</span></span>
                <div class="h-9 w-9 bg-gradient-to-tr from-[#005ea2] to-blue-400 rounded-full flex items-center justify-center text-white font-bold shadow-sm ring-2 ring-white">
                    AD
                </div>
            </div>
        </header>

        <!-- Area Konten Utama -->
        <div class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </div>
    </main>

</body>
</html>