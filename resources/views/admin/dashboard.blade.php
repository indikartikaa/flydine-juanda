@extends('layouts.admin')

@section('title', 'Ringkasan Sistem')

@section('content')
<div x-data="{ 
    showAllModal: false, 
    filterTab: 'all', 
    modalTab: 'all',
    modalSearch: '',
    isRefreshing: false,
    refreshFeed() {
        this.isRefreshing = true;
        setTimeout(() => {
            window.location.reload();
        }, 400);
    }
}" class="space-y-6 pb-12">

    <!-- Hero Command Center (Sleek Aviation Navy Gradient with Glassmorphic Badges) -->
    <div class="relative rounded-3xl p-6 sm:p-7 text-white shadow-xl shadow-blue-950/15 overflow-hidden"
         style="background: linear-gradient(135deg, #09131f 0%, #005ea2 50%, #1e40af 100%);">
        
        <!-- Background Radar Accent -->
        <div class="absolute -right-12 -bottom-12 opacity-10 pointer-events-none select-none">
            <svg class="w-96 h-96 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
            </svg>
        </div>
        <div class="absolute top-0 right-1/3 w-64 h-64 bg-sky-400/10 rounded-full filter blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-2.5 max-w-2xl">
                <!-- Status Pills Bar -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 px-3 py-1 rounded-full text-[11px] font-bold text-blue-100">
                        <span class="w-2 h-2 rounded-full bg-[#8dc63f] animate-pulse"></span>
                        <span>Sistem Bandara Juanda Online</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 bg-emerald-500/20 backdrop-blur-md border border-emerald-400/30 px-3 py-1 rounded-full text-[11px] font-semibold text-emerald-200">
                        <span>✈️</span>
                        <span>Terminal 1 & 2 Aktif</span>
                    </span>
                    <span class="inline-flex items-center gap-1 bg-white/10 backdrop-blur-md border border-white/15 px-2.5 py-1 rounded-full text-[11px] font-medium text-blue-200">
                        <span>⏱️ SOP Saji &le; 15m</span>
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                    Selamat Datang, Admin Ops! 👋
                </h1>
                <p class="text-blue-100/90 text-xs sm:text-sm leading-relaxed font-medium">
                    Pusat pemantauan real-time untuk <strong>{{ $total_tenants }} mitra F&B</strong>, pergerakan pesanan penumpang, dan resolusi keluhan layanan di seluruh gerai Bandara Internasional Juanda.
                </p>
            </div>
            
            <!-- Quick Actions Buttons -->
            <div class="flex items-center flex-wrap gap-2.5 shrink-0">
                <a href="{{ route('admin.tenants.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 active:bg-white/25 backdrop-blur-md text-white border border-white/20 text-xs font-black transition shadow-2xs">
                    <svg class="w-4 h-4 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Kelola Tenant</span>
                </a>

                <a href="{{ route('admin.complaints') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#8dc63f] hover:bg-[#7cb335] active:bg-[#6ea12c] text-slate-900 text-xs font-black transition shadow-md shadow-[#8dc63f]/25">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span>Resolusi Komplain ({{ $open_complaints }})</span>
                    @if($open_complaints > 0)
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                    @endif
                </a>
            </div>
        </div>
    </div>

    <!-- 4 Modern Stat Cards (Unified, Sleek & Informative) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        
        <!-- Card 1: Mitra Terdaftar -->
        <a href="{{ route('admin.tenants.index') }}" 
           class="group bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs hover:shadow-md hover:border-blue-400 transition-all duration-200 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Mitra Terdaftar</span>
                <span class="h-9 w-9 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black group-hover:scale-105 transition-transform">
                    🏢
                </span>
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900 tracking-tight">{{ $total_tenants }}</span>
                    <span class="text-xs font-bold text-slate-500">Tenant</span>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="font-bold text-slate-500">T1: <strong>{{ $t1_tenants }}</strong> &bull; T2: <strong>{{ $t2_tenants }}</strong></span>
                    <span class="font-black text-blue-700 bg-blue-50 px-2 py-0.5 rounded">100% Aktif</span>
                </div>
            </div>
        </a>

        <!-- Card 2: Mitra Beroperasi -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs hover:shadow-md hover:border-emerald-400 transition-all duration-200 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Mitra Beroperasi</span>
                <span class="h-9 w-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black">
                    🛡️
                </span>
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900 tracking-tight">{{ $active_tenants }}</span>
                    <span class="text-xs font-bold text-emerald-600">Gerai Buka</span>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="font-semibold text-slate-500 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Semua gerai siap saji
                    </span>
                    <span class="font-black text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">Ready</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Menunggu Respon -->
        <a href="{{ route('admin.complaints') }}" 
           class="group bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs hover:shadow-md hover:border-amber-400 transition-all duration-200 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Menunggu Respon</span>
                <span class="h-9 w-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-black group-hover:scale-105 transition-transform">
                    🔔
                </span>
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900 tracking-tight">{{ $open_complaints }}</span>
                    <span class="text-xs font-bold text-amber-600">Tiket Baru</span>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="font-bold text-slate-500">{{ $in_progress_complaints }} sedang diproses</span>
                    @if($open_complaints > 0)
                    <span class="font-black text-rose-700 bg-rose-50 px-2 py-0.5 rounded animate-pulse">Butuh Cepat!</span>
                    @else
                    <span class="font-black text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">Tuntas</span>
                    @endif
                </div>
            </div>
        </a>

        <!-- Card 4: Pesanan Hari Ini -->
        <a href="{{ route('admin.analytics.drilldown.orders') }}" 
           class="group bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs hover:shadow-md hover:border-indigo-400 transition-all duration-200 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Pesanan Hari Ini</span>
                <span class="h-9 w-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black group-hover:scale-105 transition-transform">
                    🛍️
                </span>
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900 tracking-tight">{{ $today_orders }}</span>
                    <span class="text-xs font-bold text-indigo-600">Transaksi Aktif</span>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="font-bold text-slate-500">
                        @if($today_revenue > 0)
                        Rp {{ number_format($today_revenue, 0, ',', '.') }}
                        @else
                        Live pergerakan terminal
                        @endif
                    </span>
                    <span class="font-black text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded">Realtime</span>
                </div>
            </div>
        </a>

    </div>

    <!-- 2-Column Responsive Workspace: Live Audit Trail (Left 8-cols) & Terminal Quick Radar (Right 4-cols) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Kolom Kiri (8 Kolom): Live Audit Trail & Aktivitas Sistem -->
        <div class="lg:col-span-8 bg-white rounded-2xl shadow-2xs border border-slate-200/90 overflow-hidden flex flex-col justify-between">
            <div>
                <!-- Header Toolbar: Title, Filter Pills, and Refresh Button -->
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-xl bg-[#005ea2]/10 text-[#005ea2]">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="font-black text-slate-900 text-sm sm:text-base">Audit Trail & Aktivitas Sistem</h2>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    {{ count($all_logs) }} Rekaman
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 font-medium">Log riil database dari pesanan, komplain, dan audit keamanan sistem.</p>
                        </div>
                    </div>

                    <!-- Right Controls: Filter Tabs & Tombol Lihat Semua -->
                    <div class="flex items-center gap-2 self-start sm:self-auto">
                        <!-- Tombol Segarkan Log Realtime -->
                        <button type="button" @click="refreshFeed()" 
                                title="Perbarui data terbaru dari database"
                                class="p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-200/60 transition cursor-pointer border border-slate-200/80 bg-white">
                            <svg class="w-4 h-4" :class="isRefreshing ? 'animate-spin text-[#005ea2]' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>

                        <!-- Tombol Lihat Semua Modal -->
                        <button type="button" 
                                @click="showAllModal = true; modalTab = 'all'; modalSearch = ''" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black text-[#005ea2] bg-blue-50 hover:bg-blue-100 transition border border-blue-200 cursor-pointer shadow-2xs">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span>Lihat Semua</span>
                        </button>
                    </div>
                </div>

                <!-- Live Quick Filter Pills -->
                <div class="px-6 py-2.5 bg-slate-50/40 border-b border-slate-100 flex items-center gap-1.5 overflow-x-auto text-xs font-bold">
                    <button type="button" @click="filterTab = 'all'" 
                            class="px-2.5 py-1 rounded-lg transition cursor-pointer"
                            :class="filterTab === 'all' ? 'bg-slate-900 text-white font-black' : 'text-slate-600 hover:bg-slate-200/60'">
                        Semua
                    </button>
                    <button type="button" @click="filterTab = 'order'" 
                            class="px-2.5 py-1 rounded-lg transition cursor-pointer"
                            :class="filterTab === 'order' ? 'bg-indigo-600 text-white font-black' : 'text-slate-600 hover:bg-slate-200/60'">
                        🛒 Pesanan
                    </button>
                    <button type="button" @click="filterTab = 'complaint'" 
                            class="px-2.5 py-1 rounded-lg transition cursor-pointer"
                            :class="filterTab === 'complaint' ? 'bg-amber-600 text-white font-black' : 'text-slate-600 hover:bg-slate-200/60'">
                        ⚠️ Komplain
                    </button>
                    <button type="button" @click="filterTab = 'system'" 
                            class="px-2.5 py-1 rounded-lg transition cursor-pointer"
                            :class="filterTab === 'system' ? 'bg-emerald-600 text-white font-black' : 'text-slate-600 hover:bg-slate-200/60'">
                        🔐 Log Sistem
                    </button>
                </div>

                <!-- Feed Aktivitas List -->
                <div class="p-5 sm:p-6 space-y-3">
                    @forelse($recent_logs as $log)
                    <div x-show="filterTab === 'all' || filterTab === '{{ $log['type'] }}'"
                         x-transition
                         class="flex items-start justify-between gap-3 p-3.5 rounded-xl border border-slate-100 hover:border-slate-200 hover:bg-slate-50/50 transition">
                        
                        <div class="flex items-start gap-3 min-w-0">
                            <span class="w-9 h-9 rounded-xl {{ $log['icon_bg'] }} font-black text-xs flex items-center justify-center shrink-0 shadow-2xs mt-0.5">
                                {{ $log['badge'] }}
                            </span>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <span class="font-black text-slate-900 text-xs sm:text-sm tracking-tight">{{ $log['title'] }}</span>
                                    <span class="px-2 py-0.2 rounded text-[10px] font-bold border {{ $log['badge_color'] }}">
                                        {{ $log['category'] }}
                                    </span>
                                    <span class="text-[10px] font-semibold text-slate-500 bg-slate-100 px-1.5 py-0.2 rounded">
                                        {{ $log['terminal'] }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-600 font-medium leading-relaxed">
                                    {{ $log['description'] }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col items-end gap-1.5 shrink-0">
                            <span class="text-[11px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">
                                {{ $log['time_formatted'] }}
                            </span>
                            @if(isset($log['link']))
                            <a href="{{ $log['link'] }}" 
                               class="text-[11px] font-bold text-[#005ea2] hover:underline flex items-center gap-0.5">
                                <span>Detail</span>
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-slate-400 font-medium text-xs">
                        Belum ada aktivitas yang terekam hari ini.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Bottom Indicator -->
            <div class="px-6 py-3 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between text-xs text-slate-500">
                <span class="flex items-center gap-1.5 font-medium">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Sinkronisasi otomatis dengan database PostgreSQL/SQLite
                </span>
                <button type="button" @click="showAllModal = true; modalTab = 'all'; modalSearch = ''" 
                        class="font-black text-[#005ea2] hover:underline cursor-pointer">
                    Buka Log Lengkap &rarr;
                </button>
            </div>
        </div>

        <!-- Kolom Kanan (4 Kolom): Radar Terminal Juanda & Akses Cepat Pintar -->
        <div class="lg:col-span-4 space-y-5">

            <!-- Card 1: Radar Terminal Juanda (Ringkas & Tidak Penuh) -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs">
                <div class="flex items-center justify-between mb-3.5 pb-2.5 border-b border-slate-100">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                        <span>✈️</span>
                        <span>Radar Terminal Juanda</span>
                    </h3>
                    <span class="text-[10px] font-black bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded">100% Siap</span>
                </div>

                <div class="space-y-3 text-xs">
                    <!-- T1 -->
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                        <div class="flex items-center justify-between font-bold text-slate-800 mb-1">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                Terminal 1 (Domestik)
                            </span>
                            <span class="font-black text-[#005ea2]">{{ $t1_active }}/{{ $t1_tenants }} Gerai</span>
                        </div>
                        <p class="text-[11px] text-slate-500">Gate 1 - 12, Food Court & Kedatangan</p>
                    </div>

                    <!-- T2 -->
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                        <div class="flex items-center justify-between font-bold text-slate-800 mb-1">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                                Terminal 2 (Internasional)
                            </span>
                            <span class="font-black text-purple-700">{{ $t2_active }}/{{ $t2_tenants }} Gerai</span>
                        </div>
                        <p class="text-[11px] text-slate-500">Lobby & Ruang Keberangkatan Umroh</p>
                    </div>
                </div>
            </div>

            <!-- Card 2: Akses Pintas Modul Utama (Sleek List) -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs">
                <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                        <span>⚡</span>
                        <span>Akses Cepat Modul</span>
                    </h3>
                    <span class="text-[10px] font-extrabold text-slate-400">Shortcuts</span>
                </div>

                <div class="space-y-1.5">
                    <a href="{{ route('admin.executive-dashboard') }}" 
                       class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition group">
                        <div class="flex items-center gap-2.5">
                            <span class="p-1.5 rounded-lg bg-indigo-50 text-indigo-700 font-bold text-xs">📊</span>
                            <div>
                                <span class="text-xs font-black text-slate-800 group-hover:text-[#005ea2]">Analitik Eksekutif</span>
                                <p class="text-[10px] text-slate-400">Tren Penjualan & Kinerja SLA</p>
                            </div>
                        </div>
                        <span class="text-slate-400 text-xs font-black group-hover:text-[#005ea2]">&rarr;</span>
                    </a>

                    <a href="{{ route('admin.analytics.whatif') }}" 
                       class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition group">
                        <div class="flex items-center gap-2.5">
                            <span class="p-1.5 rounded-lg bg-emerald-50 text-emerald-700 font-bold text-xs">🎯</span>
                            <div>
                                <span class="text-xs font-black text-slate-800 group-hover:text-emerald-700">What-If Simulator</span>
                                <p class="text-[10px] text-slate-400">Kapasitas Staf & Target Omset</p>
                            </div>
                        </div>
                        <span class="text-slate-400 text-xs font-black group-hover:text-emerald-700">&rarr;</span>
                    </a>

                    <a href="{{ route('admin.fraud') }}" 
                       class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition group">
                        <div class="flex items-center gap-2.5">
                            <span class="p-1.5 rounded-lg bg-rose-50 text-rose-700 font-bold text-xs">🛡️</span>
                            <div>
                                <span class="text-xs font-black text-slate-800 group-hover:text-rose-700">Fraud Monitoring</span>
                                <p class="text-[10px] text-slate-400">Deteksi Anomali & Keamanan</p>
                            </div>
                        </div>
                        <span class="text-slate-400 text-xs font-black group-hover:text-rose-700">&rarr;</span>
                    </a>

                    <a href="{{ route('admin.analytics.customers') }}" 
                       class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition group">
                        <div class="flex items-center gap-2.5">
                            <span class="p-1.5 rounded-lg bg-blue-50 text-blue-700 font-bold text-xs">👥</span>
                            <div>
                                <span class="text-xs font-black text-slate-800 group-hover:text-[#005ea2]">Segmentasi Pelanggan</span>
                                <p class="text-[10px] text-slate-400">Profil & Frekuensi Belanja</p>
                            </div>
                        </div>
                        <span class="text-slate-400 text-xs font-black group-hover:text-[#005ea2]">&rarr;</span>
                    </a>
                </div>
            </div>

        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- MODAL POPUP: RIWAYAT AUDIT TRAIL LENGKAP & PENCARIAN (INTERAKTIF)         -->
    <!-- ========================================================================= -->
    <div x-show="showAllModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="showAllModal = false"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 sm:p-6" 
         style="display: none;">
        
        <div @click.away="showAllModal = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="w-full max-w-2xl bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[85vh]">
            
            <!-- Modal Header -->
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Audit Trail & Riwayat Aktivitas Sistem</h3>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Catatan riil pesanan, keluhan komplain, dan log sistem bandara</p>
                </div>
                <button type="button" @click="showAllModal = false" 
                        class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200 transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Filter & Search Bar -->
            <div class="p-4 sm:px-6 bg-slate-50/60 border-b border-slate-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                <!-- Category Tabs -->
                <div class="inline-flex rounded-xl p-1 bg-slate-200/80 text-xs font-bold">
                    <button type="button" @click="modalTab = 'all'" 
                            class="px-3 py-1.5 rounded-lg transition cursor-pointer"
                            :class="modalTab === 'all' ? 'bg-white text-slate-900 shadow-2xs font-extrabold' : 'text-slate-600 hover:text-slate-900'">
                        Semua ({{ count($all_logs) }})
                    </button>
                    <button type="button" @click="modalTab = 'order'" 
                            class="px-3 py-1.5 rounded-lg transition cursor-pointer"
                            :class="modalTab === 'order' ? 'bg-white text-indigo-700 shadow-2xs font-extrabold' : 'text-slate-600 hover:text-slate-900'">
                        Pesanan
                    </button>
                    <button type="button" @click="modalTab = 'complaint'" 
                            class="px-3 py-1.5 rounded-lg transition cursor-pointer"
                            :class="modalTab === 'complaint' ? 'bg-white text-amber-700 shadow-2xs font-extrabold' : 'text-slate-600 hover:text-slate-900'">
                        Komplain
                    </button>
                    <button type="button" @click="modalTab = 'system'" 
                            class="px-3 py-1.5 rounded-lg transition cursor-pointer"
                            :class="modalTab === 'system' ? 'bg-white text-emerald-700 shadow-2xs font-extrabold' : 'text-slate-600 hover:text-slate-900'">
                        Sistem
                    </button>
                </div>

                <!-- Search Input -->
                <div class="relative w-full sm:w-60">
                    <input type="text" x-model="modalSearch" 
                           placeholder="Cari log atau tenant..." 
                           class="w-full pl-8 pr-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Modal Scrollable Content List -->
            <div class="p-6 overflow-y-auto space-y-3 flex-1 max-h-[50vh]">
                @foreach($all_logs as $index => $log)
                <div x-show="(modalTab === 'all' || modalTab === '{{ $log['type'] }}') && (modalSearch === '' || '{{ strtolower(addslashes($log['title'] . ' ' . $log['description'] . ' ' . $log['tenant_name'] . ' ' . $log['terminal'])) }}'.includes(modalSearch.toLowerCase()))"
                     class="flex items-start justify-between gap-3 p-3.5 rounded-xl border border-slate-100 hover:border-slate-200 hover:bg-slate-50 transition">
                    
                    <div class="flex items-start gap-3 min-w-0">
                        <span class="w-9 h-9 rounded-xl {{ $log['icon_bg'] }} font-extrabold text-xs flex items-center justify-center shrink-0 shadow-2xs mt-0.5">
                            {{ $log['badge'] }}
                        </span>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-0.5">
                                <span class="font-extrabold text-slate-900 text-xs sm:text-sm">{{ $log['title'] }}</span>
                                <span class="px-2 py-0.2 rounded text-[10px] font-bold border {{ $log['badge_color'] }}">
                                    {{ $log['category'] }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                {{ $log['description'] }}
                            </p>
                            <span class="text-[11px] text-slate-400 block mt-1">
                                {{ $log['date_formatted'] }} WIB
                            </span>
                        </div>
                    </div>

                    @if(isset($log['link']))
                    <a href="{{ $log['link'] }}" 
                       class="shrink-0 px-3 py-1 rounded-lg text-xs font-bold text-[#005ea2] bg-blue-50 hover:bg-blue-100 transition self-center">
                        Buka
                    </a>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Total <strong>{{ count($all_logs) }}</strong> aktivitas tersimpan di database.</span>
                <button type="button" @click="showAllModal = false" 
                        class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-black transition cursor-pointer">
                    Tutup
                </button>
            </div>

        </div>
    </div>

</div>
@endsection