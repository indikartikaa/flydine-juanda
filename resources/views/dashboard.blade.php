<x-app-layout>
    <!-- Bagian Header (Judul Halaman) -->
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-extrabold text-2xl text-[#005ea2] leading-tight flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                Pusat Kendali Restoran
            </h2>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-700 border border-green-200">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                Sistem Online
            </span>
        </div>
    </x-slot>

    <!-- Konten Utama Dashboard -->
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Kartu Statistik Bisnis -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                
                <!-- Pesanan Masuk (Urgent) -->
                <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl p-6 text-white shadow-md relative overflow-hidden transform hover:-translate-y-1 transition-all">
                    <p class="text-orange-50 text-sm font-semibold mb-1">Pesanan Baru (Menunggu)</p>
                    <h3 class="text-4xl font-extrabold">4 <span class="text-sm font-medium">Antrean</span></h3>
                    <svg class="absolute -right-4 -bottom-4 opacity-20 w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" /><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" /></svg>
                </div>

                <!-- Pesanan Selesai -->
                <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm flex justify-between items-center hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Pesanan Selesai Hari Ini</p>
                        <h3 class="text-3xl font-extrabold text-gray-800">42</h3>
                    </div>
                    <div class="h-14 w-14 bg-green-50 text-green-600 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                </div>

                <!-- Total Menu Aktif -->
                <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm flex justify-between items-center hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Menu Aktif di Katalog</p>
                        <h3 class="text-3xl font-extrabold text-[#005ea2]">15 <span class="text-xs text-gray-400 font-normal">Item</span></h3>
                    </div>
                    <div class="h-14 w-14 bg-blue-50 text-[#005ea2] rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                    </div>
                </div>

            </div>

            <!-- Pesanan Masuk (Real-time Preview) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 text-lg">Pesanan Perlu Disiapkan</h3>
                    <a href="{{ url('/tenant/orders') }}" class="text-sm font-semibold text-[#005ea2] hover:underline">Kelola Semua Pesanan &rarr;</a>
                </div>
                
                <div class="p-0">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                                <th class="px-6 py-3 font-semibold">Kode Pesanan</th>
                                <th class="px-6 py-3 font-semibold">Detail Menu</th>
                                <th class="px-6 py-3 font-semibold text-center">Status</th>
                                <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-sm">
                            
                            <tr class="hover:bg-orange-50/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800">#ORD-9982</div>
                                    <div class="text-xs text-gray-500">2 menit yang lalu</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-[#005ea2]">2x Paket Nasi Ayam</span><br>
                                    <span class="text-xs text-gray-500">Catatan: Ekstra sambal</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-bold shadow-sm">Baru Masuk</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="bg-[#8dc63f] hover:bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">Terima & Proses</button>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>