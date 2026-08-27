@extends('layouts.admin')

@section('title', 'Manajemen Mitra Tenant')

@section('content')
    <!-- Quick Stats Khusus Tenant -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-gradient-to-br from-[#005ea2] to-blue-800 rounded-xl p-6 text-white shadow-md relative overflow-hidden">
            <!-- Dekorasi Background -->
            <svg class="absolute -right-4 -bottom-4 opacity-20 w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/></svg>
            <p class="text-blue-100 text-sm font-medium mb-1 relative z-10">Total Mitra Beroperasi</p>
            <h3 class="text-4xl font-extrabold relative z-10">18 <span class="text-base font-medium opacity-80">Gerai</span></h3>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm flex justify-between items-center group hover:shadow-md transition-shadow">
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Menunggu Verifikasi</p>
                <h3 class="text-3xl font-extrabold text-gray-800">2 <span class="text-sm font-medium text-gray-400">Pendaftar</span></h3>
            </div>
            <div class="h-12 w-12 bg-yellow-50 text-yellow-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm flex justify-between items-center group hover:shadow-md transition-shadow">
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Kontrak Segera Habis</p>
                <h3 class="text-3xl font-extrabold text-red-600">1 <span class="text-sm font-medium text-gray-400">Tenant</span></h3>
            </div>
            <div class="h-12 w-12 bg-red-50 text-red-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
        </div>
    </div>

    <!-- Toolbar: Search, Filter, Action -->
    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex flex-col md:flex-row w-full md:w-auto gap-3">
            <div class="relative w-full md:w-80">
                <input type="text" placeholder="Cari nama brand, PT, atau kode ruang..." class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:border-[#005ea2] focus:ring-1 focus:ring-[#005ea2] outline-none transition-all bg-gray-50 focus:bg-white placeholder-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <select class="border border-gray-200 rounded-lg px-4 py-2.5 text-sm bg-gray-50 hover:bg-white focus:border-[#005ea2] outline-none transition-colors cursor-pointer text-gray-600 font-medium">
                <option value="">Filter: Semua Terminal</option>
                <option value="1">Hanya Terminal 1</option>
                <option value="2">Hanya Terminal 2</option>
            </select>
        </div>
        
        <button class="w-full md:w-auto bg-[#8dc63f] hover:bg-green-600 text-white px-6 py-2.5 rounded-lg text-sm font-bold shadow-md hover:shadow-lg flex items-center justify-center transition-all transform hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
            Tambah Mitra
        </button>
    </div>

    <!-- Tabel Tenant Premium -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">Perusahaan / Brand</th>
                        <th class="px-6 py-4 font-bold">Lokasi & Kategori</th>
                        <th class="px-6 py-4 font-bold">Masa Kontrak</th>
                        <th class="px-6 py-4 font-bold">Status</th>
                        <th class="px-6 py-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    
                    <!-- Tenant 1 -->
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-11 w-11 flex-shrink-0 bg-blue-100 rounded-lg border border-blue-200 flex items-center justify-center text-[#005ea2] font-bold text-lg shadow-inner">
                                    BP
                                </div>
                                <div class="ml-4">
                                    <div class="font-bold text-gray-900 text-base">Beard Papa's</div>
                                    <div class="text-xs text-gray-500 font-medium">PT Sebastian Citra Indonesia</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">T1 <span class="text-gray-400 font-normal ml-1">EP-01-01</span></div>
                            <div class="text-xs text-[#005ea2] font-semibold bg-blue-50 border border-blue-100 inline-block px-2.5 py-1 rounded mt-1.5">F&B (Bakery)</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-800">02 Jan 2025</div>
                            <div class="text-xs text-gray-500 mt-0.5 flex items-center">
                                <span class="mr-1">s/d</span> 31 Des 2025
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                Beroperasi
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <button class="p-2 text-gray-400 hover:text-[#005ea2] hover:bg-blue-50 rounded-lg transition-colors" title="Edit Tenant">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Tangguhkan (Suspend)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Tenant 2 -->
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-11 w-11 flex-shrink-0 bg-orange-100 rounded-lg border border-orange-200 flex items-center justify-center text-orange-600 font-bold text-lg shadow-inner">
                                    AW
                                </div>
                                <div class="ml-4">
                                    <div class="font-bold text-gray-900 text-base">A&W Restaurant</div>
                                    <div class="text-xs text-gray-500 font-medium">PT Fast Food Indonesia</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">T1 <span class="text-gray-400 font-normal ml-1">EP-01-02</span></div>
                            <div class="text-xs text-orange-600 font-semibold bg-orange-50 border border-orange-100 inline-block px-2.5 py-1 rounded mt-1.5">F&B (Fast Food)</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-800">01 Mar 2025</div>
                            <div class="text-xs text-gray-500 mt-0.5 flex items-center">
                                <span class="mr-1">s/d</span> 28 Feb 2026
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                Beroperasi
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <button class="p-2 text-gray-400 hover:text-[#005ea2] hover:bg-blue-50 rounded-lg transition-colors" title="Edit Tenant">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Tangguhkan (Suspend)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                            </button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
        
        <!-- Paginasi (Mockup) -->
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-gray-50/30">
            <span class="text-sm text-gray-500">Menampilkan <span class="font-bold text-gray-800">1</span> sampai <span class="font-bold text-gray-800">2</span> dari <span class="font-bold text-gray-800">18</span> tenant</span>
            <div class="inline-flex rounded-md shadow-sm">
                <button class="px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-200 rounded-l-lg cursor-not-allowed">Sebelumnya</button>
                <button class="px-4 py-2 text-sm font-medium text-[#005ea2] bg-white border-t border-b border-r border-gray-200 rounded-r-lg hover:bg-gray-50 transition-colors">Selanjutnya</button>
            </div>
        </div>
    </div>
@endsection