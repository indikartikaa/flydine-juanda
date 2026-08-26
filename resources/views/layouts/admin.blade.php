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
<body class="bg-gray-100 flex h-screen overflow-hidden">

    <!-- Sidebar Admin -->
    <aside class="w-64 bg-[#1e293b] text-white flex flex-col h-full shadow-lg z-20 shrink-0">
        
        <div class="p-6 border-b border-gray-700">
            <h2 class="text-2xl font-extrabold tracking-wider flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                FlyDine
            </h2>
            <p class="text-xs text-gray-400 mt-1.5 font-medium">System Administrator</p>
        </div>
        
        <!-- Menu Navigasi Admin -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            
            <a href="{{ url('/admin/dashboard') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->is('*admin/dashboard*') ? 'bg-gray-800 text-white font-semibold border-l-4 border-[#8dc63f] shadow-inner' : 'text-gray-400 hover:bg-gray-800 hover:text-white hover:pl-5 border-l-4 border-transparent' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                Dashboard
            </a>

            <a href="{{ url('/admin/tenants-management') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->is('*admin/tenants-management*') ? 'bg-gray-800 text-white font-semibold border-l-4 border-[#8dc63f] shadow-inner' : 'text-gray-400 hover:bg-gray-800 hover:text-white hover:pl-5 border-l-4 border-transparent' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                Manajemen Tenant
            </a>

            <a href="{{ url('/admin/complaints') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->is('*admin/complaints*') ? 'bg-gray-800 text-white font-semibold border-l-4 border-[#8dc63f] shadow-inner' : 'text-gray-400 hover:bg-gray-800 hover:text-white hover:pl-5 border-l-4 border-transparent' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Komplain Pelanggan
            </a>
            
        </nav>
        
        <div class="p-4 border-t border-gray-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all shadow-sm hover:shadow-md text-sm font-semibold group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden">
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 shrink-0 z-10 border-b border-gray-200">
            <h1 class="text-xl font-bold text-gray-800">@yield('title')</h1>
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-gray-600">Halo, <span class="text-[#005ea2] font-bold">Administrator</span></span>
                <div class="h-9 w-9 bg-[#005ea2] rounded-full flex items-center justify-center text-white font-bold border-2 border-white shadow-md">
                    AD
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </div>
    </main>

</body>
</html>