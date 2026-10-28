@extends('layouts.tenant')

@section('title', 'Manajemen Pesanan')

@section('content')
<div x-data="orderManagement()" class="space-y-6">

    <!-- ========================================================================= -->
    <!-- 1. HEADER HALAMAN & KONTROL CEPAT                                         -->
    <!-- ========================================================================= -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Manajemen Antrean Pesanan</h2>
            </div>
            <p class="text-xs sm:text-sm font-semibold text-slate-500">
                Pantau pesanan masuk secara real-time, koordinasikan proses dapur, dan serah terima hidangan kepada penumpang.
            </p>
        </div>
        
        <div class="flex items-center gap-2.5 shrink-0">
            <a href="{{ url('/tenant/orders/history') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-slate-700 text-xs font-black shadow-2xs transition-all cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Riwayat Pesanan Selesai</span>
            </a>
            
            <button type="button" 
                    @click="refreshData()" 
                    class="bg-white border border-slate-200 hover:border-slate-300 text-slate-700 hover:bg-slate-50 p-2.5 rounded-xl shadow-2xs transition-all cursor-pointer group" 
                    title="Muat Ulang Antrean Pesanan">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-600 group-hover:text-[#005ea2] transition-colors" :class="isRefreshing ? 'animate-spin text-[#005ea2]' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 2. LIVE KPI ANTRIAN DAPUR (4 KARTU METRIK OPERASIONAL)                    -->
    <!-- ========================================================================= -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        
        <!-- Kartu 1: Menunggu Konfirmasi -->
        <button type="button" 
                @click="activeTab = 'menunggu'"
                :class="activeTab === 'menunggu' ? 'ring-2 ring-amber-400 bg-amber-50/40 border-amber-300' : 'bg-white border-slate-200/80 hover:border-slate-300'"
                class="p-4 rounded-2xl border text-left shadow-2xs transition-all cursor-pointer flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Menunggu Respon</span>
                <div class="w-7 h-7 rounded-lg flex items-center justify-center" :class="countMenunggu > 0 ? 'bg-amber-100 text-amber-700 animate-pulse' : 'bg-slate-100 text-slate-500'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-slate-900" x-text="countMenunggu"></span>
                <span class="text-[11px] font-bold" :class="countMenunggu > 0 ? 'text-amber-600' : 'text-slate-400'" x-text="countMenunggu > 0 ? 'Perlu Segera Diproses' : 'Kosong'"></span>
            </div>
        </button>

        <!-- Kartu 2: Sedang Dimasak -->
        <button type="button" 
                @click="activeTab = 'diproses'"
                :class="activeTab === 'diproses' ? 'ring-2 ring-blue-400 bg-blue-50/40 border-blue-300' : 'bg-white border-slate-200/80 hover:border-slate-300'"
                class="p-4 rounded-2xl border text-left shadow-2xs transition-all cursor-pointer flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Sedang Dimasak</span>
                <div class="w-7 h-7 rounded-lg bg-blue-50 text-[#005ea2] flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-slate-900" x-text="countDiproses"></span>
                <span class="text-[11px] font-bold text-blue-600">Proses Dapur/Bar</span>
            </div>
        </button>

        <!-- Kartu 3: Siap Antar / Ambil -->
        <button type="button" 
                @click="activeTab = 'siap'"
                :class="activeTab === 'siap' ? 'ring-2 ring-purple-400 bg-purple-50/40 border-purple-300' : 'bg-white border-slate-200/80 hover:border-slate-300'"
                class="p-4 rounded-2xl border text-left shadow-2xs transition-all cursor-pointer flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Siap Diambil/Diantar</span>
                <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-slate-900" x-text="countSiap"></span>
                <span class="text-[11px] font-bold text-purple-600">Siap Diserahkan</span>
            </div>
        </button>

        <!-- Kartu 4: Total Antrean Aktif -->
        <button type="button" 
                @click="activeTab = 'semua'"
                :class="activeTab === 'semua' ? 'ring-2 ring-[#005ea2]/50 bg-slate-50/80 border-[#005ea2]/40' : 'bg-white border-slate-200/80 hover:border-slate-300'"
                class="p-4 rounded-2xl border text-left shadow-2xs transition-all cursor-pointer flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-500">Total Antrean Aktif</span>
                <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-slate-900" x-text="orders.length"></span>
                <span class="text-[11px] font-bold text-slate-500">Pesanan Berjalan</span>
            </div>
        </button>

    </div>

    <!-- ========================================================================= -->
    <!-- 3. BAR FILTER STATUS & PENCARIAN PESANAN                                  -->
    <!-- ========================================================================= -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-2xs">
        
        <!-- Filter Tabs -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 scrollbar-hide">
            <button type="button" 
                    @click="activeTab = 'semua'" 
                    :class="activeTab === 'semua' ? 'bg-[#005ea2] text-white shadow-xs font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'" 
                    class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap cursor-pointer">
                <span>Semua Pesanan</span>
                <span class="ml-1 px-1.5 py-0.5 rounded-md text-[10px]" :class="activeTab === 'semua' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'" x-text="orders.length"></span>
            </button>

            <button type="button" 
                    @click="activeTab = 'menunggu'" 
                    :class="activeTab === 'menunggu' ? 'bg-amber-500 text-white shadow-xs font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'" 
                    class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center gap-1.5 cursor-pointer">
                <span>Menunggu</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-extrabold" :class="activeTab === 'menunggu' ? 'bg-white text-amber-700' : 'bg-amber-100 text-amber-700'" x-text="countMenunggu"></span>
            </button>

            <button type="button" 
                    @click="activeTab = 'diproses'" 
                    :class="activeTab === 'diproses' ? 'bg-[#005ea2] text-white shadow-xs font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'" 
                    class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center gap-1.5 cursor-pointer">
                <span>Sedang Dimasak</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-extrabold" :class="activeTab === 'diproses' ? 'bg-white text-[#005ea2]' : 'bg-blue-100 text-[#005ea2]'" x-text="countDiproses"></span>
            </button>

            <button type="button" 
                    @click="activeTab = 'siap'" 
                    :class="activeTab === 'siap' ? 'bg-purple-600 text-white shadow-xs font-black' : 'text-slate-600 hover:bg-slate-100 font-bold'" 
                    class="px-4 py-2 rounded-xl text-xs transition-all whitespace-nowrap flex items-center gap-1.5 cursor-pointer">
                <span>Siap Antar/Ambil</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-extrabold" :class="activeTab === 'siap' ? 'bg-white text-purple-700' : 'bg-purple-100 text-purple-700'" x-text="countSiap"></span>
            </button>
        </div>

        <!-- Kolom Pencarian -->
        <div class="relative w-full md:w-72 shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" 
                   x-model="searchQuery" 
                   placeholder="Cari ID Pesanan / Penerbangan..." 
                   class="w-full pl-9 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/15 outline-none transition-all">
            <button type="button" 
                    x-show="searchQuery !== ''" 
                    @click="searchQuery = ''" 
                    class="absolute right-2.5 top-2.5 text-slate-400 hover:text-slate-600">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- 4. TABEL ANTREAN PESANAN LENGKAP & KONTROL MASAK                          -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 text-[10px] uppercase tracking-wider font-black">
                        <th class="px-5 py-3.5">Waktu & Durasi</th>
                        <th class="px-5 py-3.5">ID Pesanan & Flight</th>
                        <th class="px-5 py-3.5">Metode Penyerahan</th>
                        <th class="px-5 py-3.5">Rincian Menu</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-center">Tindakan Operasional</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-slate-100">
                    
                    <template x-for="order in filteredOrders" :key="order.id">
                        <tr class="hover:bg-slate-50/70 transition-colors group">
                            
                            <!-- Kolom 1: Waktu & Durasi Antrean -->
                            <td class="px-5 py-4 align-top">
                                <div class="flex items-center gap-1.5 font-black text-slate-800">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span x-text="formatTime(order.ordered_at)"></span>
                                </div>
                                <div x-show="order.status === 'menunggu' || order.status === 'diproses'" class="mt-2">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider shadow-2xs border"
                                          :class="{
                                              'bg-emerald-50 text-emerald-700 border-emerald-200': getSlaStatus(order.ordered_at).status === 'aman',
                                              'bg-amber-50 text-amber-700 border-amber-200': getSlaStatus(order.ordered_at).status === 'peringatan',
                                              'bg-rose-50 text-rose-700 border-rose-200 animate-pulse': getSlaStatus(order.ordered_at).status === 'terlambat'
                                          }">
                                        <span class="w-1.5 h-1.5 rounded-full" :class="{
                                            'bg-emerald-500': getSlaStatus(order.ordered_at).status === 'aman',
                                            'bg-amber-500': getSlaStatus(order.ordered_at).status === 'peringatan',
                                            'bg-rose-500': getSlaStatus(order.ordered_at).status === 'terlambat'
                                        }"></span>
                                        <span x-text="getSlaStatus(order.ordered_at).label + ' (' + getSlaStatus(order.ordered_at).minutes + 'm)'"></span>
                                    </span>
                                </div>
                            </td>

                            <!-- Kolom 2: ID Pesanan & Flight -->
                            <td class="px-5 py-4 align-top">
                                <span class="font-mono font-black text-[#005ea2] bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100 inline-block" x-text="order.order_code"></span>
                                
                                <div class="mt-2 space-y-0.5">
                                    <div class="font-bold text-slate-800 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                        </svg>
                                        <span x-text="order.flight_number || 'Umum / Transit'"></span>
                                    </div>
                                    <div class="text-[11px] font-semibold text-slate-500 flex items-center gap-1">
                                        <span>Gate:</span>
                                        <span class="font-bold text-slate-700" x-text="order.gate ? 'Gate ' + order.gate : '-'"></span>
                                        <span x-show="order.boarding_time" class="text-slate-400">•</span>
                                        <span x-show="order.boarding_time" class="text-rose-600 font-bold" x-text="'Boarding: ' + formatTime(order.boarding_time)"></span>
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom 3: Metode Penyerahan -->
                            <td class="px-5 py-4 align-top">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-black border"
                                      :class="order.pickup_method === 'diantar' ? 'bg-purple-50 text-purple-700 border-purple-200' : 'bg-slate-100 text-slate-700 border-slate-200'">
                                    <svg x-show="order.pickup_method === 'diantar'" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <svg x-show="order.pickup_method !== 'diantar'" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    <span x-text="order.pickup_method === 'diantar' ? 'Layanan Porter (Diantar)' : 'Ambil di Gerai'"></span>
                                </span>
                                <div x-show="order.pickup_method === 'diantar' && order.delivery_location" class="text-[11px] font-semibold text-purple-700 mt-1">
                                    Lokasi: <span class="font-bold" x-text="order.delivery_location ? order.delivery_location.name : ''"></span>
                                </div>
                            </td>

                            <!-- Kolom 4: Rincian Menu & Pembayaran -->
                            <td class="px-5 py-4 align-top">
                                <div class="space-y-1 mb-2">
                                    <template x-for="item in order.order_items" :key="item.id">
                                        <div class="flex items-start gap-1.5">
                                            <span class="font-black text-[#005ea2] bg-blue-50 px-1.5 py-0.5 rounded text-[10px]" x-text="item.quantity + 'x'"></span>
                                            <span class="font-bold text-slate-800" x-text="item.product_name_snapshot"></span>
                                        </div>
                                    </template>
                                </div>

                                <!-- Catatan Khusus Pesanan untuk Dapur -->
                                <template x-if="order.notes">
                                    <div class="my-2.5 p-2.5 bg-amber-50/90 rounded-xl border border-amber-200/90 text-xs text-amber-900 flex items-start gap-2 shadow-2xs">
                                        <span class="shrink-0 text-amber-600 font-bold text-sm">📝</span>
                                        <div class="leading-snug min-w-0 flex-1">
                                            <span class="font-black uppercase tracking-wider text-[10px] text-amber-800 block">Catatan Pelanggan:</span>
                                            <span class="font-bold italic text-amber-950 break-words text-xs" x-text="order.notes"></span>
                                        </div>
                                    </div>
                                </template>

                                <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[11px] font-bold text-slate-400">Total:</span>
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-wider border"
                                              :class="order.is_paid ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200'"
                                              x-text="order.is_paid ? 'Lunas' : 'Belum Bayar'"></span>
                                    </div>
                                    <span class="font-black text-slate-900" x-text="'Rp ' + Number(order.total_amount).toLocaleString('id-ID')"></span>
                                </div>
                            </td>

                            <!-- Kolom 5: Status -->
                            <td class="px-5 py-4 align-top text-center">
                                <!-- Status Menunggu -->
                                <span x-show="order.status === 'menunggu'" class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-black bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse mr-1"></span>
                                    Menunggu
                                </span>
                                
                                <!-- Status Diproses -->
                                <span x-show="order.status === 'diproses'" class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-black bg-blue-50 text-blue-700 border border-blue-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1"></span>
                                    Sedang Dimasak
                                </span>
                                
                                <!-- Status Siap -->
                                <span x-show="order.status === 'siap'" class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-black bg-purple-50 text-purple-700 border border-purple-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500 mr-1"></span>
                                    Siap Antar/Ambil
                                </span>

                                <!-- Status Selesai -->
                                <span x-show="order.status === 'selesai'" class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1"></span>
                                    Selesai
                                </span>

                                <!-- Status Ditolak -->
                                <span x-show="order.status === 'ditolak'" class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-black bg-rose-50 text-rose-700 border border-rose-200">
                                    Dibatalkan
                                </span>
                            </td>

                            <!-- Kolom 6: Tombol Tindakan Operasional -->
                            <td class="px-5 py-4 align-top text-center">
                                <div class="flex flex-col gap-1.5 max-w-[170px] mx-auto">
                                    
                                    <!-- Aksi untuk Menunggu -->
                                    <template x-if="order.status === 'menunggu'">
                                        <div class="space-y-1.5 w-full">
                                            <button type="button" 
                                                    @click="if (order.is_paid) updateStatus(order.id, 'diproses')" 
                                                    :disabled="!order.is_paid"
                                                    :class="order.is_paid ? 'bg-[#005ea2] hover:bg-[#004b82] active:scale-95 text-white shadow-md shadow-blue-600/20 cursor-pointer' : 'bg-slate-100 text-slate-400 border border-slate-200 cursor-not-allowed'"
                                                    class="w-full py-2 px-3 rounded-xl text-xs font-black transition-all">
                                                <span x-text="order.is_paid ? 'Terima & Masak' : 'Menunggu Bayar'"></span>
                                            </button>
                                            <button type="button" 
                                                    @click="updateStatus(order.id, 'ditolak')" 
                                                    class="w-full bg-white border border-rose-200 hover:bg-rose-50 hover:border-rose-300 text-rose-600 py-1.5 px-3 rounded-xl text-[11px] font-bold transition-all cursor-pointer">
                                                Tolak Pesanan
                                            </button>
                                        </div>
                                    </template>

                                    <!-- Aksi untuk Diproses -->
                                    <template x-if="order.status === 'diproses'">
                                        <button type="button" 
                                                @click="updateStatus(order.id, 'siap')" 
                                                class="w-full bg-[#005ea2] hover:bg-[#004b82] active:scale-95 text-white py-2 px-3 rounded-xl text-xs font-black shadow-md shadow-blue-600/20 transition-all cursor-pointer">
                                            <span x-text="order.pickup_method === 'diantar' ? 'Siap Diantar' : 'Siap Diambil'"></span>
                                        </button>
                                    </template>

                                    <!-- Aksi untuk Siap -->
                                    <template x-if="order.status === 'siap'">
                                        <button type="button" 
                                                @click="updateStatus(order.id, 'selesai')" 
                                                class="w-full bg-[#8dc63f] hover:bg-[#7cb333] active:scale-95 text-slate-900 py-2 px-3 rounded-xl text-xs font-extrabold shadow-md shadow-black/10 transition-all cursor-pointer">
                                            Selesaikan Pesanan
                                        </button>
                                    </template>
                                    
                                    <template x-if="order.status === 'selesai' || order.status === 'ditolak'">
                                        <span class="text-xs font-bold text-slate-400">Terselesaikan</span>
                                    </template>

                                </div>
                            </td>

                        </tr>
                    </template>

                    <!-- Baris Kosong Formal & Berkelas -->
                    <tr x-show="filteredOrders.length === 0">
                        <td colspan="6" class="px-6 py-14 text-center">
                            <div class="max-w-md mx-auto flex flex-col items-center">
                                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-[#005ea2] flex items-center justify-center mb-3 shadow-2xs">
                                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>
                                </div>
                                <h4 class="text-base font-black text-slate-900">Tidak Ada Antrean Pesanan Aktif</h4>
                                <p class="text-xs text-slate-500 font-semibold mt-1 leading-relaxed">
                                    Seluruh pesanan pada status yang dipilih telah selesai diproses. Pesanan baru dari customer terminal bandara akan otomatis masuk secara <em>real-time</em>.
                                </p>
                                <div class="mt-4 flex items-center gap-2">
                                    <button type="button" 
                                            x-show="activeTab !== 'semua' || searchQuery !== ''" 
                                            @click="activeTab = 'semua'; searchQuery = ''" 
                                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-xs font-black text-[#005ea2] shadow-2xs transition cursor-pointer">
                                        <span>Reset Filter & Pencarian</span>
                                    </button>
                                    <a href="{{ url('/tenant/orders/history') }}" 
                                       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-black text-slate-700 transition cursor-pointer">
                                        <span>Buka Riwayat Selesai</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Script to handle dynamic fetching and updates -->
<script nonce="{{ $cspNonce }}">
    document.addEventListener('alpine:init', () => {
        Alpine.data('orderManagement', () => ({
            activeTab: 'semua',
            searchQuery: '',
            orders: @json($orders),
            currentTime: new Date(),
            isRefreshing: false,

            init() {
                setInterval(() => {
                    this.currentTime = new Date();
                }, 10000); // 10 seconds refresh
            },

            refreshData() {
                this.isRefreshing = true;
                setTimeout(() => {
                    window.location.reload();
                }, 400);
            },
            
            getSlaStatus(orderedAt) {
                if (!orderedAt) return { status: 'aman', minutes: 0, label: 'Waktu Aman' };
                const ordered = new Date(orderedAt);
                const diffMs = this.currentTime - ordered;
                const diffMins = Math.floor(diffMs / 60000);

                if (diffMins < 10) {
                    return { status: 'aman', minutes: diffMins, label: 'Waktu Aman' };
                } else if (diffMins >= 10 && diffMins < 15) {
                    return { status: 'peringatan', minutes: diffMins, label: 'Mendekati Batas' };
                } else {
                    return { status: 'terlambat', minutes: diffMins, label: 'Melebihi Estimasi' };
                }
            },

            get filteredOrders() {
                let result = this.orders;
                
                if (this.searchQuery !== '') {
                    const q = this.searchQuery.toLowerCase();
                    result = result.filter(order => {
                        const codeMatch = (order.order_code || '').toLowerCase().includes(q);
                        const flightMatch = (order.flight_number || '').toLowerCase().includes(q);
                        const gateMatch = (order.gate || '').toLowerCase().includes(q);
                        return codeMatch || flightMatch || gateMatch;
                    });
                }

                if (this.activeTab !== 'semua') {
                    result = result.filter(order => order.status === this.activeTab);
                }
                
                return result;
            },
            
            get countMenunggu() {
                return this.orders.filter(order => order.status === 'menunggu').length;
            },
            
            get countDiproses() {
                return this.orders.filter(order => order.status === 'diproses').length;
            },

            get countSiap() {
                return this.orders.filter(order => order.status === 'siap').length;
            },

            formatTime(dateString) {
                if (!dateString) return '--:--';
                const date = new Date(dateString);
                return date.getHours().toString().padStart(2, '0') + ':' + date.getMinutes().toString().padStart(2, '0') + ' WIB';
            },

            async updateStatus(orderId, newStatus) {
                try {
                    const response = await fetch(`/tenant/orders/${orderId}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ status: newStatus })
                    });
                    
                    const data = await response.json();
                    
                        if (data.success) {
                            const index = this.orders.findIndex(o => o.id === orderId);
                            if (index !== -1) {
                                if (newStatus === 'selesai' || newStatus === 'ditolak' || newStatus === 'dibatalkan') {
                                    this.orders.splice(index, 1);
                                } else {
                                    this.orders[index].status = newStatus;
                                }
                            }
                        } else {
                        alert('Gagal mengupdate pesanan: ' + (data.message || 'Unknown error'));
                    }
                } catch (error) {
                    alert('Terjadi kesalahan jaringan.');
                    console.error(error);
                }
            }
        }))
    })
</script>
@endsection