@extends('layouts.admin')

@section('title', 'Manajemen Komplain Pelanggan')

@section('content')
    <!-- Header: Judul & Ringkasan Singkat -->
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-800">Pusat Resolusi Komplain</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau dan tindak lanjuti kendala pesanan penumpang di area bandara.</p>
        </div>
        <div class="flex items-center space-x-3 bg-white p-2 rounded-lg border border-gray-100 shadow-sm">
            <div class="px-3 py-1.5 bg-red-50 text-red-700 rounded-md text-sm font-semibold border border-red-100 flex items-center">
                <span class="w-2 h-2 rounded-full bg-red-500 mr-2 animate-pulse"></span>
                2 Pending
            </div>
            <div class="px-3 py-1.5 bg-blue-50 text-[#005ea2] rounded-md text-sm font-semibold border border-blue-100">
                1 Diproses
            </div>
        </div>
    </div>

    <!-- Filter Tab Navigation -->
    <div class="flex space-x-1 mb-6 border-b border-gray-200">
        <button class="px-5 py-3 text-sm font-bold text-[#005ea2] border-b-2 border-[#005ea2] transition-colors focus:outline-none">
            Semua Komplain
        </button>
        <button class="px-5 py-3 text-sm font-medium text-gray-500 hover:text-gray-800 hover:border-gray-300 border-b-2 border-transparent transition-colors focus:outline-none">
            Butuh Tindakan (2)
        </button>
        <button class="px-5 py-3 text-sm font-medium text-gray-500 hover:text-gray-800 hover:border-gray-300 border-b-2 border-transparent transition-colors focus:outline-none">
            Sedang Diproses (1)
        </button>
        <button class="px-5 py-3 text-sm font-medium text-gray-500 hover:text-gray-800 hover:border-gray-300 border-b-2 border-transparent transition-colors focus:outline-none">
            Selesai Ditangani
        </button>
    </div>

    <!-- Tabel Komplain Premium -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        
        <!-- Toolbar Tabel -->
        <div class="p-4 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="relative w-full sm:w-72">
                <input type="text" placeholder="Cari kode CMP atau nama penumpang..." class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-[#005ea2] focus:ring-1 focus:ring-[#005ea2] outline-none transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <button class="text-sm font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 px-4 py-2 rounded-lg flex items-center transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                Filter Tambahan
            </button>
        </div>

        <!-- Isi Tabel -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white text-gray-400 text-xs uppercase tracking-wider border-b border-gray-200">
                        <th class="px-6 py-4 font-bold">Tiket & Waktu</th>
                        <th class="px-6 py-4 font-bold">Detail Pelapor</th>
                        <th class="px-6 py-4 font-bold">Keluhan</th>
                        <th class="px-6 py-4 font-bold text-center">Status</th>
                        <th class="px-6 py-4 font-bold text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    
                    <!-- Item Komplain 1 (High Priority / Pending) -->
                    <tr class="hover:bg-red-50/30 transition-colors group relative">
                        <td class="px-6 py-4 align-top">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-red-500"></div>
                            <div class="font-extrabold text-gray-900 flex items-center">
                                #CMP-3021
                                <span class="ml-2 bg-red-100 text-red-700 text-[10px] font-bold px-1.5 py-0.5 rounded uppercase">Urgent</span>
                            </div>
                            <div class="text-xs text-gray-500 mt-1 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                13:15 WIB (Hari ini)
                            </div>
                        </td>
                        <td class="px-6 py-4 align-top">
                            <div class="font-bold text-gray-800">Budi Santoso</div>
                            <div class="text-xs font-medium text-[#005ea2] mt-0.5 flex items-center cursor-pointer hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                #ORD-98765
                            </div>
                            <div class="text-xs text-gray-500 mt-1">Tenant: <span class="font-semibold text-gray-700">A&W (T1)</span></div>
                        </td>
                        <td class="px-6 py-4 align-top max-w-sm">
                            <div class="font-semibold text-gray-800 mb-1 text-sm">Pesanan Terlalu Lama</div>
                            <p class="text-xs leading-relaxed text-gray-600 bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                                "Makanan agak lama disiapkan padahal waktu boarding saya sudah dekat. Mohon dibantu agar dipercepat."
                            </p>
                        </td>
                        <td class="px-6 py-4 align-top text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200 shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                                Menunggu Respon
                            </span>
                        </td>
                        <td class="px-6 py-4 align-top text-right">
                            <button class="bg-[#005ea2] hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-xs font-bold transition-all shadow-sm hover:shadow-md flex items-center justify-center w-full md:w-auto ml-auto">
                                Tindak Lanjuti
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Item Komplain 2 (Sedang Diproses) -->
                    <tr class="hover:bg-slate-50 transition-colors group relative">
                        <td class="px-6 py-4 align-top">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500 hidden group-hover:block"></div>
                            <div class="font-bold text-gray-900">#CMP-3019</div>
                            <div class="text-xs text-gray-400 mt-1 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                Kemarin, 09:40 WIB
                            </div>
                        </td>
                        <td class="px-6 py-4 align-top">
                            <div class="font-bold text-gray-800">Siti Rahmawati</div>
                            <div class="text-xs font-medium text-[#005ea2] mt-0.5 hover:underline cursor-pointer">#ORD-98712</div>
                            <div class="text-xs text-gray-500 mt-1">Tenant: <span class="font-semibold text-gray-700">Beard Papa's (T1)</span></div>
                        </td>
                        <td class="px-6 py-4 align-top max-w-sm">
                            <div class="font-semibold text-gray-800 mb-1 text-sm">Pesanan Tidak Sesuai</div>
                            <p class="text-xs leading-relaxed text-gray-600 bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                                "Saya pesan varian Vanilla tapi yang dikirim varian Coklat. Mohon konfirmasinya."
                            </p>
                        </td>
                        <td class="px-6 py-4 align-top text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                <svg class="animate-spin h-3 w-3 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Investigasi
                            </span>
                        </td>
                        <td class="px-6 py-4 align-top text-right">
                            <button class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-xs font-bold transition-colors flex items-center justify-center w-full md:w-auto ml-auto">
                                Lihat Detail
                            </button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
        
        <!-- Footer / Paginasi -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <span class="text-xs text-gray-500 font-medium">Menampilkan 2 komplain aktif.</span>
        </div>
    </div>
@endsection