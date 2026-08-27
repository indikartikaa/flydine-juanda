<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Pesanan - FlyDine Juanda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }</style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen">

    <!-- Header Keranjang -->
    <header class="bg-[#005ea2] text-white shadow-md sticky top-0 z-50 px-6 py-4 flex items-center border-b-4 border-[#8dc63f]">
        <a href="/" class="mr-4 p-2 rounded-full hover:bg-white/10 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <div>
            <h1 class="text-lg font-black tracking-wide">Keranjang Pesanan</h1>
            <p class="text-[10px] text-blue-200 uppercase tracking-widest font-semibold">Juanda International Airport</p>
        </div>
    </header>

    <!-- Main Container -->
    <main class="container mx-auto px-4 py-8 max-w-xl flex-grow">
        
        <!-- Rincian Item Pesanan -->
        <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-gray-100 mb-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                <h2 class="font-extrabold text-[#005ea2] uppercase text-sm tracking-wide">A&W - T1 Boarding Lounge</h2>
                <span class="text-xs font-bold bg-green-50 text-green-600 px-2.5 py-1 rounded-full border border-green-100">Ready</span>
            </div>
            
            <div class="space-y-4 mb-5">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-sm text-gray-800">2x Paket Fried Chicken</h3>
                        <p class="text-xs text-gray-400 font-medium">Rp 45.000 / item</p>
                    </div>
                    <span class="font-bold text-gray-900">Rp 90.000</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-sm text-gray-800">1x Root Beer</h3>
                        <p class="text-xs text-gray-400 font-medium">Rp 15.000 / item</p>
                    </div>
                    <span class="font-bold text-gray-900">Rp 15.000</span>
                </div>
            </div>

            <div class="flex justify-between items-center pt-4 border-t border-dashed border-gray-200">
                <span class="font-bold text-sm text-gray-500 uppercase tracking-wider">Total Pembayaran</span>
                <span class="font-black text-2xl text-[#005ea2]">Rp 105.000</span>
            </div>
        </div>

        <!-- Form Data Penerbangan (Sistem Keamanan Boarding) -->
        <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-gray-100 mb-8">
            <h2 class="font-extrabold text-[#8dc63f] uppercase mb-5 text-sm flex items-center tracking-wider">
                <span class="w-2.5 h-2.5 rounded-full bg-[#8dc63f] mr-2"></span> Informasi Keberangkatan
            </h2>
            
            <form action="/tracking" method="GET" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1.5">Nama Pemesan</label>
                    <input type="text" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium focus:bg-white focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] outline-none transition-all" placeholder="Masukkan nama lengkap Anda">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1.5">No. Penerbangan</label>
                        <input type="text" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium uppercase focus:bg-white focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] outline-none transition-all" placeholder="Misal: JT-012">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1.5">Gate / Pintu</label>
                        <input type="text" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium uppercase focus:bg-white focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] outline-none transition-all" placeholder="Misal: Gate 8">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1.5">Waktu Boarding</label>
                    <input type="time" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium focus:bg-white focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] outline-none transition-all">
                    
                    <!-- Peringatan Sistem (Fitur Khas FR FlyDine) -->
                    <div class="mt-2.5 p-3 bg-amber-50 rounded-xl border border-amber-200/60 flex items-start space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <p class="text-[11px] text-amber-800 font-medium leading-relaxed">
                            Pesanan akan otomatis dibatalkan jika waktu boarding tersisa kurang dari 15 menit.
                        </p>
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-[#8dc63f] hover:bg-green-600 text-white font-black py-4 rounded-xl text-sm uppercase tracking-widest transition-all shadow-md hover:shadow-lg focus:ring-4 focus:ring-green-100 outline-none mt-6 flex items-center justify-center">
                    <span>Konfirmasi & Bayar</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </button>
            </form>
        </div>
    </main>

    <!-- Footer Tipis -->
    <footer class="py-6 text-center text-gray-400 text-xs mt-auto">
        <p>FlyDine System MVP v1.0 • Juanda International Airport</p>
    </footer>

</body>
</html>