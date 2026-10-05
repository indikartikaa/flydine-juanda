@extends('layouts.admin')

@section('title', 'Dashboard Eksekutif')

@section('content')
<div class="space-y-6 pb-12">

    <!-- 1. Executive Control Panel & Filter Bar -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <!-- Top Section: Title, Operational Status & Export Buttons -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-5 border-b border-slate-200">
            <div>
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
            <div class="shrink-0">
                <label class="block text-xs font-extrabold text-slate-600 uppercase tracking-wider mb-1.5">Unduh Laporan Resmi</label>
                <div class="flex items-center gap-2.5">
                    <a href="{{ route('admin.executive-dashboard.export', ['start_date' => $startDateFormatted, 'end_date' => $endDateFormatted, 'tenant_id' => $tenantId]) }}" 
                       class="inline-flex items-center px-4 py-2.5 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white rounded-xl text-sm font-bold shadow-sm hover:shadow transition duration-150 gap-2 cursor-pointer" 
                       title="Unduh Dokumen Laporan Eksekutif Format PDF">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Export PDF</span>
                    </a>
                    <a href="{{ route('admin.executive-dashboard.export-excel', ['start_date' => $startDateFormatted, 'end_date' => $endDateFormatted, 'tenant_id' => $tenantId]) }}" 
                       class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl text-sm font-bold shadow-sm shadow-emerald-600/20 hover:shadow transition duration-150 gap-2 cursor-pointer" 
                       title="Unduh Data Eksekutif Format Spreadsheet Excel (.xls)">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 4h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Export Excel</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Section: Calendar Date Range Picker & Filter Form -->
        <div class="mt-5">
            <form method="GET" action="{{ route('admin.executive-dashboard') }}" id="filterForm" class="flex flex-col xl:flex-row xl:items-end justify-between gap-4">
                <!-- Dual Calendar Pickers + Apply Button + Tenant Filter -->
                <div class="flex flex-wrap items-end gap-3 sm:gap-4">
                    <!-- Dari Tanggal (Start Date) -->
                    <div>
                        <label for="startDateInput" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Dari Tanggal</span>
                        </label>
                        <div class="relative">
                            <input type="date" 
                                   id="startDateInput" 
                                   name="start_date" 
                                   value="{{ $startDateFormatted }}" 
                                   class="w-44 px-3.5 py-2.5 bg-slate-50 hover:bg-white border-2 border-slate-300 focus:border-[#005ea2] focus:bg-white rounded-xl text-sm font-extrabold text-slate-900 focus:ring-2 focus:ring-blue-100 transition shadow-xs cursor-pointer">
                        </div>
                    </div>

                    <!-- Sampai Tanggal (End Date) -->
                    <div>
                        <label for="endDateInput" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Sampai Tanggal</span>
                        </label>
                        <div class="relative">
                            <input type="date" 
                                   id="endDateInput" 
                                   name="end_date" 
                                   value="{{ $endDateFormatted }}" 
                                   class="w-44 px-3.5 py-2.5 bg-slate-50 hover:bg-white border-2 border-slate-300 focus:border-[#005ea2] focus:bg-white rounded-xl text-sm font-extrabold text-slate-900 focus:ring-2 focus:ring-blue-100 transition shadow-xs cursor-pointer">
                        </div>
                    </div>

                    <!-- Tombol Terapkan Rentang -->
                    <div>
                        <button type="submit" 
                                class="px-5 py-2.5 bg-[#005ea2] hover:bg-[#004b82] active:bg-[#003860] text-white rounded-xl text-sm font-extrabold shadow-sm hover:shadow transition duration-150 flex items-center gap-2 cursor-pointer h-[44px]">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span>Terapkan Rentang</span>
                        </button>
                    </div>

                    <!-- Filter Mitra Tenant Dropdown -->
                    <div>
                        <label for="tenantSelect" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                            <span>🏢 Filter Mitra Tenant</span>
                        </label>
                        <div class="relative">
                            <select id="tenantSelect" 
                                    name="tenant_id" 
                                    class="pl-3.5 pr-9 py-2.5 bg-slate-50 hover:bg-white border-2 border-slate-300 focus:border-[#005ea2] focus:bg-white rounded-xl text-sm font-extrabold text-slate-900 focus:ring-2 focus:ring-[#005ea2] transition cursor-pointer shadow-xs min-w-[210px] max-w-[280px] truncate h-[44px]" 
                                    onchange="document.getElementById('filterForm').submit()">
                                <option value="">🏢 Semua Tenant (Konsolidasi)</option>
                                @foreach($tenants as $t)
                                    <option value="{{ $t->id }}" {{ $tenantId == $t->id ? 'selected' : '' }}>🏢 {{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Quick Shortcut Chips -->
                <div class="flex items-center flex-wrap gap-2 text-xs pt-1">
                    <span class="text-xs font-black text-slate-500 uppercase tracking-wider mr-1">Preset Cepat:</span>
                    <button type="button" onclick="setQuickDateRange(7)" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-blue-100 hover:text-[#005ea2] text-slate-800 font-extrabold border border-slate-200 transition cursor-pointer">
                        7 Hari
                    </button>
                    <button type="button" onclick="setQuickDateRange(30)" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-blue-100 hover:text-[#005ea2] text-slate-800 font-extrabold border border-slate-200 transition cursor-pointer">
                        30 Hari
                    </button>
                    <button type="button" onclick="setQuickDateRange('month')" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-blue-100 hover:text-[#005ea2] text-slate-800 font-extrabold border border-slate-200 transition cursor-pointer">
                        Bulan Ini
                    </button>
                    <button type="button" onclick="setQuickDateRange(90)" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-blue-100 hover:text-[#005ea2] text-slate-800 font-extrabold border border-slate-200 transition cursor-pointer">
                        90 Hari
                    </button>
                </div>
            </form>
        </div>
    </div>

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

    <!-- 3. Key Operational KPI Cards (High Contrast, Bold, Clear) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Card 1: Total Volume -->
        @php
            $sumVolume = $volumePerDay->sum('total');
            $dailyAvg = $days > 0 ? round($sumVolume / $days, 1) : 0;
        @endphp
        <div class="bg-white p-6 rounded-2xl shadow-sm border-2 border-slate-200 hover:border-blue-400 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Volume Pesanan</span>
                <span class="p-2 rounded-xl bg-blue-100 text-[#005ea2]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ number_format($sumVolume) }}</span>
                <span class="text-sm font-bold text-slate-600">Pesanan Masuk</span>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500 font-semibold">Rata-rata:</span>
                <span class="font-extrabold text-[#005ea2] bg-blue-50 px-2.5 py-1 rounded-md">{{ $dailyAvg }} order/hari</span>
            </div>
        </div>

        <!-- Card 2: Cancel Rate -->
        @php
            $totalOrders = $statusDistribution->sum('total');
            $canceled = $statusDistribution->where('status', 'dibatalkan')->first()->total ?? 0;
            $cancelRate = $totalOrders > 0 ? round(($canceled / $totalOrders) * 100, 1) : 0;
            $isHighCancel = $cancelRate > 10;
        @endphp
        <div class="bg-white p-6 rounded-2xl shadow-sm border-2 border-slate-200 hover:border-rose-400 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tingkat Pembatalan</span>
                <span class="p-2 rounded-xl {{ $isHighCancel ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black {{ $isHighCancel ? 'text-rose-600' : 'text-slate-900' }}">{{ $cancelRate }}%</span>
                <span class="text-sm font-bold text-slate-600">Cancel Rate</span>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500 font-semibold">{{ $canceled }} dari {{ $totalOrders }} order</span>
                <span class="font-extrabold px-2.5 py-1 rounded-md {{ $isHighCancel ? 'text-rose-700 bg-rose-100' : 'text-emerald-700 bg-emerald-100' }}">
                    {{ $isHighCancel ? '⚠️ Perlu Perhatian' : '✅ Terkendali' }}
                </span>
            </div>
        </div>

        <!-- Card 3: Waktu Saji SLA -->
        @php
            $avgMinutes = $slaPerformance->avg('avg_minutes') ?? 0;
            $isOverSla = $avgMinutes > 15;
        @endphp
        <div class="bg-white p-6 rounded-2xl shadow-sm border-2 border-slate-200 hover:border-emerald-400 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Waktu Saji Rata-rata</span>
                <span class="p-2 rounded-xl {{ $isOverSla ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black {{ $isOverSla ? 'text-rose-600' : 'text-emerald-700' }}">{{ round($avgMinutes, 1) }}</span>
                <span class="text-sm font-bold text-slate-600">Menit (SLA)</span>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500 font-semibold">Target Standar: ≤ 15m</span>
                <span class="font-extrabold px-2.5 py-1 rounded-md {{ $isOverSla ? 'text-rose-700 bg-rose-100' : 'text-emerald-700 bg-emerald-100' }}">
                    {{ $isOverSla ? '⚠️ Melewati SLA' : '✅ Sangat Cepat' }}
                </span>
            </div>
        </div>

        <!-- Card 4: Open Complaints -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border-2 border-slate-200 hover:border-amber-400 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Komplain Terbuka</span>
                <span class="p-2 rounded-xl {{ $openComplaints > 0 ? 'bg-amber-100 text-amber-600' : 'bg-slate-100 text-slate-500' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black {{ $openComplaints > 0 ? 'text-amber-600' : 'text-slate-900' }}">{{ $openComplaints }}</span>
                <span class="text-sm font-bold text-slate-600">Kasus Aktif</span>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500 font-semibold">Terselesaikan:</span>
                <span class="font-extrabold text-slate-800 bg-slate-100 px-2.5 py-1 rounded-md">{{ $resolvedComplaints }} selesai</span>
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

    <!-- 6. Top 10 Best Selling Products -->
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

    <!-- 7. Executive Decision Support System (DSS): What-If Scenario Simulator (Executive Compact Mode) -->
    <div class="bg-white rounded-2xl p-6 sm:p-7 shadow-sm border-2 border-indigo-200" 
         x-data="{
            // Data Riil Database
            actualOrders: {{ (int)$totalOrdersCount }},
            actualRevenue: {{ (float)$totalRevenue }},
            actualAov: {{ (float)($avgOrderValue > 0 ? $avgOrderValue : 85000) }},

            // Variabel Acuan Dasar Simulasi
            baseOrders: {{ (int)$totalOrdersCount > 0 ? (int)$totalOrdersCount : 100 }},
            baseAov: {{ (float)($avgOrderValue > 0 ? $avgOrderValue : 85000) }},
            get baseRevenue() {
                return Math.round(Number(this.baseOrders) * Number(this.baseAov));
            },
            baseSlaMinutes: {{ (float)($avgSlaMinutes > 0 ? $avgSlaMinutes : 13) }},
            baseSlaCompliance: {{ (float)($slaComplianceRate > 0 ? $slaComplianceRate : 100) }},
            baseCancelRate: {{ (float)$cancelRate }},
            baseComplaints: {{ (int)$openComplaints }},
            
            // Slider & Skenario Kontrol
            activeScenario: 'normal',
            showCustomSliders: false,
            volumePct: 0,
            slaReduction: 0,
            targetCancelRate: {{ (float)$cancelRate }},
            copied: false,

            setBaseline(orders) {
                this.baseOrders = Number(orders) || 1;
            },

            applyPreset(type) {
                this.activeScenario = type;
                if (type === 'normal') {
                    this.volumePct = 0;
                    this.slaReduction = 0;
                    this.targetCancelRate = this.baseCancelRate;
                } else if (type === 'peak_season') {
                    this.volumePct = 35;
                    this.slaReduction = 2;
                    this.targetCancelRate = Math.max(1, +(this.baseCancelRate * 0.6).toFixed(1));
                } else if (type === 'fast_track') {
                    this.volumePct = 10;
                    this.slaReduction = 4;
                    this.targetCancelRate = Math.max(1, +(this.baseCancelRate * 0.5).toFixed(1));
                } else if (type === 'zero_cancel') {
                    this.volumePct = 5;
                    this.slaReduction = 2.5;
                    this.targetCancelRate = 1.0;
                }
            },

            get projectedOrders() {
                return Math.max(0, Math.round(Number(this.baseOrders) * (1 + (Number(this.volumePct) / 100))));
            },
            get ordersDelta() {
                return this.projectedOrders - Number(this.baseOrders);
            },
            get projectedRevenue() {
                return Math.round(this.projectedOrders * Number(this.baseAov));
            },
            get revenueDelta() {
                return this.projectedRevenue - this.baseRevenue;
            },
            get projectedSla() {
                return Math.max(3, +(Number(this.baseSlaMinutes) - Number(this.slaReduction)).toFixed(1));
            },
            get projectedCompliance() {
                const bonus = Number(this.slaReduction) * 7.0;
                return Math.min(100, Math.max(0, +(Number(this.baseSlaCompliance) + bonus).toFixed(1)));
            },
            get projectedSavedOrders() {
                const currentCancelPct = Number(this.baseCancelRate) / 100;
                const targetCancelPct = Number(this.targetCancelRate) / 100;
                if (targetCancelPct >= currentCancelPct) return 0;
                const diffPct = currentCancelPct - targetCancelPct;
                return Math.max(0, Math.round(this.projectedOrders * diffPct));
            },
            get recoveredRevenue() {
                return Math.round(this.projectedSavedOrders * Number(this.baseAov));
            },
            get projectedComplaints() {
                const factor = Math.max(0.1, 1 - (Number(this.slaReduction) * 0.12) - (Math.max(0, Number(this.baseCancelRate) - Number(this.targetCancelRate)) * 0.05));
                return Math.max(0, Math.round(this.baseComplaints * factor));
            },
            formatRupiah(val) {
                return 'Rp ' + Number(val).toLocaleString('id-ID');
            },
            get operationalStatus() {
                if (this.projectedSla > 15) {
                    return {
                        level: 'danger',
                        badge: '⚠️ RISIKO KELAMBATAN (SLA > 15m)',
                        badgeClass: 'bg-rose-100 text-rose-900 border-rose-300',
                        bannerClass: 'bg-rose-50/90 border-rose-200 text-rose-950',
                        icon: '🚨',
                        summary: 'Waktu saji melebihi batas 15 menit. Penumpang berisiko terlambat boarding gate!',
                        action: 'Tenant wajib menambah koki cadangan & runner pengantar cepat.'
                    };
                } else if (this.volumePct >= 30 && this.projectedSla > 12) {
                    return {
                        level: 'warning',
                        badge: '⚡ BEBAN TINGGI (PERLU WASPADA)',
                        badgeClass: 'bg-amber-100 text-amber-900 border-amber-300',
                        bannerClass: 'bg-amber-50/90 border-amber-200 text-amber-950',
                        icon: '⚡',
                        summary: 'Lonjakan pesanan saat peak season. Waktu saji mendekati batas toleransi 15 menit.',
                        action: 'Instruksikan tenant optimalkan bahan cepat saji (fast-prep) dan antrean prioritas.'
                    };
                } else {
                    return {
                        level: 'success',
                        badge: '✅ OPERASIONAL AMAN & PRIMA',
                        badgeClass: 'bg-emerald-100 text-emerald-900 border-emerald-300',
                        bannerClass: 'bg-emerald-50/80 border-emerald-200 text-emerald-950',
                        icon: '🎯',
                        summary: 'Kinerja operasional berada pada titik ideal. Makanan tersaji aman sebelum penumpang boarding.',
                        action: 'Pertahankan standar layanan ini. Kapasitas dapur dan kecepatan saji sangat memadai.'
                    };
                }
            },
            copySummary() {
                const text = `FlyDine Juanda - Ringkasan Simulasi Keputusan:
Status: ${this.operationalStatus.badge}
• Skenario: ${this.activeScenario.toUpperCase()}
• Proyeksi Omset: ${this.formatRupiah(this.projectedRevenue)} (${this.revenueDelta >= 0 ? '+' : ''}${this.formatRupiah(this.revenueDelta)})
• Perkiraan Pesanan: ${this.projectedOrders} order (${this.ordersDelta >= 0 ? '+' : ''}${this.ordersDelta} order)
• Waktu Saji SLA: ${this.projectedSla} menit (${this.projectedCompliance}% tepat waktu)
• Omset Terselamatkan: ${this.formatRupiah(this.recoveredRevenue)} (${this.projectedSavedOrders} batal dicegah)
• Rekomendasi Pimpinan: ${this.operationalStatus.action}`;
                navigator.clipboard.writeText(text).then(() => {
                    this.copied = true;
                    setTimeout(() => this.copied = false, 2500);
                });
            }
         }">
        
        <!-- Header DSS: Judul Ringkas & Toggle Parameter Manual -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5 pb-4 border-b border-slate-100">
            <div>
                <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-lg text-xs font-black uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200 mb-1">
                    <span>🔮 Simulasi Eksekutif</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    Simulasi & Perkiraan Dampak Keputusan
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-0.5">
                    Pilih skenario operasional bandara untuk menguji proyeksi omset & kualitas layanan secara instan.
                </p>
            </div>

            <!-- Tombol Toggle Parameter Manual -->
            <button type="button" @click="showCustomSliders = !showCustomSliders" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-extrabold border transition shadow-2xs self-start sm:self-center cursor-pointer"
                    :class="showCustomSliders ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200'">
                <svg class="w-4 h-4 transition-transform duration-200" :class="showCustomSliders ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
                <span x-text="showCustomSliders ? 'Tutup Slider Manual' : '⚙️ Atur Parameter Slider'"></span>
            </button>
        </div>

        <!-- 4 Tombol Skenario Cepat (1-Klik Tanpa Perlu Pusing Atur Slider) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">
            <!-- Skenario 1: Normal -->
            <button type="button" @click="applyPreset('normal')" 
                    class="p-3.5 rounded-xl border-2 text-left transition cursor-pointer flex flex-col justify-between"
                    :class="activeScenario === 'normal' ? 'border-[#005ea2] bg-blue-50/70 shadow-sm' : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50'">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-base">🟢</span>
                    <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-md"
                          :class="activeScenario === 'normal' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'">
                        Aktif
                    </span>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-900">Operasional Normal</h3>
                    <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Sesuai data aktual bandara</p>
                </div>
            </button>

            <!-- Skenario 2: Peak Season -->
            <button type="button" @click="applyPreset('peak_season')" 
                    class="p-3.5 rounded-xl border-2 text-left transition cursor-pointer flex flex-col justify-between"
                    :class="activeScenario === 'peak_season' ? 'border-amber-500 bg-amber-50/70 shadow-sm' : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50'">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-base">🛫</span>
                    <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-md bg-amber-100 text-amber-900">
                        +35% Trafik
                    </span>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-900">Musim Liburan</h3>
                    <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Lonjakan penumpang Juanda</p>
                </div>
            </button>

            <!-- Skenario 3: Fast Track -->
            <button type="button" @click="applyPreset('fast_track')" 
                    class="p-3.5 rounded-xl border-2 text-left transition cursor-pointer flex flex-col justify-between"
                    :class="activeScenario === 'fast_track' ? 'border-sky-500 bg-sky-50/70 shadow-sm' : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50'">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-base">⚡</span>
                    <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-md bg-sky-100 text-sky-900">
                        -4 Menit SLA
                    </span>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-900">Layanan Kilat</h3>
                    <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Runner gate & fast-prep</p>
                </div>
            </button>

            <!-- Skenario 4: Zero Cancel -->
            <button type="button" @click="applyPreset('zero_cancel')" 
                    class="p-3.5 rounded-xl border-2 text-left transition cursor-pointer flex flex-col justify-between"
                    :class="activeScenario === 'zero_cancel' ? 'border-emerald-500 bg-emerald-50/70 shadow-sm' : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50'">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-base">🛡️</span>
                    <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-900">
                        Batal ≤ 1%
                    </span>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-900">Cegah Pembatalan</h3>
                    <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Maksimalkan omset tenant</p>
                </div>
            </button>
        </div>

        <!-- 2 Panel Hasil Dampak Utama (Fokus: Keuangan vs Layanan Bandara) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
            
            <!-- Pilar 1: Finansial & Bisnis -->
            <div class="bg-gradient-to-br from-slate-50 to-emerald-50/40 p-5 rounded-2xl border-2 border-slate-200 hover:border-emerald-300 transition">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <span class="text-xs font-black uppercase tracking-wider text-slate-600">Dampak Finansial (Omset)</span>
                    </div>
                    <span class="px-2.5 py-0.5 text-xs font-black rounded-lg"
                          :class="revenueDelta >= 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'"
                          x-text="(revenueDelta >= 0 ? '+' : '') + formatRupiah(revenueDelta)">
                    </span>
                </div>
                <div class="flex items-baseline gap-2">
                    <h4 class="text-3xl font-black text-slate-900 truncate" x-text="formatRupiah(projectedRevenue)"></h4>
                    <span class="text-xs text-slate-500 font-bold">Proyeksi Omset</span>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-200/70 flex items-center justify-between text-xs font-bold">
                    <span class="text-slate-600 flex items-center gap-1.5">
                        <span>📦 Total Pesanan:</span>
                        <strong class="text-slate-900" x-text="projectedOrders + ' Order (' + (ordersDelta >= 0 ? '+' : '') + ordersDelta + ')'"></strong>
                    </span>
                    <span class="text-emerald-700 bg-emerald-100/70 px-2 py-0.5 rounded-md" 
                          x-show="recoveredRevenue > 0"
                          x-text="'+' + formatRupiah(recoveredRevenue) + ' Terselamatkan'">
                    </span>
                </div>
            </div>

            <!-- Pilar 2: Operasional & Kualitas Layanan SLA -->
            <div class="bg-gradient-to-br from-slate-50 to-sky-50/40 p-5 rounded-2xl border-2 border-slate-200 hover:border-sky-300 transition">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span>
                        <span class="text-xs font-black uppercase tracking-wider text-slate-600">Kinerja Waktu Saji (SLA)</span>
                    </div>
                    <span class="px-2.5 py-0.5 text-xs font-black rounded-lg"
                          :class="projectedSla <= 15 ? 'bg-sky-100 text-sky-800' : 'bg-rose-100 text-rose-800'"
                          x-text="projectedCompliance + '% Tepat Waktu'">
                    </span>
                </div>
                <div class="flex items-baseline gap-2">
                    <h4 class="text-3xl font-black text-slate-900" 
                        :class="projectedSla <= 15 ? 'text-slate-900' : 'text-rose-600'" 
                        x-text="projectedSla + ' Menit'"></h4>
                    <span class="text-xs text-slate-500 font-bold">Rata-rata Masak & Saji</span>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-200/70 flex items-center justify-between text-xs font-bold">
                    <span class="text-slate-600 flex items-center gap-1.5">
                        <span>🎯 Standar Juanda:</span>
                        <strong class="text-slate-900">Maks. 15 Menit</strong>
                    </span>
                    <span class="text-slate-600" x-text="'Est. sisa komplain: ' + projectedComplaints + ' kasus'"></span>
                </div>
            </div>

        </div>

        <!-- Banner Kesimpulan & Rekomendasi Terpadu -->
        <div class="rounded-2xl p-4 sm:p-5 border transition-all duration-300 flex flex-col lg:flex-row lg:items-center justify-between gap-4"
             :class="operationalStatus.bannerClass">
            <div class="flex items-start sm:items-center gap-3">
                <span class="h-10 w-10 rounded-xl bg-white shadow-2xs flex items-center justify-center text-xl shrink-0 border border-slate-200" 
                      x-text="operationalStatus.icon"></span>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-black border"
                              :class="operationalStatus.badgeClass"
                              x-text="operationalStatus.badge"></span>
                        <span class="text-xs text-slate-600 font-bold" x-text="operationalStatus.summary"></span>
                    </div>
                    <p class="text-xs sm:text-sm font-extrabold text-slate-900 mt-1 flex items-start sm:items-center gap-1.5">
                        <span class="text-indigo-600 font-black shrink-0">💡 Rekomendasi:</span>
                        <span x-text="operationalStatus.action"></span>
                    </p>
                </div>
            </div>

            <div class="shrink-0 flex items-center gap-2 self-end lg:self-center">
                <button type="button" @click="copySummary()" 
                        class="inline-flex items-center px-4 py-2 bg-slate-900 hover:bg-slate-800 active:bg-black text-white text-xs font-bold rounded-xl shadow-xs transition duration-150 cursor-pointer gap-2">
                    <svg x-show="!copied" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                    <svg x-show="copied" class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span x-text="copied ? 'Tersalin!' : 'Salin Ringkasan'"></span>
                </button>
            </div>
        </div>

        <!-- Panel Kustomisasi Slider (Accordion Opsional: Hanya Muncul Jika Diklik) -->
        <div x-show="showCustomSliders" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="mt-5 pt-5 border-t border-slate-200">
            
            <!-- Sub-bar Basis Pesanan -->
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 mb-4 flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="flex items-center gap-2 flex-wrap text-xs font-bold text-slate-700">
                    <span>⚙️ Basis Data Acuan:</span>
                    <button type="button" @click="setBaseline({{ (int)$totalOrdersCount > 0 ? (int)$totalOrdersCount : 1 }})" 
                            class="px-2.5 py-1 rounded-md border transition cursor-pointer"
                            :class="baseOrders == {{ (int)$totalOrdersCount > 0 ? (int)$totalOrdersCount : 1 }} ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-700 border-slate-300'">
                        Data Riil ({{ (int)$totalOrdersCount }})
                    </button>
                    <button type="button" @click="setBaseline(100)" 
                            class="px-2.5 py-1 rounded-md border transition cursor-pointer"
                            :class="baseOrders == 100 ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-700 border-slate-300'">
                        100 Order
                    </button>
                    <button type="button" @click="setBaseline(500)" 
                            class="px-2.5 py-1 rounded-md border transition cursor-pointer"
                            :class="baseOrders == 500 ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-700 border-slate-300'">
                        500 Order
                    </button>
                </div>
                <div class="text-[11px] text-slate-500 font-semibold flex items-center gap-2">
                    <span>AOV: <strong class="text-slate-800" x-text="formatRupiah(baseAov)"></strong></span>
                    <span>•</span>
                    <span>Waktu Awal: <strong class="text-slate-800" x-text="baseSlaMinutes + ' mnt'"></strong></span>
                    <span>•</span>
                    <span>Batal Awal: <strong class="text-slate-800" x-text="baseCancelRate + '%'"></strong></span>
                </div>
            </div>

            <!-- 3 Slider Kontrol Manual -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Slider 1 -->
                <div class="bg-white p-3.5 rounded-xl border border-slate-200">
                    <div class="flex items-center justify-between mb-1 text-xs font-bold text-slate-700">
                        <span>📦 Perubahan Pesanan</span>
                        <span class="px-2 py-0.5 text-xs font-black rounded-md"
                              :class="volumePct > 0 ? 'bg-emerald-100 text-emerald-800' : (volumePct < 0 ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-700')"
                              x-text="(volumePct >= 0 ? '+' : '') + volumePct + '%'"></span>
                    </div>
                    <input type="range" min="-50" max="100" step="5" x-model="volumePct" @input="activeScenario = 'custom'" 
                           class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-[#005ea2]">
                    <div class="flex justify-between text-[10px] text-slate-400 font-semibold mt-1">
                        <span>-50%</span><span>Normal (0%)</span><span>+100%</span>
                    </div>
                </div>

                <!-- Slider 2 -->
                <div class="bg-white p-3.5 rounded-xl border border-slate-200">
                    <div class="flex items-center justify-between mb-1 text-xs font-bold text-slate-700">
                        <span>⏱️ Efisiensi Waktu SLA</span>
                        <span class="px-2 py-0.5 text-xs font-black rounded-md bg-sky-100 text-sky-800"
                              x-text="'-' + Number(slaReduction).toFixed(1) + ' Menit'"></span>
                    </div>
                    <input type="range" min="0" max="10" step="0.5" x-model="slaReduction" @input="activeScenario = 'custom'" 
                           class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-sky-600">
                    <div class="flex justify-between text-[10px] text-slate-400 font-semibold mt-1">
                        <span>0m (Standar)</span><span>-5m</span><span>-10m</span>
                    </div>
                </div>

                <!-- Slider 3 -->
                <div class="bg-white p-3.5 rounded-xl border border-slate-200">
                    <div class="flex items-center justify-between mb-1 text-xs font-bold text-slate-700">
                        <span>🚫 Target Pembatalan</span>
                        <span class="px-2 py-0.5 text-xs font-black rounded-md"
                              :class="targetCancelRate < baseCancelRate ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700'"
                              x-text="Number(targetCancelRate).toFixed(1) + '%'"></span>
                    </div>
                    <input type="range" min="0" max="15" step="0.5" x-model="targetCancelRate" @input="activeScenario = 'custom'" 
                           class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-emerald-600">
                    <div class="flex justify-between text-[10px] text-slate-400 font-semibold mt-1">
                        <span>0% (Ideal)</span><span>{{ $cancelRate }}%</span><span>15%</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- 8. Enterprise CRM - Customer Segmentation -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border-2 border-slate-200">
        <div class="flex items-center justify-between mb-5">
            <div>
                <div class="inline-flex items-center gap-2 text-xs font-black text-indigo-700 uppercase tracking-wider mb-1">
                    <span>👑 Customer Loyalty Intel</span>
                </div>
                <h2 class="text-lg font-black text-slate-900 tracking-tight">Enterprise CRM - Segmentasi Pelanggan</h2>
                <p class="text-xs text-slate-500 font-semibold mt-0.5">Distribusi loyalitas frekuensi belanja penumpang bandara</p>
            </div>
        </div>

        @php
            $newCust = $customerSegmentation->new_customers ?? 0;
            $regCust = $customerSegmentation->regular_customers ?? 0;
            $freqCust = $customerSegmentation->frequent_customers ?? 0;
            $totalCust = $customerSegmentation->total_customers ?? 0;
            
            $newPct = $totalCust > 0 ? round(($newCust / $totalCust) * 100) : 0;
            $regPct = $totalCust > 0 ? round(($regCust / $totalCust) * 100) : 0;
            $freqPct = $totalCust > 0 ? round(($freqCust / $totalCust) * 100) : 0;
        @endphp

        <!-- Segmen Bar -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2 text-xs font-bold text-slate-700">
                <span>Distribusi Segmen Pengguna</span>
                <span>Total: {{ number_format($totalCust) }} Pelanggan</span>
            </div>
            <div class="w-full flex h-6 rounded-xl overflow-hidden bg-slate-100 p-1 gap-1">
                <div class="bg-sky-400 rounded-lg flex items-center justify-center text-[10px] font-black text-white transition-all duration-1000 shadow-xs" style="width: {{ max(4, $newPct) }}%" title="Pelanggan Baru ({{ $newPct }}%)">
                    @if($newPct > 5) {{ $newPct }}% @endif
                </div>
                <div class="bg-amber-400 rounded-lg flex items-center justify-center text-[10px] font-black text-white transition-all duration-1000 shadow-xs" style="width: {{ max(4, $regPct) }}%" title="Pelanggan Reguler ({{ $regPct }}%)">
                    @if($regPct > 5) {{ $regPct }}% @endif
                </div>
                <div class="bg-emerald-500 rounded-lg flex items-center justify-center text-[10px] font-black text-white transition-all duration-1000 shadow-xs" style="width: {{ max(4, $freqPct) }}%" title="Pelanggan Setia ({{ $freqPct }}%)">
                    @if($freqPct > 5) {{ $freqPct }}% @endif
                </div>
            </div>
            <div class="flex items-center justify-between mt-3 text-xs font-bold text-slate-600">
                <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-sky-400 mr-2 shadow-xs"></span> Baru (1x pesan)</div>
                <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-amber-400 mr-2 shadow-xs"></span> Reguler (2-5x pesan)</div>
                <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-emerald-500 mr-2 shadow-xs"></span> Setia (>5x pesan)</div>
            </div>
        </div>

        <!-- Kartu Rincian 3 Segmen -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-slate-50 p-5 rounded-2xl border-2 border-slate-200 flex items-center justify-between">
                <div>
                    <span class="text-xs font-black uppercase tracking-wider text-sky-700 block">Pelanggan Baru</span>
                    <span class="text-2xl font-black text-slate-900 mt-1 block">{{ number_format($newCust) }}</span>
                    <span class="text-xs text-slate-500 font-semibold">1 Kali Pemesanan</span>
                </div>
                <div class="h-12 w-12 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center font-black text-sm">
                    1x
                </div>
            </div>

            <div class="bg-slate-50 p-5 rounded-2xl border-2 border-slate-200 flex items-center justify-between">
                <div>
                    <span class="text-xs font-black uppercase tracking-wider text-amber-700 block">Pelanggan Reguler</span>
                    <span class="text-2xl font-black text-slate-900 mt-1 block">{{ number_format($regCust) }}</span>
                    <span class="text-xs text-slate-500 font-semibold">2 - 5 Kali Pemesanan</span>
                </div>
                <div class="h-12 w-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-black text-sm">
                    2-5x
                </div>
            </div>

            <div class="bg-emerald-50 p-5 rounded-2xl border-2 border-emerald-300 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-black uppercase tracking-wider text-emerald-800">Pelanggan Setia</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-600 text-white uppercase">VIP</span>
                    </div>
                    <span class="text-2xl font-black text-slate-900 mt-1 block">{{ number_format($freqCust) }}</span>
                    <span class="text-xs text-emerald-800 font-semibold">> 5 Kali Pemesanan</span>
                </div>
                <div class="h-12 w-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-black shadow-sm text-sm">
                    VIP
                </div>
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
