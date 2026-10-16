@extends('layouts.admin')

@section('title', 'Dashboard Eksekutif')

@section('content')
<div class="space-y-6 pb-12">

    <!-- 1. Header Section: Title, Breadcrumb, Operational Status & Export Buttons -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <!-- Breadcrumb -->
                <nav class="flex items-center gap-2 text-xs font-bold text-slate-400 mb-2">
                    <span>Analitik Operasional</span>
                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-[#005ea2] font-extrabold">Ringkasan Eksekutif</span>
                </nav>

                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-xs font-extrabold uppercase tracking-wider bg-blue-50 border border-blue-200 text-[#005ea2] mb-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Monitoring Operasional • Bandara Internasional Juanda</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    Executive Summary & Performance Analytics
                </h1>
                <p class="text-sm font-semibold text-slate-600 mt-1">
                    Sistem Informasi Eksekutif: Evaluasi Kinerja Mitra F&B, SLA Waktu Saji, dan Pendukung Keputusan
                </p>
                <div class="mt-2.5 inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-xs font-bold text-slate-800">
                    <svg class="w-4 h-4 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Periode Terpilih: <strong class="text-[#005ea2]">{{ \Carbon\Carbon::parse($startDateFormatted)->format('d M Y') }}</strong> s/d <strong class="text-[#005ea2]">{{ \Carbon\Carbon::parse($endDateFormatted)->format('d M Y') }}</strong> ({{ (int)$days }} Hari)</span>
                </div>
            </div>

            <!-- Export Action Buttons -->
            <div class="shrink-0 w-full sm:w-auto">
                <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-wider mb-1.5">Unduh Laporan Resmi</label>
                <div class="grid grid-cols-2 gap-2 sm:flex sm:items-center sm:gap-2.5">
                    <a href="{{ route('admin.executive-dashboard.export', ['start_date' => $startDateFormatted, 'end_date' => $endDateFormatted, 'tenant_id' => $tenantId]) }}" 
                       class="inline-flex items-center justify-center px-4 py-2.5 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white rounded-xl text-sm font-bold shadow-sm hover:shadow transition duration-150 gap-2 cursor-pointer" 
                       title="Unduh Dokumen Laporan Eksekutif Format PDF">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Export PDF</span>
                    </a>
                    <a href="{{ route('admin.executive-dashboard.export-excel', ['start_date' => $startDateFormatted, 'end_date' => $endDateFormatted, 'tenant_id' => $tenantId]) }}" 
                       class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl text-sm font-bold shadow-sm shadow-emerald-600/20 hover:shadow transition duration-150 gap-2 cursor-pointer" 
                       title="Unduh Data Eksekutif Format Spreadsheet Excel (.xls)">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 4h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Export Excel</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar Component -->
    <x-analytics.filter-bar 
        :action="route('admin.executive-dashboard')" 
        :startDateFormatted="$startDateFormatted" 
        :endDateFormatted="$endDateFormatted" 
        :tenantId="$tenantId" 
        :tenants="$tenants" 
    />

    <!-- 2. Drill-Down Active State Banner -->
    @if($selectedTenant)
    <div class="bg-gradient-to-r from-blue-700 via-indigo-700 to-[#005ea2] text-white p-5 rounded-2xl shadow-md border-2 border-blue-400 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="h-12 w-12 rounded-xl bg-white/20 text-white flex items-center justify-center font-black shadow shrink-0 text-xl">
                🎯
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-0.5 rounded-full text-xs font-black uppercase tracking-wider bg-amber-400 text-slate-950">Mode Drill-Down Aktif</span>
                    <span class="text-xs text-blue-100 font-semibold">Isolasi Data Spesifik</span>
                </div>
                <h2 class="text-lg font-black text-white mt-1">
                    Analisis Kinerja Khusus Tenant: <span class="text-amber-300 underline underline-offset-4 decoration-amber-300">{{ $selectedTenant->name }}</span>
                </h2>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.executive-dashboard', ['start_date' => $startDateFormatted, 'end_date' => $endDateFormatted]) }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white text-slate-900 hover:bg-rose-50 hover:text-rose-700 text-sm font-black rounded-xl shadow transition duration-150 gap-2">
                <svg class="h-4 w-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span>Reset Drill-Down (Tampilkan Semua)</span>
            </a>
        </div>
    </div>
    @endif

    <!-- 3. Key Operational KPI Cards (6 Matriks Utama Termasuk Okupansi & Jam Puncak) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        
        <!-- Card 1: Total Volume Pesanan Harian -->
        @php
            $sumVolume = $volumePerDay->sum('total');
            $dailyAvg = $days > 0 ? round($sumVolume / $days, 1) : 0;
        @endphp
        <div class="bg-white p-5 rounded-2xl shadow-sm border-2 border-slate-200 hover:border-blue-400 transition-all duration-200">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-black text-slate-500 uppercase tracking-wider">Volume Pesanan</span>
                <span class="p-1.5 rounded-lg bg-blue-100 text-[#005ea2]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </span>
            </div>
            <div class="flex items-baseline gap-1.5">
                <span class="text-2xl font-black text-slate-900">{{ number_format($sumVolume) }}</span>
                <span class="text-xs font-bold text-slate-500">Order</span>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500 font-semibold">Rata-rata:</span>
                <span class="font-black text-[#005ea2] bg-blue-50 px-2 py-0.5 rounded">{{ $dailyAvg }}/hari</span>
            </div>
        </div>

        <!-- Card 2: Volume Transaksi Puncak (Peak Hours) -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border-2 border-slate-200 hover:border-amber-400 transition-all duration-200">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-black text-slate-500 uppercase tracking-wider">Transaksi Puncak</span>
                <span class="p-1.5 rounded-lg bg-amber-100 text-amber-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </span>
            </div>
            <div class="flex items-baseline gap-1.5">
                <span class="text-2xl font-black text-amber-600">{{ number_format($peakHourVolume) }}</span>
                <span class="text-xs font-bold text-slate-500">Order/Jam</span>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500 font-semibold">Jam Puncak:</span>
                <span class="font-black text-amber-800 bg-amber-50 px-2 py-0.5 rounded" title="Volume puncak jam sibuk penerbangan">{{ $peakHourRange }}</span>
            </div>
        </div>

        <!-- Card 3: Rasio Okupansi Tenant -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border-2 border-slate-200 hover:border-indigo-400 transition-all duration-200">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-black text-slate-500 uppercase tracking-wider">Okupansi Tenant</span>
                <span class="p-1.5 rounded-lg bg-indigo-100 text-indigo-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </span>
            </div>
            <div class="flex items-baseline gap-1.5">
                <span class="text-2xl font-black text-indigo-700">{{ $tenantOccupancyRate }}%</span>
                <span class="text-xs font-bold text-slate-500">Aktif</span>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500 font-semibold">Gerai Buka:</span>
                <span class="font-black text-indigo-800 bg-indigo-50 px-2 py-0.5 rounded">{{ $activeTenantCount }}/{{ $totalTenantCount }} Mitra</span>
            </div>
        </div>

        <!-- Card 4: Waktu Saji SLA -->
        @php
            $avgMinutes = $slaPerformance->avg('avg_minutes') ?? 0;
            $isOverSla = $avgMinutes > 15;
        @endphp
        <div class="bg-white p-5 rounded-2xl shadow-sm border-2 border-slate-200 hover:border-emerald-400 transition-all duration-200">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-black text-slate-500 uppercase tracking-wider">Rata-rata SLA</span>
                <span class="p-1.5 rounded-lg {{ $isOverSla ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <div class="flex items-baseline gap-1.5">
                <span class="text-2xl font-black {{ $isOverSla ? 'text-rose-600' : 'text-emerald-700' }}">{{ round($avgMinutes, 1) }}</span>
                <span class="text-xs font-bold text-slate-500">Menit</span>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500 font-semibold">SOP Maks:</span>
                <span class="font-black px-2 py-0.5 rounded {{ $isOverSla ? 'text-rose-700 bg-rose-100' : 'text-emerald-700 bg-emerald-100' }}">
                    {{ $isOverSla ? '⚠️ > 15m' : '✅ ≤ 15m' }}
                </span>
            </div>
        </div>

        <!-- Card 5: Tingkat Pembatalan (Cancel Rate) -->
        @php
            $totalOrders = $statusDistribution->sum('total');
            $canceled = $statusDistribution->where('status', 'dibatalkan')->first()->total ?? 0;
            $cancelRate = $totalOrders > 0 ? round(($canceled / $totalOrders) * 100, 1) : 0;
            $isHighCancel = $cancelRate > 10;
        @endphp
        <div class="bg-white p-5 rounded-2xl shadow-sm border-2 border-slate-200 hover:border-rose-400 transition-all duration-200">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-black text-slate-500 uppercase tracking-wider">Cancel Rate</span>
                <span class="p-1.5 rounded-lg {{ $isHighCancel ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <div class="flex items-baseline gap-1.5">
                <span class="text-2xl font-black {{ $isHighCancel ? 'text-rose-600' : 'text-slate-900' }}">{{ $cancelRate }}%</span>
                <span class="text-xs font-bold text-slate-500">Batal</span>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500 font-semibold">{{ $canceled }} order</span>
                <span class="font-black px-2 py-0.5 rounded {{ $isHighCancel ? 'text-rose-700 bg-rose-100' : 'text-emerald-700 bg-emerald-100' }}">
                    {{ $isHighCancel ? '⚠️ Waspada' : '✅ Terkendali' }}
                </span>
            </div>
        </div>

        <!-- Card 6: Komplain Terbuka -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border-2 border-slate-200 hover:border-amber-400 transition-all duration-200">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-black text-slate-500 uppercase tracking-wider">Komplain Kasus</span>
                <span class="p-1.5 rounded-lg {{ $openComplaints > 0 ? 'bg-amber-100 text-amber-600' : 'bg-slate-100 text-slate-500' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </span>
            </div>
            <div class="flex items-baseline gap-1.5">
                <span class="text-2xl font-black {{ $openComplaints > 0 ? 'text-amber-600' : 'text-slate-900' }}">{{ $openComplaints }}</span>
                <span class="text-xs font-bold text-slate-500">Kasus Aktif</span>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-500 font-semibold">Terselesaikan:</span>
                <span class="font-black text-slate-800 bg-slate-100 px-2 py-0.5 rounded">{{ $resolvedComplaints }} closed</span>
            </div>
        </div>

    </div>

    <!-- 4. Charts Row 1: Volume Trend & SLA Performance -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Volume Line Chart -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border-2 border-slate-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base font-black text-slate-900 uppercase tracking-wide">Tren Volume Pesanan Harian</h2>
                    <p class="text-xs text-slate-500 font-semibold mt-0.5">Pola fluktuasi jumlah pesanan penumpang per tanggal</p>
                </div>
                <span class="px-3 py-1 rounded-lg bg-blue-100 text-[#005ea2] text-xs font-black">
                    📈 Volume Harian
                </span>
            </div>
            <div class="relative h-72">
                <canvas id="volumeChart"></canvas>
            </div>
        </div>

        <!-- SLA Bar Chart -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border-2 border-slate-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base font-black text-slate-900 uppercase tracking-wide">Kinerja Waktu Saji Tenant (SLA)</h2>
                    <p class="text-xs text-slate-500 font-semibold mt-0.5">Batas standar layanan bandara maksimal 15 menit</p>
                </div>
                <span class="inline-flex items-center text-xs font-black uppercase tracking-wider text-[#005ea2] bg-blue-50 border-2 border-blue-200 px-3 py-1 rounded-xl">
                    🎯 Drill-down: Klik Bar
                </span>
            </div>
            <div class="relative h-72">
                <canvas id="slaChart"></canvas>
            </div>
        </div>

    </div>

    <!-- 5. Charts Row 2: Status Distribution, Tenant Volume, & Top Products -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Status Doughnut -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border-2 border-slate-200">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-black text-slate-900 uppercase tracking-wide">Distribusi Status Pesanan</h2>
                <span class="text-xs text-slate-500 font-bold">100% Total</span>
            </div>
            <div class="relative h-64">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Tenant Performance Bar (2 Cols) -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border-2 border-slate-200 lg:col-span-2">
            <div class="flex items-center justify-between mb-1">
                <div>
                    <h2 class="text-base font-black text-slate-900 uppercase tracking-wide">Top 20 Tenant Terlaris (Volume Order)</h2>
                    <p class="text-xs text-slate-500 font-semibold mt-0.5">Klik salah satu batang tenant untuk mengisolasi analitik secara mendalam (Drill-Down)</p>
                </div>
                <span class="inline-flex items-center text-xs font-black uppercase tracking-wider text-[#005ea2] bg-blue-50 border-2 border-blue-200 px-3 py-1 rounded-xl">
                    🎯 Drill-down: Klik Bar
                </span>
            </div>
            <div class="relative h-64 mt-3">
                <canvas id="tenantChart"></canvas>
            </div>
        </div>

    </div>

    <!-- 6. Row 3: Top Products & Hourly Peak Transaction Distribution (Peak Hours) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Col 1: Top 10 Best Selling Products -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border-2 border-slate-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base font-black text-slate-900 uppercase tracking-wide">10 Menu Makanan & Minuman Terlaris</h2>
                    <p class="text-xs text-slate-500 font-semibold mt-0.5">Peringkat menu favorit penumpang bandara</p>
                </div>
                <span class="px-3 py-1 rounded-lg bg-purple-100 text-purple-800 text-xs font-black">
                    ⭐ Menu Terlaris
                </span>
            </div>
            <div class="relative h-72">
                <canvas id="productsChart"></canvas>
            </div>
        </div>

        <!-- Col 2: Hourly Peak Transaction Distribution -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border-2 border-slate-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base font-black text-slate-900 uppercase tracking-wide">Distribusi Transaksi per Jam (Peak Hours)</h2>
                    <p class="text-xs text-slate-500 font-semibold mt-0.5">Pola volume jam sibuk penerbangan (00:00 - 23:00 WIB)</p>
                </div>
                <span class="px-3 py-1 rounded-lg bg-amber-100 text-amber-900 text-xs font-black flex items-center gap-1.5" title="Jam dengan volume pesanan tertinggi">
                    <span>🔥 Puncak:</span>
                    <strong class="text-amber-800">{{ $peakHourRange }}</strong>
                </span>
            </div>
            <div class="relative h-72">
                <canvas id="hourlyChart"></canvas>
            </div>
        </div>

    </div>

    <!-- 7. Kartu Tautan ke Fitur Analitik Lanjutan (Drill-Down, What-If, Segmentasi Pelanggan) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
        <!-- Card 1: Rincian Pesanan (Drill-Down) -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border-2 border-slate-200 hover:border-[#005ea2] transition-all duration-200 flex flex-col justify-between group">
            <div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xl mb-4 group-hover:scale-105 transition-transform shadow-xs">
                    🔍
                </div>
                <h3 class="text-lg font-black text-slate-900 tracking-tight group-hover:text-[#005ea2] transition-colors">
                    Rincian Pesanan
                </h3>
                <p class="text-xs font-semibold text-slate-500 mt-2 leading-relaxed">
                    Telusuri data individual per transaksi secara mendalam dan inspeksi kepatuhan SLA setiap gerai.
                </p>
            </div>
            <div class="mt-5 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.analytics.drilldown', request()->only(['start_date', 'end_date', 'tenant_id'])) }}" 
                   class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#005ea2] hover:bg-[#004b82] text-white rounded-xl text-xs font-black shadow-xs transition cursor-pointer">
                    <span>Buka Rincian Pesanan</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Card 2: Simulasi What-If -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border-2 border-slate-200 hover:border-indigo-500 transition-all duration-200 flex flex-col justify-between group">
            <div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-black text-xl mb-4 group-hover:scale-105 transition-transform shadow-xs">
                    🎯
                </div>
                <h3 class="text-lg font-black text-slate-900 tracking-tight group-hover:text-indigo-700 transition-colors">
                    Simulasi What-If
                </h3>
                <p class="text-xs font-semibold text-slate-500 mt-2 leading-relaxed">
                    Hitung proyeksi target omset, kebutuhan trafik pengunjung, dan mitigasi risiko operasional gerai.
                </p>
            </div>
            <div class="mt-5 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.analytics.whatif', request()->only(['start_date', 'end_date', 'tenant_id'])) }}" 
                   class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-black shadow-xs transition cursor-pointer">
                    <span>Mulai Simulasi What-If</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Card 3: Segmentasi Pelanggan -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border-2 border-slate-200 hover:border-emerald-500 transition-all duration-200 flex flex-col justify-between group">
            <div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-black text-xl mb-4 group-hover:scale-105 transition-transform shadow-xs">
                    👥
                </div>
                <h3 class="text-lg font-black text-slate-900 tracking-tight group-hover:text-emerald-700 transition-colors">
                    Segmentasi Pelanggan
                </h3>
                <p class="text-xs font-semibold text-slate-500 mt-2 leading-relaxed">
                    Analisis loyalitas penumpang dan distribusi frekuensi belanja untuk strategi retensi bandara.
                </p>
            </div>
            <div class="mt-5 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.analytics.customers', request()->only(['start_date', 'end_date', 'tenant_id'])) }}" 
                   class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black shadow-xs transition cursor-pointer">
                    <span>Lihat Segmentasi Pelanggan</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- 9. Modern Chart.js Configs with Max Bar Thickness, Gradients, and Custom Tooltips -->
<script nonce="{{ $cspNonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function() {
    // Shared Styling Options
    Chart.defaults.font.family = "'Plus Jakarta Sans', 'Inter', sans-serif";
    Chart.defaults.color = '#475569';
    
    // 1. Volume Chart (Line with Area Gradient)
    const volumeCanvas = document.getElementById('volumeChart');
    const volumeCtx = volumeCanvas.getContext('2d');
    const volumeData = @json($volumePerDay);

    const volGradient = volumeCtx.createLinearGradient(0, 0, 0, 300);
    volGradient.addColorStop(0, 'rgba(0, 94, 162, 0.35)');
    volGradient.addColorStop(1, 'rgba(0, 94, 162, 0.02)');

    new Chart(volumeCtx, {
        type: 'line',
        data: {
            labels: volumeData.map(item => item.date),
            datasets: [{
                label: 'Pesanan Masuk',
                data: volumeData.map(item => item.total),
                borderColor: '#005ea2',
                backgroundColor: volGradient,
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#005ea2',
                pointBorderWidth: 2.5,
                pointRadius: 5,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: '#005ea2',
                pointHoverBorderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 12,
                    cornerRadius: 12,
                    titleFont: { weight: 'bold', size: 13 },
                    bodyFont: { size: 12 }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: { color: '#e2e8f0', borderDash: [4, 4] },
                    ticks: { precision: 0, font: { weight: 'bold' } }
                },
                x: { 
                    grid: { display: false },
                    ticks: { font: { weight: 'bold', size: 11 } }
                }
            }
        }
    });

    // 2. SLA Chart (Bar with Max Thickness & Color Threshold)
    const slaCanvas = document.getElementById('slaChart');
    const slaCtx = slaCanvas.getContext('2d');
    const slaData = @json($slaPerformance);

    const slaBgColors = slaData.map(item => {
        return item.avg_minutes > 15 ? '#e11d48' : '#10b981';
    });

    const slaChart = new Chart(slaCtx, {
        type: 'bar',
        data: {
            labels: slaData.map(item => item.name),
            datasets: [{
                label: 'Rata-rata Menit',
                data: slaData.map(item => item.avg_minutes),
                backgroundColor: slaBgColors,
                borderRadius: 8,
                maxBarThickness: 45 // Ramping & rapi
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            onHover: (event, chartElement) => {
                const target = event.native ? event.native.target : (event.chart ? event.chart.canvas : null);
                if (target) {
                    target.style.cursor = chartElement.length ? 'pointer' : 'default';
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 12,
                    cornerRadius: 12,
                    titleFont: { weight: 'bold', size: 13 },
                    bodyFont: { size: 12 },
                    callbacks: {
                        afterLabel: function(context) {
                            return context.raw > 15 ? '⚠️ Melewati Batas SLA (15m)' : '✅ Sesuai Standar Layanan (≤ 15m)';
                        },
                        footer: function() {
                            return '👉 Klik bar untuk Drill-down ke tenant ini';
                        }
                    }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: { color: '#e2e8f0', borderDash: [4, 4] },
                    title: { display: true, text: 'Durasi Rata-rata (Menit)', font: { weight: 'bold' } },
                    ticks: { font: { weight: 'bold' } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { weight: 'bold' } }
                }
            },
            onClick: (e) => {
                const elements = slaChart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, false);
                let targetId = null;
                if (elements.length > 0) {
                    const index = elements[0].index;
                    if (slaData[index]) targetId = slaData[index].id;
                } else {
                    const canvasPosition = Chart.helpers.getRelativePosition(e, slaChart);
                    const dataX = slaChart.scales.x.getValueForPixel(canvasPosition.x);
                    if (dataX !== undefined && slaData[dataX]) targetId = slaData[dataX].id;
                }
                if (targetId) {
                    const selectEl = document.querySelector('select[name="tenant_id"]');
                    if(selectEl) {
                        selectEl.value = targetId;
                        document.getElementById('filterForm').submit();
                    }
                }
            }
        }
    });

    // 3. Status Chart (Doughnut)
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusDataRaw = @json($statusDistribution);
    
    const statusColors = {
        'menunggu': '#94a3b8',
        'diproses': '#3b82f6',
        'siap': '#f59e0b',
        'selesai': '#10b981',
        'dibatalkan': '#ef4444'
    };
    
    const statusLabels = statusDataRaw.map(item => item.status.toUpperCase());
    const statusValues = statusDataRaw.map(item => item.total);
    const statusBgColors = statusDataRaw.map(item => statusColors[item.status] || '#94a3b8');

    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusValues,
                backgroundColor: statusBgColors,
                borderWidth: 3,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { 
                    position: 'bottom', 
                    labels: { 
                        usePointStyle: true, 
                        padding: 16, 
                        font: { size: 12, weight: 'bold' } 
                    } 
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 12,
                    cornerRadius: 12,
                    titleFont: { weight: 'bold', size: 13 },
                    bodyFont: { size: 12 }
                }
            }
        }
    });

    // 4. Tenant Performance Chart (Bar with Drill-down)
    const tenantCtx = document.getElementById('tenantChart').getContext('2d');
    const tenantDataRaw = @json($tenantPerformance);
    const tenantChart = new Chart(tenantCtx, {
        type: 'bar',
        data: {
            labels: tenantDataRaw.map(item => item.name),
            datasets: [{
                label: 'Total Pesanan',
                data: tenantDataRaw.map(item => item.total),
                backgroundColor: '#005ea2',
                borderRadius: 8,
                maxBarThickness: 45
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            onHover: (event, chartElement) => {
                const target = event.native ? event.native.target : (event.chart ? event.chart.canvas : null);
                if (target) {
                    target.style.cursor = chartElement.length ? 'pointer' : 'default';
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 12,
                    cornerRadius: 12,
                    titleFont: { weight: 'bold', size: 13 },
                    bodyFont: { size: 12 },
                    callbacks: {
                        footer: function() {
                            return '👉 Klik bar untuk Drill-down ke tenant ini';
                        }
                    }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: { color: '#e2e8f0', borderDash: [4, 4] },
                    ticks: { precision: 0, font: { weight: 'bold' } }
                },
                x: { 
                    grid: { display: false },
                    ticks: { font: { weight: 'bold' } }
                }
            },
            onClick: (e) => {
                const elements = tenantChart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, false);
                let targetId = null;
                if (elements.length > 0) {
                    const index = elements[0].index;
                    if (tenantDataRaw[index]) targetId = tenantDataRaw[index].id;
                } else {
                    const canvasPosition = Chart.helpers.getRelativePosition(e, tenantChart);
                    const dataX = tenantChart.scales.x.getValueForPixel(canvasPosition.x);
                    if (dataX !== undefined && tenantDataRaw[dataX]) targetId = tenantDataRaw[dataX].id;
                }
                if (targetId) {
                    const selectEl = document.querySelector('select[name="tenant_id"]');
                    if(selectEl) {
                        selectEl.value = targetId;
                        document.getElementById('filterForm').submit();
                    }
                }
            }
        }
    });

    // 5. Top Products (Horizontal Bar)
    const productsCtx = document.getElementById('productsChart').getContext('2d');
    const productsData = @json($topProducts);
    new Chart(productsCtx, {
        type: 'bar',
        data: {
            labels: productsData.map(item => item.product_name_snapshot),
            datasets: [{
                label: 'Total Terjual',
                data: productsData.map(item => item.total_qty),
                backgroundColor: '#7c3aed',
                borderRadius: 6,
                maxBarThickness: 24
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 12,
                    cornerRadius: 12,
                    titleFont: { weight: 'bold', size: 13 },
                    bodyFont: { size: 12 }
                }
            },
            scales: {
                x: { 
                    beginAtZero: true, 
                    grid: { color: '#e2e8f0', borderDash: [4, 4] },
                    ticks: { precision: 0, font: { weight: 'bold' } }
                },
                y: {
                    grid: { display: false },
                    ticks: { font: { weight: 'bold', size: 11 } }
                }
            }
        }
    });

    // 6. Hourly Peak Transaction Distribution (Bar Chart - Peak Hours)
    const hourlyCanvas = document.getElementById('hourlyChart');
    if (hourlyCanvas) {
        const hourlyCtx = hourlyCanvas.getContext('2d');
        const hourlyData = @json($hourlyDistribution);
        
        // Buat label jam 00:00 s/d 23:00 WIB
        const fullHours = Array.from({length: 24}, (_, i) => i);
        const hourlyMap = {};
        hourlyData.forEach(item => {
            hourlyMap[item.hour] = item.total;
        });

        const hourLabels = fullHours.map(h => String(h).padStart(2, '0') + ':00');
        const hourValues = fullHours.map(h => hourlyMap[h] || 0);
        const maxVal = Math.max(...hourValues, 1);
        const hourColors = hourValues.map(v => (v === maxVal && v > 0) ? '#ea580c' : (v > 0 ? '#3b82f6' : '#e2e8f0'));

        new Chart(hourlyCtx, {
            type: 'bar',
            data: {
                labels: hourLabels,
                datasets: [{
                    label: 'Volume Transaksi (Order)',
                    data: hourValues,
                    backgroundColor: hourColors,
                    borderRadius: 4,
                    maxBarThickness: 18
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 10,
                        cornerRadius: 10,
                        titleFont: { weight: 'bold' },
                        callbacks: {
                            afterLabel: function(context) {
                                return context.raw === maxVal && maxVal > 0 ? '🔥 Jam Puncak Tersibuk (Peak Hour Bandara)' : '';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#e2e8f0', borderDash: [4, 4] },
                        ticks: { precision: 0, font: { weight: 'bold' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 10, weight: 'bold' },
                            maxRotation: 0,
                            autoSkip: true,
                            maxTicksLimit: 12
                        }
                    }
                }
            }
        });
    }

    // Calendar Range Picker UX Helpers
    const startInput = document.getElementById('startDateInput');
    const endInput = document.getElementById('endDateInput');
    if (startInput) {
        startInput.addEventListener('click', function() {
            if (typeof this.showPicker === 'function') {
                try { this.showPicker(); } catch(e) {}
            }
        });
        startInput.addEventListener('change', function() {
            if (endInput && this.value) {
                endInput.min = this.value;
            }
        });
    }
    if (endInput) {
        endInput.addEventListener('click', function() {
            if (typeof this.showPicker === 'function') {
                try { this.showPicker(); } catch(e) {}
            }
        });
        endInput.addEventListener('change', function() {
            if (startInput && this.value) {
                startInput.max = this.value;
            }
        });
    }
});

// Quick Shortcut Preset Helper for Dates
window.setQuickDateRange = function(type) {
    const today = new Date();
    let start = new Date();
    
    if (type === 'month') {
        start = new Date(today.getFullYear(), today.getMonth(), 1);
    } else {
        const days = parseInt(type, 10) || 7;
        start.setDate(today.getDate() - (days - 1));
    }
    
    const pad = (n) => String(n).padStart(2, '0');
    const formatYMD = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
    
    const startEl = document.getElementById('startDateInput');
    const endEl = document.getElementById('endDateInput');
    if (startEl && endEl) {
        startEl.value = formatYMD(start);
        endEl.value = formatYMD(today);
        document.getElementById('filterForm').submit();
    }
};
</script>
@endsection
