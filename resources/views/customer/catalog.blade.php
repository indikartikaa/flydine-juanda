<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlyDine - Juanda International Airport</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }
        /* Kustomisasi scrollbar untuk daftar menu */
        .menu-scroll::-webkit-scrollbar { width: 4px; }
        .menu-scroll::-webkit-scrollbar-track { background: #f1f1f1; }
        .menu-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen">

    <!-- Header Atas (Mirip Referensi Juanda) -->
    <header class="bg-white border-b-4 border-[#8dc63f] flex flex-col md:flex-row shadow-sm sticky top-0 z-50">
        <!-- Bagian Logo (Kiri) -->
        <div class="w-full md:w-1/3 py-3 px-6 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-extrabold text-gray-800 leading-tight">Juanda</h1>
                <p class="text-xs text-gray-500 font-semibold tracking-wider">International Airport</p>
            </div>
            <span class="text-[10px] font-bold bg-[#005ea2] text-white px-2 py-1 rounded">FlyDine T1</span>
        </div>
        
        <!-- Bagian Menu Navigasi (Kanan) -->
        <div class="w-full md:w-2/3 bg-[#005ea2] text-white flex items-center justify-end px-6 py-3 space-x-6">
            <span class="text-sm font-semibold hidden md:inline cursor-pointer hover:text-gray-200">EN | ID</span>
            <span class="text-sm font-semibold hidden md:inline border-l pl-6 cursor-pointer hover:text-gray-200">CHOOSE AIRPORT </span>
            <span class="text-sm font-semibold hidden md:inline cursor-pointer hover:text-gray-200">CORPORATE</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="container mx-auto px-6 py-10 flex-grow">
        
        <!-- Bagian Judul -->
        <div class="mb-10">
            <p class="text-[#8dc63f] font-bold text-sm tracking-widest uppercase mb-1">Get the right location</p>
            <h2 class="text-4xl md:text-5xl font-extrabold text-[#005ea2] uppercase leading-none">DINING<br>DIRECTORY</h2>
            <div class="w-16 h-1 bg-[#8dc63f] mt-4"></div>
        </div>

        <!-- Filter & Search (Visual mockup seperti di referensi) -->
        <div class="flex flex-col md:flex-row gap-4 mb-8">
            <div class="relative w-full md:w-1/4">
                <select class="w-full appearance-none bg-white border border-gray-200 text-gray-700 py-3 px-4 pr-8 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#005ea2] text-sm">
                    <option>All Terminal</option>
                    <option>Terminal 1</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 bg-[#76bce4] text-white">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>
            <div class="relative w-full md:w-1/4">
                <select class="w-full appearance-none bg-white border border-gray-200 text-gray-700 py-3 px-4 pr-8 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#005ea2] text-sm">
                    <option>All Location</option>
                    <option>Boarding Lounge</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 bg-[#76bce4] text-white">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>
            <div class="relative w-full md:w-2/4 flex shadow-sm">
                <input type="text" placeholder="Enter Shop Name" class="w-full bg-white border border-gray-200 text-gray-700 py-3 px-4 focus:outline-none focus:ring-2 focus:ring-[#005ea2] text-sm italic">
                <button class="bg-[#76bce4] text-white px-6 hover:bg-[#005ea2] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Tab Navigasi Terminal -->
        <div class="flex space-x-6 border-b border-gray-200 mb-8 text-xs font-bold tracking-wider">
            <span class="text-gray-800 border-b-2 border-[#8dc63f] pb-2 flex items-center">
                <span class="text-yellow-500 text-lg mr-2 leading-none">•</span> DOMESTIC TERMINAL
            </span>
            <span class="text-gray-400 pb-2 flex items-center">
                <span class="text-purple-500 text-lg mr-2 leading-none">•</span> INTERNATIONAL TERMINAL
            </span>
        </div>

        <!-- Grid Daftar Tenant / Restoran -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            @forelse($tenants as $tenant)
            <!-- Kartu Tenant -->
            <div class="bg-white group cursor-pointer shadow-sm hover:shadow-xl transition-shadow duration-300">
                <!-- Placeholder Gambar (Bisa diganti foto asli nanti) -->
                <div class="h-48 bg-gray-200 relative overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($tenant->name) }}&background=f1f5f9&color=005ea2&size=500&font-size=0.2" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $tenant->name }}">
                    <!-- Status Buka/Tutup (Dummy) -->
                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-green-600 text-[10px] font-bold px-2 py-1 rounded shadow-sm">
                        OPEN
                    </div>
                </div>
                
                <div class="p-5">
                    <!-- Lokasi Format Juanda -->
                    <p class="text-[10px] text-gray-500 font-semibold tracking-wider uppercase mb-2 flex items-center">
                        <span class="text-yellow-500 text-xl leading-[0] mr-1.5">•</span> 
                        T1 - {{ $tenant->floor_location }}
                    </p>
                    
                    <h3 class="font-bold text-xl text-[#005ea2] uppercase mb-4">{{ $tenant->name }}</h3>
                    
                    <!-- Area Daftar Produk (Bisa di-scroll) -->
                    <div class="space-y-3 mb-6 menu-scroll pr-2" style="max-height: 120px; overflow-y: auto;">
                        @forelse($tenant->products as $product)
                        <div class="flex justify-between items-center border-b border-dashed border-gray-200 pb-2 last:border-0 last:pb-0">
                            <span class="text-sm text-gray-600 font-medium truncate pr-4">{{ $product->name }}</span>
                            <span class="text-sm font-bold text-gray-800 whitespace-nowrap">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                        @empty
                        <div class="text-xs text-gray-400 italic text-center py-4 bg-gray-50 rounded">Katalog menu belum tersedia.</div>
                        @endforelse
                    </div>
                    
                    <!-- Tombol Aksi FlyDine -->
                    <button class="w-full border-2 border-[#005ea2] text-[#005ea2] hover:bg-[#005ea2] hover:text-white font-bold py-2.5 text-sm uppercase tracking-wide transition-colors">
                        Pesan Sekarang
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16 bg-white border border-dashed border-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <p class="text-gray-500 font-medium">Belum ada data restoran F&B yang aktif di terminal ini.</p>
            </div>
            @endforelse
            
        </div>
    </main>
    
    <footer class="bg-[#003b66] text-white text-center py-6 mt-auto">
        <p class="text-xs text-gray-400">© 2026 PT Angkasa Pura I (Persero) - Juanda International Airport.</p>
        <p class="text-xs text-gray-400 mt-1">FlyDine System MVP v1.0</p>
    </footer>

</body>
</html>