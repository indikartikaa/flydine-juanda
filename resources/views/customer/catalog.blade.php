<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlyDine - Juanda International Airport</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }
        /* Kustomisasi Scrollbar Menu agar lebih elegan */
        .menu-scroll::-webkit-scrollbar { width: 4px; }
        .menu-scroll::-webkit-scrollbar-track { background: transparent; }
        .menu-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .menu-scroll:hover::-webkit-scrollbar-thumb { background: #94a3b8; }
    </style>
    <script>
        function changeLanguage(lang) {
            document.querySelectorAll('[data-id]').forEach(function(element) {
                element.innerHTML = lang === 'en' ? element.getAttribute('data-en') : element.getAttribute('data-id');
            });
        }

        function searchTenant() {
            let input = document.getElementById('searchInput').value.toLowerCase();
            document.querySelectorAll('.tenant-card').forEach(function(card) {
                let name = card.getAttribute('data-name').toLowerCase();
                card.style.display = name.includes(input) ? "" : "none";
            });
        }
    </script>
</head>

<body class="text-gray-800 flex flex-col min-h-screen selection:bg-[#005ea2] selection:text-white">

    <!-- Header Navigation (Lebih Bersih) -->
    <header class="bg-white border-b-4 border-[#8dc63f] shadow-sm sticky top-0 z-50">
        <div class="flex flex-col md:flex-row">
            <div class="w-full md:w-1/3 py-4 px-8 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">Juanda</h1>
                    <p class="text-[10px] text-gray-500 font-bold tracking-widest uppercase mt-0.5">International Airport</p>
                </div>
                <span class="text-xs font-bold bg-blue-50 text-[#005ea2] px-3 py-1.5 rounded-full border border-blue-100">FlyDine T1</span>
            </div>

            <div class="w-full md:w-2/3 bg-[#005ea2] text-white flex items-center justify-end px-8 py-3 space-x-6">
                <div class="text-sm font-semibold flex items-center space-x-3 bg-blue-800/50 px-3 py-1.5 rounded-full">
                    <button onclick="changeLanguage('en')" class="hover:text-blue-200 transition-colors">EN</button>
                    <span class="text-blue-400/50">|</span>
                    <button onclick="changeLanguage('id')" class="hover:text-blue-200 transition-colors">ID</button>
                </div>
                <span class="hidden md:inline text-sm border-l border-blue-400/50 pl-6 uppercase tracking-wide font-medium cursor-pointer hover:text-blue-200 transition-colors" data-id="PILIH BANDARA" data-en="CHOOSE AIRPORT">CHOOSE AIRPORT</span>
                <span class="hidden md:inline text-sm uppercase tracking-wide font-medium cursor-pointer hover:text-blue-200 transition-colors" data-id="KORPORAT" data-en="CORPORATE">CORPORATE</span>
                <button class="focus:outline-none hover:text-blue-200 transition-colors ml-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-6 md:px-8 py-12 flex-grow">

        <!-- Hero Section -->
        <section class="mb-12">
            <p class="text-[#8dc63f] font-bold text-sm tracking-[0.2em] uppercase mb-2 flex items-center" data-id="DAPATKAN LOKASI TERBAIK" data-en="GET THE RIGHT LOCATION">
                <span class="w-8 h-0.5 bg-[#8dc63f] mr-3 rounded-full"></span>
                GET THE RIGHT LOCATION
            </p>
            <h2 class="text-5xl md:text-6xl font-black text-[#005ea2] uppercase leading-[1.1] tracking-tighter" data-id="DIREKTORI MAKANAN" data-en="DINING DIRECTORY">
                DINING<br>DIRECTORY
            </h2>
        </section>

        <!-- Search & Filter (Lebih Membulat) -->
        <section class="flex flex-col md:flex-row gap-4 mb-10">
            <div class="w-full md:w-1/4">
                <select class="w-full bg-white border-0 py-4 px-5 text-sm font-medium rounded-2xl shadow-sm text-gray-600 focus:ring-2 focus:ring-[#005ea2]/20 cursor-pointer appearance-none outline-none">
                    <option data-id="Semua Terminal" data-en="All Terminal">All Terminal</option>
                    <option>Terminal 1</option>
                </select>
            </div>
            <div class="w-full md:w-1/4">
                <select class="w-full bg-white border-0 py-4 px-5 text-sm font-medium rounded-2xl shadow-sm text-gray-600 focus:ring-2 focus:ring-[#005ea2]/20 cursor-pointer appearance-none outline-none">
                    <option data-id="Semua Lokasi" data-en="All Location">All Location</option>
                    <option>Boarding Lounge</option>
                </select>
            </div>
            <div class="w-full md:w-2/4 flex shadow-sm rounded-2xl overflow-hidden bg-white group focus-within:ring-2 focus-within:ring-[#005ea2]/20">
                <input id="searchInput" onkeyup="searchTenant()" type="text" placeholder="Enter Shop Name..." class="flex-1 bg-transparent border-0 px-6 py-4 text-sm font-medium focus:outline-none placeholder-gray-400">
                <button class="bg-[#76bce4] text-white px-8 hover:bg-[#005ea2] transition-colors focus:outline-none flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </button>
            </div>
        </section>

        <!-- Tabs -->
        <div class="flex space-x-10 border-b-2 border-gray-100 mb-10 text-xs font-bold tracking-widest">
            <button class="text-gray-900 border-b-4 border-[#8dc63f] pb-4 flex items-center focus:outline-none transition-colors -mb-[2px]">
                <span class="w-2.5 h-2.5 rounded-full bg-yellow-400 mr-3"></span>
                <span data-id="TERMINAL DOMESTIK" data-en="DOMESTIC TERMINAL">DOMESTIC TERMINAL</span>
            </button>
            <button class="text-gray-400 pb-4 flex items-center focus:outline-none hover:text-gray-600 transition-colors -mb-[2px]">
                <span class="w-2.5 h-2.5 rounded-full bg-purple-500 mr-3 opacity-50"></span>
                <span data-id="TERMINAL INTERNASIONAL" data-en="INTERNATIONAL TERMINAL">INTERNATIONAL TERMINAL</span>
            </button>
        </div>

        <!-- Tenant Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            @forelse($tenants as $tenant)
            <div class="tenant-card bg-white group shadow-sm hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 rounded-[1.5rem] overflow-hidden flex flex-col h-full border border-gray-100/50" data-name="{{ $tenant->name }}">
                
                <!-- Image/Logo Area (Gradien Halus) -->
                <div class="h-48 bg-gradient-to-br from-gray-50 to-white relative flex items-center justify-center p-8 border-b border-gray-50">
                    <h2 class="text-7xl font-black text-[#005ea2] tracking-tighter group-hover:scale-110 transition-transform duration-500 drop-shadow-sm select-none">
                        {{ strtoupper(substr(str_replace([' ', "'"], '', $tenant->name), 0, 2)) }}
                    </h2>

                    <!-- Status Label Modern -->
                    @php
                        $open = false;
                        if($tenant->opening_time && $tenant->closing_time){
                            $now = now()->format('H:i:s');
                            $open = $now >= $tenant->opening_time && $now <= $tenant->closing_time;
                        }
                    @endphp
                    
                    @if($open)
                        <div class="absolute top-5 right-5 bg-white/95 text-green-600 text-[10px] font-extrabold px-3.5 py-1.5 rounded-full shadow-sm border border-green-100 tracking-wider flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> OPEN
                        </div>
                    @else
                        <div class="absolute top-5 right-5 bg-white/95 text-red-500 text-[10px] font-extrabold px-3.5 py-1.5 rounded-full shadow-sm border border-red-100 tracking-wider flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> CLOSED
                        </div>
                    @endif
                </div>

                <!-- Card Body -->
                <div class="p-7 flex flex-col flex-grow bg-white">
                    <div class="inline-flex items-center space-x-1.5 mb-3 bg-gray-50 px-2.5 py-1 rounded-md w-max border border-gray-100">
                        <span class="text-yellow-500 text-sm leading-none">•</span>
                        <span class="text-[10px] text-gray-500 font-bold tracking-wider uppercase">T1 - {{ $tenant->floor_location ?? 'TERMINAL 1' }}</span>
                    </div>
                    
                    <h3 class="font-black text-2xl text-gray-800 uppercase tracking-tight mb-5 group-hover:text-[#005ea2] transition-colors">{{ $tenant->name }}</h3>
                    
                    <!-- Product List -->
                    <div class="space-y-3.5 mb-8 menu-scroll pr-3 flex-grow" style="max-height: 120px; overflow-y: auto;">
                        @forelse($tenant->products as $product)
                            <div class="flex justify-between items-center group/item">
                                <span class="text-sm text-gray-500 font-medium truncate pr-4 group-hover/item:text-gray-800 transition-colors">{{ $product->name }}</span>
                                <div class="flex-grow border-b-2 border-dotted border-gray-200 mx-2 mt-2 opacity-50"></div>
                                <span class="text-sm font-bold text-gray-900 whitespace-nowrap">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                        @empty
                            <div class="text-xs text-gray-400 font-medium italic text-center py-6 bg-gray-50 rounded-xl border border-gray-100">
                                <span data-id="Katalog menu belum tersedia." data-en="Menu catalog is not available.">Katalog menu belum tersedia.</span>
                            </div>
                        @endforelse
                    </div>

                    <!-- Tombol Pesan (Solid Block) -->
                    <a href="{{ route('customer.menu', ['tenant' => $tenant->id]) }}" class="mt-auto flex items-center justify-center w-full bg-[#005ea2] text-white hover:bg-blue-800 font-bold py-3.5 rounded-xl text-sm uppercase tracking-widest transition-all shadow-md hover:shadow-lg focus:ring-4 focus:ring-blue-100 outline-none">
                        <span data-id="Pesan Sekarang" data-en="Order Now">Pesan Sekarang</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full flex flex-col items-center justify-center py-24 bg-white border-2 border-dashed border-gray-200 rounded-3xl">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                </div>
                <p class="text-gray-400 font-semibold text-lg" data-id="Belum ada data restoran yang aktif." data-en="No active tenant available.">Belum ada data restoran yang aktif.</p>
            </div>
            @endforelse

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-[#003b66] text-white text-center py-10 mt-auto">
        <p class="text-xs text-blue-200/80 font-medium tracking-wider">
            <span data-id="© 2026 PT Angkasa Pura I (Persero) - Bandara Internasional Juanda." data-en="© 2026 PT Angkasa Pura I (Persero) - Juanda International Airport.">© 2026 PT Angkasa Pura I (Persero) - Bandara Internasional Juanda.</span>
        </p>
        <p class="text-[10px] text-blue-400 mt-2.5 font-bold tracking-widest uppercase opacity-70">FlyDine System MVP v1.0</p>
    </footer>

</body>
</html>