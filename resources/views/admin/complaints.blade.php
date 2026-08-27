@extends('layouts.admin')

@section('title', 'Manajemen Komplain Pelanggan')

@section('content')
<div x-data="{ 
    activeTab: 'all', 
    modalOpen: false, 
    selectedTicket: '#CMP-3021',
    selectedCustomer: 'Budi Santoso',
    selectedTenant: 'A&W Restaurant (T1)',
    selectedIssue: 'Pesanan Terlalu Lama'
}">

    <!-- Header: Judul & Ringkasan Singkat -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <div class="inline-flex items-center space-x-2 bg-rose-50 text-rose-600 border border-rose-100 px-3 py-1 rounded-full text-xs font-bold mb-2">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                <span>Pusat Resolusi Layanan Terminal</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Manajemen Komplain Pelanggan</h1>
            <p class="text-sm text-slate-500 font-medium mt-1">Pantau dan tindak lanjuti kendala pesanan penumpang bandara secara cepat & tepat.</p>
        </div>
        
        <div class="flex items-center space-x-3 bg-white p-2 rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-3.5 py-2 bg-rose-50 text-rose-700 rounded-xl text-xs font-extrabold border border-rose-100 flex items-center shadow-xs">
                <span class="w-2 h-2 rounded-full bg-rose-500 mr-2 animate-pulse"></span>
                2 Urgen
            </div>
            <div class="px-3.5 py-2 bg-blue-50 text-[#005ea2] rounded-xl text-xs font-extrabold border border-blue-100 shadow-xs">
                1 Diproses
            </div>
            <div class="px-3.5 py-2 bg-emerald-50 text-emerald-700 rounded-xl text-xs font-extrabold border border-emerald-100 shadow-xs">
                14 Selesai
            </div>
        </div>
    </div>

    <!-- Filter Tab Navigation (Gojek / Traveloka Super-App Pill Style) -->
    <div class="bg-slate-200/60 p-1.5 rounded-2xl flex space-x-1 overflow-x-auto mb-6 max-w-fit shadow-inner border border-slate-200/40">
        <button @click="activeTab = 'all'" 
                :class="activeTab === 'all' ? 'bg-[#005ea2] text-white shadow-md shadow-blue-600/20 font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                class="px-5 py-2.5 rounded-xl text-xs transition-all duration-200 whitespace-nowrap focus:outline-none">
            Semua Komplain
        </button>
        <button @click="activeTab = 'urgent'" 
                :class="activeTab === 'urgent' ? 'bg-[#005ea2] text-white shadow-md shadow-blue-600/20 font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                class="px-5 py-2.5 rounded-xl text-xs transition-all duration-200 whitespace-nowrap flex items-center gap-1.5 focus:outline-none">
            <span>Butuh Tindakan</span>
            <span class="bg-rose-500 text-white text-[10px] font-extrabold px-1.5 py-0.2 rounded-full">2</span>
        </button>
        <button @click="activeTab = 'processing'" 
                :class="activeTab === 'processing' ? 'bg-[#005ea2] text-white shadow-md shadow-blue-600/20 font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                class="px-5 py-2.5 rounded-xl text-xs transition-all duration-200 whitespace-nowrap flex items-center gap-1.5 focus:outline-none">
            <span>Sedang Diproses</span>
            <span class="bg-blue-200 text-[#005ea2] text-[10px] font-extrabold px-1.5 py-0.2 rounded-full">1</span>
        </button>
        <button @click="activeTab = 'resolved'" 
                :class="activeTab === 'resolved' ? 'bg-[#005ea2] text-white shadow-md shadow-blue-600/20 font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                class="px-5 py-2.5 rounded-xl text-xs transition-all duration-200 whitespace-nowrap focus:outline-none">
            Selesai Ditangani
        </button>
    </div>

    <!-- Tabel Komplain Premium (rounded-2xl with hover elevation) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300">
        
        <!-- Toolbar Tabel -->
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="relative w-full sm:w-80">
                <input type="text" placeholder="Cari kode CMP atau nama penumpang..." 
                       class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-xs font-medium focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none transition-all bg-white placeholder-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            
            <div class="flex items-center space-x-3">
                <button class="text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 px-4 py-2.5 rounded-xl flex items-center transition-all shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter Tambahan
                </button>
            </div>
        </div>

        <!-- Isi Tabel -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-400 text-[11px] uppercase tracking-wider font-extrabold border-b border-slate-100">
                        <th class="px-6 py-4">Tiket & Waktu</th>
                        <th class="px-6 py-4">Detail Pelapor</th>
                        <th class="px-6 py-4">Keluhan Pelanggan</th>
                        <th class="px-6 py-4 text-center">Status Resolusi</th>
                        <th class="px-6 py-4 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-slate-100 font-medium">
                    
                    <!-- Item Komplain 1 (High Priority / Pending) -->
                    <tr class="hover:bg-rose-50/40 transition-colors group relative">
                        <td class="px-6 py-5 align-top">
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-rose-500 rounded-r-sm"></div>
                            <div class="font-extrabold text-slate-900 text-sm flex items-center">
                                #CMP-3021
                                <span class="ml-2 bg-rose-100 text-rose-700 text-[10px] font-extrabold px-2 py-0.5 rounded-full uppercase tracking-wider">Urgent</span>
                            </div>
                            <div class="text-[11px] text-slate-400 mt-1.5 flex items-center font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                13:15 WIB (Hari ini)
                            </div>
                        </td>
                        <td class="px-6 py-5 align-top">
                            <div class="font-bold text-slate-900 text-sm">Budi Santoso</div>
                            <div class="text-xs font-bold text-[#005ea2] mt-1 flex items-center cursor-pointer hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                #ORD-98765
                            </div>
                            <div class="text-[11px] text-slate-500 mt-1 font-semibold">Tenant: <span class="text-slate-800 font-bold">A&W Restaurant (T1)</span></div>
                        </td>
                        <td class="px-6 py-5 align-top max-w-md">
                            <div class="font-bold text-slate-900 mb-1.5 text-xs">Pesanan Terlalu Lama</div>
                            <div class="text-xs leading-relaxed text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100 group-hover:bg-white transition-colors">
                                "Makanan agak lama disiapkan padahal waktu boarding saya sudah dekat. Mohon dibantu agar dipercepat."
                            </div>
                        </td>
                        <td class="px-6 py-5 align-top text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200/80 shadow-xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Menunggu Respon
                            </span>
                        </td>
                        <td class="px-6 py-5 align-top text-right">
                            <!-- Prominent Call To Action Button #005ea2 -->
                            <button @click="modalOpen = true; selectedTicket = '#CMP-3021'; selectedCustomer = 'Budi Santoso'; selectedTenant = 'A&W Restaurant (T1)'; selectedIssue = 'Pesanan Terlalu Lama'"
                                    class="bg-[#005ea2] hover:bg-[#004a82] active:scale-95 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 shadow-md shadow-blue-600/20 hover:shadow-lg hover:shadow-blue-600/30 flex items-center justify-center gap-1.5 ml-auto">
                                <span>Tindak Lanjuti</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <!-- Item Komplain 2 (Sedang Diproses) -->
                    <tr class="hover:bg-slate-50/80 transition-colors group relative">
                        <td class="px-6 py-5 align-top">
                            <div class="font-extrabold text-slate-900 text-sm">#CMP-3019</div>
                            <div class="text-[11px] text-slate-400 mt-1.5 flex items-center font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Kemarin, 09:40 WIB
                            </div>
                        </td>
                        <td class="px-6 py-5 align-top">
                            <div class="font-bold text-slate-900 text-sm">Siti Rahmawati</div>
                            <div class="text-xs font-bold text-[#005ea2] mt-1 hover:underline cursor-pointer">#ORD-98712</div>
                            <div class="text-[11px] text-slate-500 mt-1 font-semibold">Tenant: <span class="text-slate-800 font-bold">Beard Papa's (T1)</span></div>
                        </td>
                        <td class="px-6 py-5 align-top max-w-md">
                            <div class="font-bold text-slate-900 mb-1.5 text-xs">Pesanan Tidak Sesuai</div>
                            <div class="text-xs leading-relaxed text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100 group-hover:bg-white transition-colors">
                                "Saya pesan varian Vanilla tapi yang dikirim varian Coklat. Mohon konfirmasinya."
                            </div>
                        </td>
                        <td class="px-6 py-5 align-top text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-extrabold bg-blue-50 text-[#005ea2] border border-blue-200/80 shadow-xs">
                                <svg class="animate-spin h-3 w-3 text-[#005ea2]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Dalam Investigasi
                            </span>
                        </td>
                        <td class="px-6 py-5 align-top text-right">
                            <button @click="modalOpen = true; selectedTicket = '#CMP-3019'; selectedCustomer = 'Siti Rahmawati'; selectedTenant = 'Beard Papa\'s (T1)'; selectedIssue = 'Pesanan Tidak Sesuai'"
                                    class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-4 py-2.5 rounded-xl text-xs font-bold transition-all shadow-xs flex items-center justify-center gap-1.5 ml-auto">
                                Lihat Detail
                            </button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
        
        <!-- Footer / Paginasi -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <span class="text-xs text-slate-500 font-semibold">Menampilkan <span class="font-extrabold text-slate-800">2</span> komplain aktif di sistem.</span>
        </div>
    </div>

    <!-- Modal Interaktif Tindak Lanjuti Komplain (Traveloka / Gojek Slide Modal) -->
    <div x-show="modalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" 
         style="display: none;">
        
        <div x-show="modalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             @click.away="modalOpen = false"
             class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-100">
            
            <!-- Modal Header -->
            <div class="px-6 py-5 bg-gradient-to-r from-slate-900 to-[#005ea2] text-white flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-base leading-tight">Resolusi Tiket <span x-text="selectedTicket"></span></h3>
                        <p class="text-xs text-blue-100" x-text="selectedTenant"></p>
                    </div>
                </div>
                <button @click="modalOpen = false" class="text-white/80 hover:text-white p-1.5 rounded-full hover:bg-white/10 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content Form -->
            <div class="p-6 space-y-4">
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <div class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Pelapor & Kendala</div>
                    <div class="font-bold text-slate-800 text-sm" x-text="selectedCustomer"></div>
                    <div class="text-xs font-semibold text-rose-600 mt-0.5" x-text="selectedIssue"></div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Update Status Tiket</label>
                    <select class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                        <option value="investigation">Sedang Diproses (Investigasi)</option>
                        <option value="contacted_tenant">Menghubungi Tenant Bersangkutan</option>
                        <option value="resolved">Selesai (Berikan Kompensasi/Penjelasan)</option>
                        <option value="rejected">Ditolak / Tidak Valid</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Catatan Tindakan Admin</label>
                    <textarea rows="3" placeholder="Tuliskan catatan tindak lanjut atau solusi yang diberikan..." 
                              class="w-full border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none placeholder-slate-400"></textarea>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end space-x-3">
                <button @click="modalOpen = false" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-white text-xs font-bold transition-all">
                    Batal
                </button>
                <button @click="modalOpen = false; alert('Status komplain berhasil diperbarui!')" 
                        class="px-5 py-2.5 rounded-xl bg-[#005ea2] hover:bg-[#004a82] text-white text-xs font-extrabold shadow-md shadow-blue-600/20 hover:shadow-lg transition-all">
                    Simpan & Kirim Notifikasi
                </button>
            </div>
        </div>
    </div>

</div>
@endsection