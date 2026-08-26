<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Dashboard - FlyDine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-[#005ea2] text-white flex flex-col h-full shadow-lg z-20 shrink-0">
        
        <!-- Area Logo -->
        <div class="p-6 border-b border-blue-700/50">
            <h2 class="text-2xl font-extrabold tracking-wider flex items-center">
                <!-- Tambahan Ikon Kecil di Sebelah Logo -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                FlyDine
            </h2>
            <p class="text-xs text-blue-200 mt-1.5 font-medium">Tenant Partner Portal</p>
        </div>
        
        <!-- Menu Navigasi -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            
            <!-- Menu Dashboard -->
            <a href="{{ url('/tenant/dashboard') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->is('*tenant/dashboard*') ? 'bg-[#76bce4]/20 text-white font-semibold border-l-4 border-[#8dc63f] shadow-inner' : 'text-blue-100 hover:bg-blue-800 hover:text-white hover:pl-5 border-l-4 border-transparent' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                Dashboard
            </a>

            <!-- Menu Manajemen Pesanan -->
            <a href="{{ url('/tenant/orders') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->is('*tenant/orders*') ? 'bg-[#76bce4]/20 text-white font-semibold border-l-4 border-[#8dc63f] shadow-inner' : 'text-blue-100 hover:bg-blue-800 hover:text-white hover:pl-5 border-l-4 border-transparent' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                Manajemen Pesanan
            </a>

            <!-- Menu Katalog Produk -->
            <a href="{{ url('/tenant/products') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->is('*tenant/products*') ? 'bg-[#76bce4]/20 text-white font-semibold border-l-4 border-[#8dc63f] shadow-inner' : 'text-blue-100 hover:bg-blue-800 hover:text-white hover:pl-5 border-l-4 border-transparent' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                Katalog Produk
            </a>
            
        </nav>
        
        <!-- Area Logout (Fungsional) -->
        <div class="p-4 border-t border-blue-700/50">
            <!-- Form untuk Logout agar aman dari serangan CSRF -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all shadow-sm hover:shadow-md text-sm font-semibold group">
                    <!-- Ikon diberi efek bergeser saat di-hover -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden">
        <!-- Top Navbar -->
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 shrink-0 z-10">
            <h1 class="text-xl font-bold text-gray-800">Ringkasan Hari Ini</h1>
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-gray-600">Halo, <span class="text-[#005ea2] font-bold">Staf A&W Terminal 1</span></span>
                <div class="h-9 w-9 bg-[#8dc63f] rounded-full flex items-center justify-center text-white font-bold border-2 border-white shadow-md">
                    AW
                </div>
            </div>
        </header>

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto p-8">
            
            <!-- Statistic Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Card 1 -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
                    <div class="rounded-full p-4 bg-blue-50 text-[#005ea2] mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Pesanan Masuk</p>
                        <h3 class="text-3xl font-extrabold text-gray-800">12</h3>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
                    <div class="rounded-full p-4 bg-green-50 text-[#8dc63f] mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Sedang Diproses</p>
                        <h3 class="text-3xl font-extrabold text-gray-800">4</h3>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
                    <div class="rounded-full p-4 bg-yellow-50 text-yellow-500 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Total Pendapatan</p>
                        <h3 class="text-2xl font-extrabold text-gray-800">Rp 450.000</h3>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h2 class="font-bold text-gray-800">Antrean Pesanan Terbaru</h2>
                    <a href="#" class="text-sm font-semibold text-[#005ea2] hover:underline">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-medium">ID Pesanan</th>
                                <th class="px-6 py-4 font-medium">Pelanggan</th>
                                <th class="px-6 py-4 font-medium">Penerbangan</th>
                                <th class="px-6 py-4 font-medium">Total Harga</th>
                                <th class="px-6 py-4 font-medium">Status</th>
                                <th class="px-6 py-4 font-medium text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100">
                            <!-- Dummy Data Row 1 -->
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-bold text-[#005ea2]">#ORD-98765</td>
                                <td class="px-6 py-4">Budi Santoso</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800">JT-012</div>
                                    <div class="text-xs text-red-500 font-medium">Boarding: 14:45</div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-800">Rp 105.000</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Menunggu Konfirmasi
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button class="bg-[#005ea2] hover:bg-blue-700 text-white px-4 py-1.5 rounded text-xs font-semibold transition-colors">
                                        Proses
                                    </button>
                                </td>
                            </tr>
                            <!-- Dummy Data Row 2 -->
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-bold text-[#005ea2]">#ORD-98764</td>
                                <td class="px-6 py-4">Rina Wijaya</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800">GA-318</div>
                                    <div class="text-xs text-gray-500">Boarding: 15:30</div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-800">Rp 45.000</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Sedang Dimasak
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button class="bg-[#8dc63f] hover:bg-green-600 text-white px-4 py-1.5 rounded text-xs font-semibold transition-colors">
                                        Siap Diambil
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

</body>
</html>