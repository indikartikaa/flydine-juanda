<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tenant Portal') - FlyDine Juanda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden">

    <!-- Sidebar Reusable -->
    <aside class="w-64 bg-[#005ea2] text-white flex flex-col h-full shadow-lg z-20">
        <div class="p-6 border-b border-blue-700">
            <h2 class="text-2xl font-extrabold tracking-wider">FlyDine</h2>
            <p class="text-xs text-blue-200 mt-1">Tenant Partner Portal</p>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="/tenant/dashboard" class="flex items-center px-4 py-3 hover:bg-blue-800 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                Dashboard
            </a>
            <a href="/tenant/orders" class="flex items-center px-4 py-3 hover:bg-blue-800 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                Manajemen Pesanan
            </a>
            <a href="/tenant/products" class="flex items-center px-4 py-3 hover:bg-blue-800 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                Katalog Produk
            </a>
        </nav>

        <div class="p-4 border-t border-blue-700">
            <button class="w-full flex items-center justify-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded transition-colors text-sm font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                Logout
            </button>
        </div>
    </aside>

    <!-- Main Content Area Reusable -->
    <main class="flex-1 flex flex-col h-full overflow-hidden">
        <!-- Top Navbar -->
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 shrink-0 z-10">
            <h1 class="text-xl font-bold text-gray-800">@yield('title')</h1>
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-gray-600">Halo, <span class="text-[#005ea2] font-bold">Staf Restoran</span></span>
                <div class="h-9 w-9 bg-[#8dc63f] rounded-full flex items-center justify-center text-white font-bold border-2 border-white shadow-md">
                    OP
                </div>
            </div>
        </header>

        <!-- Area Konten Dinamis -->
        <div class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </div>
    </main>

</body>
</html>