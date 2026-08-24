<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - FlyDine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }</style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen">

    <header class="bg-[#005ea2] text-white shadow-md sticky top-0 z-50 px-4 py-3 flex items-center">
        <a href="/" class="mr-4 hover:text-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <h1 class="text-lg font-bold tracking-wider">Keranjang Pesanan</h1>
    </header>

    <main class="container mx-auto px-4 py-6 max-w-2xl flex-grow">
        <!-- Rincian Item (Tampilan Dummy Sementara) -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6">
            <h2 class="font-bold text-[#005ea2] uppercase border-b pb-2 mb-4">A&W - T1 Boarding Lounge</h2>
            
            <div class="flex justify-between items-center mb-3">
                <div>
                    <h3 class="font-semibold text-sm">2x Paket Fried Chicken</h3>
                    <p class="text-xs text-gray-500">Rp 45.000 / item</p>
                </div>
                <span class="font-bold">Rp 90.000</span>
            </div>
            
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="font-semibold text-sm">1x Root Beer</h3>
                    <p class="text-xs text-gray-500">Rp 15.000 / item</p>
                </div>
                <span class="font-bold">Rp 15.000</span>
            </div>

            <div class="flex justify-between items-center pt-3 border-t border-dashed border-gray-300">
                <span class="font-bold text-gray-600">Total Pembayaran</span>
                <span class="font-extrabold text-xl text-[#005ea2]">Rp 105.000</span>
            </div>
        </div>

        <!-- Form Data Penerbangan -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-8">
            <h2 class="font-bold text-[#8dc63f] uppercase mb-4 text-sm flex items-center">
                <span class="text-yellow-500 text-lg mr-2 leading-none">•</span> Informasi Keberangkatan
            </h2>
            
            <form action="/tracking" method="GET" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Pemesan</label>
                    <input type="text" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-[#005ea2] focus:outline-none" placeholder="Masukkan nama Anda">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">No. Penerbangan</label>
                        <input type="text" class="w-full border border-gray-300 rounded px-3 py-2 text-sm uppercase" placeholder="Misal: JT-012">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Gate / Pintu</label>
                        <input type="text" class="w-full border border-gray-300 rounded px-3 py-2 text-sm uppercase" placeholder="Misal: Gate 8">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Waktu Boarding</label>
                    <input type="time" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <p class="text-[10px] text-gray-500 mt-1 italic">*Pesanan akan otomatis dibatalkan jika waktu boarding tersisa kurang dari 15 menit.</p>
                </div>
                
                <button type="submit" class="w-full bg-[#8dc63f] hover:bg-green-600 text-white font-bold py-3 rounded text-sm uppercase tracking-wider mt-4">
                    Konfirmasi & Bayar
                </button>
            </form>
        </div>
    </main>
</body>
</html>