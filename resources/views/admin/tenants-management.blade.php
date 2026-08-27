@extends('layouts.admin')

@section('title', 'Manajemen Mitra Tenant')

@section('content')
<div x-data="{ addTenantModalOpen: false, searchKeyword: '' }">

    <!-- Quick Stats Khusus Tenant (rounded-2xl cards with hover elevation) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Stat Card 1 (Royal Blue Gradient) -->
        <div class="bg-gradient-to-br from-[#005ea2] to-blue-800 rounded-2xl p-6 text-white shadow-md shadow-blue-900/10 relative overflow-hidden group hover:shadow-xl hover:shadow-blue-900/20 transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
            <div class="absolute -right-4 -bottom-4 opacity-15 text-white group-hover:scale-110 transition-transform duration-500 pointer-events-none">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/>
                </svg>
            </div>
            <p class="text-blue-100 text-xs font-bold uppercase tracking-wider mb-1 relative z-10">Total Mitra Beroperasi</p>
            <h3 class="text-3xl md:text-4xl font-extrabold relative z-10 tracking-tight">18 <span class="text-sm font-medium opacity-80">Gerai Bandara</span></h3>
            <p class="text-xs text-blue-200 mt-2 relative z-10 font-semibold">T1 (12 Gerai) &bull; T2 (6 Gerai)</p>
        </div>
        
        <!-- Stat Card 2 (Pending Verification) -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex justify-between items-center group hover:shadow-xl hover:shadow-slate-200/60 transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Menunggu Verifikasi</p>
                <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">2 <span class="text-xs font-bold text-slate-400">Pendaftar</span></h3>
                <p class="text-xs text-amber-600 font-semibold mt-2 flex items-center">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                    Dokumen belum ditinjau
                </p>
            </div>
            <div class="h-14 w-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        
        <!-- Stat Card 3 (Expiring Contract) -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex justify-between items-center group hover:shadow-xl hover:shadow-slate-200/60 transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Kontrak Segera Habis</p>
                <h3 class="text-3xl md:text-4xl font-extrabold text-rose-600 tracking-tight">1 <span class="text-xs font-bold text-slate-400">Tenant</span></h3>
                <p class="text-xs text-rose-600 font-semibold mt-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    &lt; 30 Hari tersisa
                </p>
            </div>
            <div class="h-14 w-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Toolbar: Search, Filter, Action Button (#005ea2 CTA) -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 mb-6 flex flex-col md:flex-row justify-between items-center gap-4 hover:shadow-md transition-shadow">
        <div class="flex flex-col md:flex-row w-full md:w-auto gap-3">
            <div class="relative w-full md:w-80">
                <input type="text" x-model="searchKeyword" placeholder="Cari nama brand, PT, atau kode ruang..." 
                       class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-xs font-medium focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            
            <select class="border border-slate-200 rounded-xl px-4 py-2.5 text-xs bg-slate-50 hover:bg-white focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none transition-colors cursor-pointer text-slate-700 font-bold">
                <option value="">Filter: Semua Terminal</option>
                <option value="1">Hanya Terminal 1 (T1)</option>
                <option value="2">Hanya Terminal 2 (T2)</option>
            </select>
        </div>
        
        <!-- Highly Prominent Primary CTA Button in #005ea2 -->
        <button @click="addTenantModalOpen = true" 
                class="w-full md:w-auto bg-[#005ea2] hover:bg-[#004a82] active:scale-95 text-white px-6 py-3 rounded-xl text-xs font-extrabold shadow-md shadow-blue-600/25 hover:shadow-lg hover:shadow-blue-600/35 flex items-center justify-center gap-2 transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span>Tambah Mitra Baru</span>
        </button>
    </div>

    <!-- Tabel Tenant Premium (rounded-2xl card container) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-400 text-[11px] uppercase tracking-wider font-extrabold">
                        <th class="px-6 py-4">Perusahaan / Brand</th>
                        <th class="px-6 py-4">Lokasi & Kategori</th>
                        <th class="px-6 py-4">Masa Kontrak</th>
                        <th class="px-6 py-4">Status Operasional</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-slate-100 font-medium">
                    
                    <!-- Tenant 1 -->
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center">
                                <div class="h-12 w-12 flex-shrink-0 bg-blue-50 rounded-2xl border border-blue-100 flex items-center justify-center text-[#005ea2] font-extrabold text-base shadow-xs group-hover:scale-105 transition-transform">
                                    BP
                                </div>
                                <div class="ml-4">
                                    <div class="font-extrabold text-slate-900 text-sm group-hover:text-[#005ea2] transition-colors">Beard Papa's</div>
                                    <div class="text-xs text-slate-500 font-semibold mt-0.5">PT Sebastian Citra Indonesia</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="font-bold text-slate-800">
                                Terminal 1 <span class="bg-slate-100 text-slate-600 font-bold px-2 py-0.5 rounded-md text-[11px] ml-1">EP-01-01</span>
                            </div>
                            <div class="text-[11px] text-[#005ea2] font-extrabold bg-blue-50 border border-blue-100 inline-block px-2.5 py-0.5 rounded-full mt-1.5">
                                F&B &bull; Bakery
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-xs font-extrabold text-slate-800">02 Jan 2025</div>
                            <div class="text-[11px] text-slate-400 mt-0.5 font-semibold">
                                s/d <span class="text-slate-600 font-bold">31 Des 2025</span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200/80 shadow-xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Beroperasi
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right space-x-1">
                            <button class="p-2 text-slate-400 hover:text-[#005ea2] hover:bg-blue-50 rounded-xl transition-all" title="Edit Tenant">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Tangguhkan (Suspend)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Tenant 2 -->
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center">
                                <div class="h-12 w-12 flex-shrink-0 bg-amber-50 rounded-2xl border border-amber-100 flex items-center justify-center text-amber-600 font-extrabold text-base shadow-xs group-hover:scale-105 transition-transform">
                                    AW
                                </div>
                                <div class="ml-4">
                                    <div class="font-extrabold text-slate-900 text-sm group-hover:text-[#005ea2] transition-colors">A&W Restaurant</div>
                                    <div class="text-xs text-slate-500 font-semibold mt-0.5">PT Fast Food Indonesia</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="font-bold text-slate-800">
                                Terminal 1 <span class="bg-slate-100 text-slate-600 font-bold px-2 py-0.5 rounded-md text-[11px] ml-1">EP-01-02</span>
                            </div>
                            <div class="text-[11px] text-amber-700 font-extrabold bg-amber-50 border border-amber-100 inline-block px-2.5 py-0.5 rounded-full mt-1.5">
                                F&B &bull; Fast Food
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-xs font-extrabold text-slate-800">01 Mar 2025</div>
                            <div class="text-[11px] text-slate-400 mt-0.5 font-semibold">
                                s/d <span class="text-slate-600 font-bold">28 Feb 2026</span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200/80 shadow-xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Beroperasi
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right space-x-1">
                            <button class="p-2 text-slate-400 hover:text-[#005ea2] hover:bg-blue-50 rounded-xl transition-all" title="Edit Tenant">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Tangguhkan (Suspend)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                            </button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
        
        <!-- Paginasi (Mockup) -->
        <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between bg-slate-50/50">
            <span class="text-xs text-slate-500 font-semibold">Menampilkan <span class="font-extrabold text-slate-800">1</span> sampai <span class="font-extrabold text-slate-800">2</span> dari <span class="font-extrabold text-slate-800">18</span> mitra tenant</span>
            <div class="inline-flex rounded-xl shadow-xs">
                <button class="px-4 py-2 text-xs font-bold text-slate-300 bg-white border border-slate-200 rounded-l-xl cursor-not-allowed">Sebelumnya</button>
                <button class="px-4 py-2 text-xs font-bold text-[#005ea2] bg-white border-t border-b border-r border-slate-200 rounded-r-xl hover:bg-slate-50 transition-colors">Selanjutnya</button>
            </div>
        </div>
    </div>

    <!-- Modal Form Tambah Mitra Tenant Baru -->
    <div x-show="addTenantModalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" 
         style="display: none;">
        
        <div x-show="addTenantModalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             @click.away="addTenantModalOpen = false"
             class="bg-white rounded-3xl shadow-2xl max-w-xl w-full overflow-hidden border border-slate-100">
            
            <!-- Modal Header -->
            <div class="px-6 py-5 bg-gradient-to-r from-slate-900 via-[#005ea2] to-blue-700 text-white flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-[#8dc63f]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-base leading-tight">Registrasi Mitra Tenant Baru</h3>
                        <p class="text-xs text-blue-100">Tambahkan informasi gerai baru di Terminal Juanda</p>
                    </div>
                </div>
                <button @click="addTenantModalOpen = false" class="text-white/80 hover:text-white p-1.5 rounded-full hover:bg-white/10 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form Content -->
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Brand / Outlet</label>
                        <input type="text" placeholder="Contoh: Starbucks Coffee" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Perusahaan (PT/CV)</label>
                        <input type="text" placeholder="Contoh: PT Sari Coffee Indonesia" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Lokasi Terminal & Kode Space</label>
                        <input type="text" placeholder="Contoh: T1 (Space EP-01-05)" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori F&B</label>
                        <select class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                            <option>Beverages & Coffee</option>
                            <option>Fast Food & Restaurant</option>
                            <option>Bakery & Pastry</option>
                            <option>Snacks & Convenience</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Mulai Kontrak</label>
                        <input type="date" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Akhir Kontrak</label>
                        <input type="date" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end space-x-3">
                <button @click="addTenantModalOpen = false" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-white text-xs font-bold transition-all">
                    Batal
                </button>
                <button @click="addTenantModalOpen = false; alert('Mitra tenant berhasil ditambahkan!')" 
                        class="px-5 py-2.5 rounded-xl bg-[#005ea2] hover:bg-[#004a82] text-white text-xs font-extrabold shadow-md shadow-blue-600/25 hover:shadow-lg transition-all">
                    Simpan Tenant Baru
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
