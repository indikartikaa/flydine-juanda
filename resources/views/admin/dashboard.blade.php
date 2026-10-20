@extends('layouts.admin')

@section('title', 'Ringkasan Sistem')

@section('content')
<div x-data="{ 
    showAllModal: false, 
    filterTab: 'all', 
    modalTab: 'all',
    modalSearch: '',
    allLogs: {{ Js::from($all_logs) }},
    get visibleLogs() {
        if (this.filterTab === 'all') {
            return this.allLogs.slice(0, 6);
        }
        return this.allLogs.filter(item => item.type === this.filterTab).slice(0, 6);
    },
    get filteredModalLogs() {
        return this.allLogs.filter(item => {
            const matchesTab = (this.modalTab === 'all' || item.type === this.modalTab);
            if (!matchesTab) return false;
            if (!this.modalSearch) return true;
            const term = this.modalSearch.toLowerCase();
            const searchTarget = (item.title + ' ' + item.description + ' ' + (item.tenant_name || '') + ' ' + (item.terminal || '')).toLowerCase();
            return searchTarget.includes(term);
        });
    }
}" 
x-effect="document.body.style.overflow = showAllModal ? 'hidden' : ''"
class="space-y-6 pb-12">

    <!-- Hero Command Center (Clean Corporate FlyDine Blue Theme) -->
    <div class="relative rounded-3xl p-6 sm:p-7 text-white shadow-xl shadow-[#005ea2]/15 border border-[#005ea2]/20 overflow-hidden"
         style="background: linear-gradient(135deg, #005ea2 0%, #00487d 100%);">
        
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-2 max-w-2xl">
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white flex items-center gap-2">
                    <span>Selamat Datang, Admin Ops!</span>
                    <span>👋</span>
                </h1>
                <p class="text-blue-100/90 text-xs sm:text-sm leading-relaxed font-medium">
                    Pusat pemantauan real-time untuk <strong class="text-white font-bold">{{ $total_tenants }} mitra F&B</strong>, pergerakan pesanan penumpang, dan resolusi keluhan layanan di seluruh gerai Bandara Internasional Juanda.
                </p>
            </div>
            
            <!-- Quick Actions Buttons (Clean Professional Styling) -->
            <div class="flex items-center flex-wrap gap-2.5 shrink-0">
                <a href="{{ route('admin.tenants.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 active:bg-white/20 text-white border border-white/25 text-xs font-bold transition shadow-2xs">
                    <svg class="w-4 h-4 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Kelola Tenant</span>
                </a>

                <a href="{{ route('admin.complaints') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-blue-50 active:bg-white text-[#005ea2] text-xs font-black transition shadow-sm">
                    <svg class="w-4 h-4 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span>Resolusi Komplain</span>
                    @if($open_complaints > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-500 text-white">
                        {{ $open_complaints }}
                    </span>
                    @else
                    <span class="text-slate-400 font-normal text-[11px]">(0)</span>
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
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </span>
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900 tracking-tight">{{ $total_tenants }}</span>
                    <span class="text-xs font-bold text-slate-500">Tenant</span>
                </div>
                <div class="mt-2 text-[11px] font-medium text-slate-400">
                    T1: {{ $t1_tenants }} &bull; T2: {{ $t2_tenants }}
                </div>
            </div>
        </a>

        <!-- Card 2: Mitra Beroperasi -->
        <a href="{{ route('admin.tenants.index', ['status' => 'active']) }}" 
           class="group bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs hover:shadow-md hover:border-emerald-400 transition-all duration-200 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Mitra Beroperasi</span>
                <span class="h-9 w-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black group-hover:scale-105 transition-transform">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900 tracking-tight">{{ $active_tenants }}</span>
                    <span class="text-xs font-bold text-emerald-600">Gerai Buka</span>
                </div>
                <div class="mt-2 text-[11px] font-medium text-emerald-600 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>Semua gerai siap saji</span>
                </div>
            </div>
        </a>

        <!-- Card 3: Menunggu Respon -->
        <a href="{{ route('admin.complaints') }}" 
           class="group bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs hover:shadow-md hover:border-amber-400 transition-all duration-200 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Menunggu Respon</span>
                <span class="h-9 w-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-black group-hover:scale-105 transition-transform">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </span>
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900 tracking-tight">{{ $open_complaints }}</span>
                    <span class="text-xs font-bold text-amber-600">Tiket Baru</span>
                </div>
                <div class="mt-2 text-[11px] font-medium text-slate-500">
                    {{ $in_progress_complaints }} sedang diproses
                </div>
            </div>
        </a>

        <!-- Card 4: Pesanan Hari Ini -->
        <a href="{{ route('admin.analytics.drilldown') }}" 
           class="group bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs hover:shadow-md hover:border-indigo-400 transition-all duration-200 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Pesanan Hari Ini</span>
                <span class="h-9 w-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black group-hover:scale-105 transition-transform">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </span>
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900 tracking-tight">{{ $today_orders }}</span>
                    <span class="text-xs font-bold text-indigo-600">Transaksi Aktif</span>
                </div>
                <div class="mt-2 text-[11px] font-medium text-slate-400">
                    @if($today_revenue > 0)
                    Rp {{ number_format($today_revenue, 0, ',', '.') }}
                    @else
                    Live pergerakan terminal
                    @endif
                </div>
            </div>
        </a>

    </div>

    <!-- Live Audit Trail & Aktivitas Sistem (Full Width) -->
    <div class="bg-white rounded-2xl shadow-2xs border border-slate-200/90 overflow-hidden flex flex-col justify-between">
        <div>
            <!-- Header Toolbar: Title, Badges, and Lihat Semua Button -->
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-xl bg-[#005ea2] text-white shrink-0 shadow-xs">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h2 class="font-black text-slate-900 text-sm sm:text-base">Audit Trail & Aktivitas Sistem</h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-blue-50 text-[#005ea2] border border-blue-200">
                                {{ count($all_logs) }} Rekaman
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 font-medium">Log riil database dari pesanan, komplain, dan audit keamanan sistem.</p>
                    </div>
                </div>

                <!-- Right Controls: Tombol Lihat Semua Modal -->
                <div class="flex items-center gap-2 self-start sm:self-auto shrink-0">
                    <button type="button" 
                            @click="showAllModal = true; modalTab = 'all'; modalSearch = ''" 
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-black text-[#005ea2] bg-blue-50 hover:bg-blue-100 transition border border-blue-200 cursor-pointer shadow-2xs">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span>Lihat Semua</span>
                    </button>
                </div>
            </div>

            <!-- Live Quick Filter Tabs -->
            <div class="px-5 sm:px-6 py-2.5 bg-slate-50/50 border-b border-slate-100 flex items-center gap-2 overflow-x-auto text-xs font-bold">
                <button type="button" @click="filterTab = 'all'" 
                        class="px-3.5 py-1.5 rounded-lg transition cursor-pointer"
                        :class="filterTab === 'all' ? 'bg-[#005ea2] text-white font-extrabold shadow-xs' : 'text-slate-600 hover:bg-slate-200/60'">
                    Semua
                </button>
                <button type="button" @click="filterTab = 'order'" 
                        class="px-3.5 py-1.5 rounded-lg transition cursor-pointer"
                        :class="filterTab === 'order' ? 'bg-[#005ea2] text-white font-extrabold shadow-xs' : 'text-slate-600 hover:bg-slate-200/60'">
                    Pesanan
                </button>
                <button type="button" @click="filterTab = 'complaint'" 
                        class="px-3.5 py-1.5 rounded-lg transition cursor-pointer"
                        :class="filterTab === 'complaint' ? 'bg-amber-600 text-white font-extrabold shadow-xs' : 'text-slate-600 hover:bg-slate-200/60'">
                    Komplain
                </button>
                <button type="button" @click="filterTab = 'system'" 
                        class="px-3.5 py-1.5 rounded-lg transition cursor-pointer"
                        :class="filterTab === 'system' ? 'bg-indigo-600 text-white font-extrabold shadow-xs' : 'text-slate-600 hover:bg-slate-200/60'">
                    Log Sistem
                </button>
            </div>

            <!-- Feed Aktivitas List -->
            <div class="p-5 sm:p-6 space-y-3">
                <template x-for="log in visibleLogs" :key="log.id">
                    <div class="flex items-start justify-between gap-3 p-3.5 rounded-xl border border-slate-100 hover:border-slate-200 hover:bg-slate-50/50 transition">
                        <div class="flex items-start gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 border mt-0.5"
                                 :class="{
                                     'bg-blue-50 border-blue-200 text-[#005ea2]': log.type === 'order',
                                     'bg-amber-50 border-amber-200 text-amber-600': log.type === 'complaint',
                                     'bg-slate-50 border-slate-200 text-slate-600': log.type === 'system'
                                 }">
                                <template x-if="log.type === 'order'">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </template>
                                <template x-if="log.type === 'complaint'">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </template>
                                <template x-if="log.type === 'system'">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </template>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <span class="font-black text-slate-900 text-xs sm:text-sm tracking-tight" x-text="log.title"></span>
                                    <span class="px-2 py-0.2 rounded text-[10px] font-bold border" :class="log.badge_color" x-text="log.category"></span>
                                    <span class="text-[10px] font-semibold text-slate-500 bg-slate-100 px-1.5 py-0.2 rounded" x-text="log.terminal"></span>
                                </div>
                                <p class="text-xs text-slate-600 font-medium leading-relaxed" x-text="log.description"></p>
                            </div>
                        </div>

                        <div class="flex flex-col items-end gap-1.5 shrink-0">
                            <span class="text-[11px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full" x-text="log.time_formatted"></span>
                            <template x-if="log.link">
                                <a :href="log.link" class="text-[11px] font-bold text-[#005ea2] hover:underline flex items-center gap-0.5">
                                    <span>Detail</span>
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </template>
                        </div>
                    </div>
                </template>

                <div x-show="visibleLogs.length === 0" class="text-center py-8 text-slate-400 font-medium text-xs">
                    Belum ada rekaman untuk kategori ini.
                </div>
            </div>
        </div>

        <!-- Bottom Indicator (Right Aligned) -->
        <div class="px-6 py-3 border-t border-slate-100 bg-slate-50/50 flex justify-end text-xs">
            <button type="button" 
                    @click="showAllModal = true; modalTab = (filterTab !== 'all' ? filterTab : 'all'); modalSearch = ''" 
                    class="font-black text-[#005ea2] hover:text-[#004a82] hover:underline flex items-center gap-1 cursor-pointer">
                <span>Buka Log Lengkap</span>
                <span>&rarr;</span>
            </button>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL POPUP: RIWAYAT AUDIT TRAIL LENGKAP & PENCARIAN (FLOATING & BLUR)    -->
    <!-- ========================================================================= -->
    <template x-teleport="body">
        <div x-show="showAllModal" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="showAllModal = false"
             class="fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-6 overflow-hidden" 
             style="display: none; background-color: rgba(15, 23, 42, 0.55); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);">
            
            <div @click.away="showAllModal = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="w-full max-w-3xl bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col my-auto"
                 style="height: 85vh; max-height: 85vh; display: flex; flex-direction: column;">
                
                <!-- 1. STICKY MODAL HEADER (Pinned at Top, shrink-0) -->
                <div class="px-6 py-4 border-b border-slate-100 bg-white flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-bold shrink-0">
                            <svg class="w-5 h-5 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Audit Trail & Riwayat Aktivitas Sistem</h3>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Catatan riil pesanan, keluhan komplain, dan log sistem bandara</p>
                        </div>
                    </div>
                    <button type="button" @click="showAllModal = false" 
                            class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- 2. STICKY FILTER TABS & SEARCH BAR (Pinned under header, shrink-0) -->
                <div class="p-4 sm:px-6 bg-slate-50/70 border-b border-slate-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 shrink-0">
                    <!-- Category Tabs (Always Visible) -->
                    <div class="inline-flex rounded-xl p-1 bg-slate-200/80 text-xs font-bold shrink-0 overflow-x-auto">
                        <button type="button" @click="modalTab = 'all'" 
                                class="whitespace-nowrap px-3 py-1.5 rounded-lg transition cursor-pointer"
                                :class="modalTab === 'all' ? 'bg-white text-[#005ea2] shadow-sm font-extrabold' : 'text-slate-600 hover:text-slate-900'">
                            Semua ({{ count($all_logs) }})
                        </button>
                        <button type="button" @click="modalTab = 'order'" 
                                class="whitespace-nowrap px-3 py-1.5 rounded-lg transition cursor-pointer"
                                :class="modalTab === 'order' ? 'bg-white text-[#005ea2] shadow-sm font-extrabold' : 'text-slate-600 hover:text-slate-900'">
                            Pesanan ({{ $all_logs->where('type', 'order')->count() }})
                        </button>
                        <button type="button" @click="modalTab = 'complaint'" 
                                class="whitespace-nowrap px-3 py-1.5 rounded-lg transition cursor-pointer"
                                :class="modalTab === 'complaint' ? 'bg-white text-amber-700 shadow-sm font-extrabold' : 'text-slate-600 hover:text-slate-900'">
                            Komplain ({{ $all_logs->where('type', 'complaint')->count() }})
                        </button>
                        <button type="button" @click="modalTab = 'system'" 
                                class="whitespace-nowrap px-3 py-1.5 rounded-lg transition cursor-pointer"
                                :class="modalTab === 'system' ? 'bg-white text-indigo-700 shadow-sm font-extrabold' : 'text-slate-600 hover:text-slate-900'">
                            Log Sistem ({{ $all_logs->where('type', 'system')->count() }})
                        </button>
                    </div>

                    <!-- Search Input -->
                    <div class="relative w-full sm:w-60 shrink-0">
                        <input type="text" x-model="modalSearch" 
                               placeholder="Cari transaksi, admin, tenant..." 
                               class="w-full pl-8 pr-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005ea2]">
                        <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- 3. SCROLLABLE CONTENT LIST (flex-1 min-h-0 overflow-y-auto, Only logs scroll!) -->
                <div class="p-4 sm:p-6 space-y-2.5 overscroll-contain" 
                     style="flex: 1 1 0%; min-height: 0; max-height: 100%; overflow-y: auto; -webkit-overflow-scrolling: touch;">
                    <template x-for="log in filteredModalLogs" :key="log.id">
                        <div class="flex items-start justify-between gap-3 p-3.5 rounded-xl border border-slate-100 hover:border-slate-200 hover:bg-slate-50/70 transition">
                            <div class="flex items-start gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 border mt-0.5"
                                     :class="{
                                         'bg-blue-50 border-blue-200 text-[#005ea2]': log.type === 'order',
                                         'bg-amber-50 border-amber-200 text-amber-600': log.type === 'complaint',
                                         'bg-slate-50 border-slate-200 text-slate-600': log.type === 'system'
                                     }">
                                    <template x-if="log.type === 'order'">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </template>
                                    <template x-if="log.type === 'complaint'">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </template>
                                    <template x-if="log.type === 'system'">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </template>
                                </div>

                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap mb-1">
                                        <span class="font-extrabold text-slate-900 text-xs sm:text-sm" x-text="log.title"></span>
                                        <span class="px-2 py-0.2 rounded text-[10px] font-bold border" :class="log.badge_color" x-text="log.category"></span>
                                        <span class="text-[10px] font-semibold text-slate-500 bg-slate-100 px-1.5 py-0.2 rounded" x-text="log.terminal"></span>
                                    </div>
                                    <p class="text-xs text-slate-600 leading-relaxed font-medium" x-text="log.description"></p>
                                    <span class="text-[11px] text-slate-400 block mt-1" x-text="log.date_formatted + ' WIB'"></span>
                                </div>
                            </div>

                            <template x-if="log.link">
                                <a :href="log.link" class="shrink-0 inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold text-[#005ea2] bg-blue-50 hover:bg-blue-100 transition border border-blue-200/60 self-center">
                                    <span>Detail</span>
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </template>
                        </div>
                    </template>
                    <div x-show="filteredModalLogs.length === 0" class="text-center py-8 text-slate-400 font-medium text-xs">
                        Tidak ada rekaman yang sesuai dengan filter atau pencarian.
                    </div>
                </div>

                <!-- 4. STICKY FOOTER (Fixed at Bottom, shrink-0) -->
                <div class="px-6 py-3.5 border-t border-slate-100 bg-slate-50 flex items-center justify-between text-xs text-slate-500 font-medium shrink-0">
                    <span>Total <strong>{{ count($all_logs) }}</strong> aktivitas tersimpan di database.</span>
                    <button type="button" @click="showAllModal = false" 
                            class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-[#005ea2] hover:bg-[#004a82] transition cursor-pointer shadow-sm">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    </template>

</div>
@endsection