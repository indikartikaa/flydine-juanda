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

    <!-- 7. Executive Target Planning & Predictive Simulator: Skenario Baik vs Buruk -->
    <div class="bg-white rounded-2xl p-6 sm:p-7 shadow-sm border-2 border-indigo-200" 
         x-data="targetPlanningSimulator()">
        
        <!-- Header: Identitas & Toolbar Kontrol -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-xs font-black uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200 mb-1.5">
                    <span>🎯 Target Planning & Forecasting • Bandara Juanda</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    Kalkulator Target Omset, Pengunjung & Waktu Saji
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-0.5 max-w-2xl">
                    Input data omset & estimasi pengunjung saat ini, sistem otomatis memproyeksikan <strong>skenario baik (target prima)</strong> vs <strong>skenario buruk (risiko waspada)</strong> untuk periode berikutnya.
                </p>
            </div>

            <!-- Toolbar Kanan: Panduan Terpadu, Download PDF, dan Tombol Salin -->
            <div class="flex items-center flex-wrap gap-2">
                <!-- Tombol 1: Panduan & Rumus Terpadu -->
                <button type="button" @click="showGuide = !showGuide" 
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-black border transition shadow-2xs cursor-pointer"
                        :class="showGuide ? 'bg-indigo-600 text-white border-indigo-600 shadow-indigo-600/20' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200'"
                        title="Buka panduan lengkap logika bisnis bandara dan rumus matematika">
                    <span>📘</span>
                    <span x-text="showGuide ? 'Tutup Panduan' : 'Panduan & Rumus Simulasi'"></span>
                </button>

                <!-- Tombol 2: Unduh Dokumen PDF Resmi -->
                <a :href="'{{ route('admin.executive-dashboard.export-simulation-pdf') }}?horizon=' + horizon + '&revenue=' + inputRevenue + '&visitors=' + inputVisitors + '&sla=' + inputSlaMinutes + '&good_growth=' + goodGrowthPct + '&bad_drop=' + badDropPct + '{{ $tenantId ? '&tenant_id=' . $tenantId : '' }}'" 
                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-black bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white shadow-2xs shadow-rose-600/20 transition cursor-pointer"
                   title="Unduh Panduan & Matriks Simulasi Target dalam Format Dokumen PDF Resmi">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Download PDF</span>
                </a>

                <!-- Tombol 3: Salin Ringkasan ke Clipboard -->
                <button type="button" @click="copyPlanSummary()" 
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-black bg-slate-900 hover:bg-slate-800 active:bg-black text-white shadow-2xs transition cursor-pointer"
                        title="Salin ringkasan proyeksi ke clipboard untuk laporan WhatsApp / Memo">
                    <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                    <svg x-show="copied" class="w-3.5 h-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span x-text="copied ? 'Tersalin!' : 'Salin Ringkasan'"></span>
                </button>
            </div>
        </div>

        <!-- CARD TERPADU: PANDUAN LENGKAP & METODOLOGI SIMULASI TARGET (Collapsible) -->
        <div x-show="showGuide" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="mb-6 p-5 sm:p-6 bg-gradient-to-br from-indigo-50/90 via-sky-50/40 to-white rounded-2xl border-2 border-indigo-200 shadow-xs">
            
            <!-- Header Card Panduan Terpadu -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 mb-4 border-b border-indigo-200/80">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-sm shadow-xs font-black">
                        📘
                    </span>
                    <div>
                        <h3 class="text-sm sm:text-base font-black text-indigo-950 uppercase tracking-wide">
                            Panduan & Metodologi Lengkap Simulasi Target
                        </h3>
                        <p class="text-xs text-slate-500 font-semibold">
                            Standar Operasional F&B Bandara Internasional Juanda & Transparansi Rumus Matematika
                        </p>
                    </div>
                </div>
                
                <!-- Quick Download PDF Button inside Guide Card -->
                <a :href="'{{ route('admin.executive-dashboard.export-simulation-pdf') }}?horizon=' + horizon + '&revenue=' + inputRevenue + '&visitors=' + inputVisitors + '&sla=' + inputSlaMinutes + '&good_growth=' + goodGrowthPct + '&bad_drop=' + badDropPct + '{{ $tenantId ? '&tenant_id=' . $tenantId : '' }}'" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-black bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 transition cursor-pointer shrink-0 self-start sm:self-auto">
                    <span>📄 Unduh Format PDF Resmi</span>
                </a>
            </div>

            <!-- Bagian 1: Logika Bisnis F&B Bandara Juanda (Skenario Baik vs Buruk) -->
            <div class="mb-4">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                    <span>🎯</span>
                    <span>1. Logika Bisnis & Pengaruh Waktu Saji (SLA) Terhadap Omset Bandara</span>
                </h4>
                <p class="text-xs text-slate-600 leading-relaxed mb-3">
                    Di lingkungan bandara, waktu adalah segalanya karena penumpang terikat oleh <strong>panggilan boarding pesawat (boarding call)</strong>. Model simulasi ini memetakan hubungan langsung antara <strong>Omset</strong>, <strong>Trafik Penumpang</strong>, dan <strong>Kecepatan Dapur (SLA)</strong>:
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                    <div class="bg-white p-3.5 rounded-xl border border-emerald-200 shadow-2xs">
                        <strong class="text-emerald-800 flex items-center gap-1.5 mb-1 font-black">
                            <span>🟢</span> Skenario Baik (Target Prima / Best Case):
                        </strong>
                        <p class="text-slate-600 font-semibold leading-relaxed">
                            Jika waktu saji cepat (<strong>&le; 9.5 menit/pesanan</strong>), penumpang merasa sangat aman untuk memesan makanan sebelum boarding. Tidak ada pesanan batal (&lt; 1%), konversi pembeli meningkat ke <strong>~55%</strong>, dan omset mencapai target optimal.
                        </p>
                    </div>
                    <div class="bg-white p-3.5 rounded-xl border border-rose-200 shadow-2xs">
                        <strong class="text-rose-800 flex items-center gap-1.5 mb-1 font-black">
                            <span>🔴</span> Skenario Buruk (Risiko Bottleneck / Worst Case):
                        </strong>
                        <p class="text-slate-600 font-semibold leading-relaxed">
                            Jika waktu saji molor melewati batas SOP (<strong>&gt; 15 menit/pesanan</strong>), penumpang yang mendengar panggilan pesawat akan panik dan membatalkan pesanan di kasir. Memicu omset hangus (lost revenue), rating buruk, dan komplain keterlambatan.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Bagian 2: Transparansi Rumus Matematika Proyeksi -->
            <div class="pt-3 border-t border-indigo-200/80">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                    <span>📐</span>
                    <span>2. Transparansi Rumus Matematika Model Proyeksi</span>
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-slate-600 font-semibold text-xs">
                    <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-2xs">
                        <strong class="text-slate-900 block mb-1">A. Rumus Proyeksi Omset:</strong>
                        <p class="font-mono bg-slate-50 p-1.5 rounded text-[11px] text-slate-800 mb-1 border border-slate-100">
                            Omset Baik = Omset Awal × (1 + Pertumbuhan%)<br>
                            Omset Buruk = Omset Awal × (1 - Penurunan%)
                        </p>
                        <p class="text-[11px]">Menghitung potensi batas atas dan bawah omset berdasarkan penyesuaian volume transaksi.</p>
                    </div>
                    <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-2xs">
                        <strong class="text-slate-900 block mb-1">B. Target Pengunjung & Transaksi:</strong>
                        <p class="font-mono bg-slate-50 p-1.5 rounded text-[11px] text-slate-800 mb-1 border border-slate-100">
                            Pengunjung = Pengunjung Awal × (1 &plusmn; Rasio%)<br>
                            Transaksi = Pengunjung × Konversi (40% - 55%)
                        </p>
                        <p class="text-[11px]">Rata-rata ~50% penumpang transit yang singgah melakukan transaksi F&B di terminal.</p>
                    </div>
                    <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-2xs">
                        <strong class="text-slate-900 block mb-1">C. Standar Waktu Saji (SLA):</strong>
                        <p class="font-mono bg-slate-50 p-1.5 rounded text-[11px] text-slate-800 mb-1 border border-slate-100">
                            SLA Baik = Min(9.5, SLA Awal - 2.5 mnt)<br>
                            SLA Buruk = Max(16.0, SLA Awal + 4.5 mnt)
                        </p>
                        <p class="text-[11px]">Batas maksimal SOP Bandara Juanda adalah 15 menit. Waktu saji di atas 15 menit memicu pembatalan fatal.</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Bagian Input Form: Horizon Switcher & 3 Kolom Input Utama -->
        <div class="bg-slate-50/80 p-5 rounded-2xl border-2 border-slate-200 mb-6">
            
            <!-- Baris 1: Horizon Tabs & Tombol Ambil Data Riil -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-200">
                <div class="flex items-center gap-1.5 p-1 bg-white rounded-xl border border-slate-200 shadow-2xs">
                    <button type="button" @click="setHorizon('weekly')" 
                            class="px-3 py-1.5 rounded-lg text-xs font-black transition cursor-pointer flex items-center gap-1.5"
                            :class="horizon === 'weekly' ? 'bg-[#005ea2] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'">
                        <span>📅 Mode Mingguan</span>
                        <span class="text-[10px] font-bold opacity-80">(Minggu Ini &rarr; Minggu Depan)</span>
                    </button>
                    <button type="button" @click="setHorizon('monthly')" 
                            class="px-3 py-1.5 rounded-lg text-xs font-black transition cursor-pointer flex items-center gap-1.5"
                            :class="horizon === 'monthly' ? 'bg-[#005ea2] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'">
                        <span>🗓️ Mode Bulanan</span>
                        <span class="text-[10px] font-bold opacity-80">(Bulan Ini &rarr; Bulan Depan)</span>
                    </button>
                </div>

                <!-- Tombol 1-Klik Isi Otomatis dari Data Riil Sistem -->
                <button type="button" @click="loadRealData()" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black bg-white hover:bg-indigo-50 text-indigo-700 border border-indigo-200 shadow-2xs transition cursor-pointer self-start sm:self-auto"
                        title="Klik untuk mengisi input secara instan dari data riil transaksi database saat ini">
                    <span>⚡ Isi Otomatis dari Data Riil Sistem</span>
                    <span class="text-[10px] bg-indigo-100 text-indigo-800 px-1.5 py-0.5 rounded font-extrabold"
                          x-text="horizon === 'weekly' ? formatRupiah(realWeeklyRevenue) : formatRupiah(realMonthlyRevenue)">
                    </span>
                </button>
            </div>

            <!-- Baris 2: 3 Input Form (Omset, Pengunjung, dan Waktu Saji Saat Ini) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                
                <!-- Input 1: Omset Saat Ini -->
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500/20 transition">
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wide mb-1 flex items-center justify-between">
                        <span class="flex items-center gap-1.5">
                            <span>💰</span>
                            <span x-text="horizon === 'weekly' ? 'Omset Minggu Ini (Rp)' : 'Omset Bulan Ini (Rp)'"></span>
                        </span>
                        <span class="text-[10px] text-slate-400 font-bold">Ketik Bebas</span>
                    </label>
                    <div class="relative mt-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 font-extrabold text-sm">Rp</span>
                        <input type="text" 
                               inputmode="numeric" 
                               x-model="rawRevenue" 
                               @input="handleRevenueInput($event.target.value)" 
                               @blur="formatRevenueField()" 
                               placeholder="Contoh: 90.000 atau 15.000.000"
                               class="w-full pl-10 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 font-black text-base focus:bg-white focus:outline-none transition">
                    </div>
                    <div class="mt-1.5 flex items-center justify-between text-[11px] font-semibold text-slate-500">
                        <span>Nominal Terbaca:</span>
                        <strong class="text-indigo-700 font-extrabold" x-text="formatRupiah(inputRevenue)"></strong>
                    </div>
                    <!-- Tombol Cepat Pilihan Omset -->
                    <div class="mt-2 pt-2 border-t border-slate-100 flex items-center gap-1.5 flex-wrap">
                        <span class="text-[10px] font-bold text-slate-400">Pilihan Cepat:</span>
                        <button type="button" @click="setRevenue(90000)" class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 text-slate-600 transition cursor-pointer">90 Rb</button>
                        <button type="button" @click="setRevenue(500000)" class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 text-slate-600 transition cursor-pointer">500 Rb</button>
                        <button type="button" @click="setRevenue(5000000)" class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 text-slate-600 transition cursor-pointer">5 Jt</button>
                        <button type="button" @click="setRevenue(15000000)" class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 text-slate-600 transition cursor-pointer">15 Jt</button>
                        <button type="button" @click="setRevenue(50000000)" class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 text-slate-600 transition cursor-pointer">50 Jt</button>
                    </div>
                </div>

                <!-- Input 2: Jumlah Pengunjung / Pelanggan -->
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500/20 transition">
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wide mb-1 flex items-center justify-between">
                        <span class="flex items-center gap-1.5">
                            <span>👥</span>
                            <span x-text="horizon === 'weekly' ? 'Pengunjung Minggu Ini' : 'Pengunjung Bulan Ini'"></span>
                        </span>
                        <span class="text-[10px] text-slate-400 font-bold">Estimasi Pax</span>
                    </label>
                    <div class="relative mt-1">
                        <input type="text" 
                               inputmode="numeric" 
                               x-model="rawVisitors" 
                               @input="handleVisitorsInput($event.target.value)" 
                               @blur="formatVisitorsField()" 
                               placeholder="Contoh: 450 atau 1.500"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 font-black text-base focus:bg-white focus:outline-none transition">
                        <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400 font-bold text-xs">Orang</span>
                    </div>
                    <div class="mt-1.5 flex items-center justify-between text-[11px] font-semibold text-slate-500">
                        <span>Estimasi Belanja (AOV):</span>
                        <strong class="text-slate-800 font-extrabold" x-text="formatRupiah(currentAov) + '/order'"></strong>
                    </div>
                    <!-- Tombol Cepat Pilihan Pengunjung -->
                    <div class="mt-2 pt-2 border-t border-slate-100 flex items-center gap-1.5 flex-wrap">
                        <span class="text-[10px] font-bold text-slate-400">Pilihan:</span>
                        <button type="button" @click="setVisitors(50)" class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 text-slate-600 transition cursor-pointer">50 Pax</button>
                        <button type="button" @click="setVisitors(250)" class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 text-slate-600 transition cursor-pointer">250 Pax</button>
                        <button type="button" @click="setVisitors(500)" class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 text-slate-600 transition cursor-pointer">500 Pax</button>
                        <button type="button" @click="setVisitors(1500)" class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 text-slate-600 transition cursor-pointer">1.500 Pax</button>
                    </div>
                </div>

                <!-- Input 3: Rata-Rata Waktu Saji Saat Ini -->
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500/20 transition">
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wide mb-1 flex items-center justify-between">
                        <span class="flex items-center gap-1.5">
                            <span>⏱️</span>
                            <span>Rata-rata Waktu Saji Saat Ini</span>
                        </span>
                        <span class="text-[10px] text-slate-400 font-bold">SLA Dapur</span>
                    </label>
                    <div class="relative mt-1">
                        <input type="number" min="1" max="60" step="0.5" x-model.number="inputSlaMinutes" 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 font-black text-base focus:bg-white focus:outline-none transition">
                        <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400 font-bold text-xs">Menit/Order</span>
                    </div>
                    <div class="mt-1.5 flex items-center justify-between text-[11px] font-semibold text-slate-500">
                        <span>Standar Bandara Juanda:</span>
                        <span class="font-extrabold px-1.5 py-0.2 rounded text-[10px]"
                              :class="inputSlaMinutes <= 15 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'"
                              x-text="inputSlaMinutes <= 15 ? '≤ 15 Menit (Sesuai SOP)' : '> 15 Menit (Perlu Diperbaiki)'"></span>
                    </div>
                    <!-- Tombol Cepat Pilihan SLA -->
                    <div class="mt-2 pt-2 border-t border-slate-100 flex items-center gap-1.5 flex-wrap">
                        <span class="text-[10px] font-bold text-slate-400">Pilihan:</span>
                        <button type="button" @click="inputSlaMinutes = 7.0" class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 text-slate-600 transition cursor-pointer">7 mnt (Kilat)</button>
                        <button type="button" @click="inputSlaMinutes = 11.5" class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 text-slate-600 transition cursor-pointer">11.5 mnt (Normal)</button>
                        <button type="button" @click="inputSlaMinutes = 18.0" class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-rose-50 hover:bg-rose-100 hover:text-rose-800 text-rose-700 transition cursor-pointer">18 mnt (Lambat)</button>
                    </div>
                </div>

            </div>

            <!-- Penyesuaian Sensitivitas Persentase Target (Sliders Kecil) -->
            <div class="mt-4 pt-3 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs font-bold text-slate-600">
                <div class="flex items-center gap-3">
                    <span>🎯 Sensitivitas Target Skenario:</span>
                    <div class="flex items-center gap-2">
                        <span class="text-emerald-700 font-black">Target Baik:</span>
                        <input type="range" min="10" max="40" step="5" x-model.number="goodGrowthPct" class="w-20 accent-emerald-600">
                        <span class="text-emerald-800 font-black" x-text="'+' + goodGrowthPct + '%'"></span>
                    </div>
                    <span class="text-slate-300">•</span>
                    <div class="flex items-center gap-2">
                        <span class="text-rose-700 font-black">Risiko Buruk:</span>
                        <input type="range" min="10" max="40" step="5" x-model.number="badDropPct" class="w-20 accent-rose-600">
                        <span class="text-rose-800 font-black" x-text="'-' + badDropPct + '%'"></span>
                    </div>
                </div>
                <div class="text-[11px] text-slate-500 font-semibold">
                    Target Proyeksi: <strong class="text-slate-800" x-text="horizon === 'weekly' ? 'Minggu Depan' : 'Bulan Depan'"></strong>
                </div>
            </div>

        </div>

        <!-- HASIL UTAMA: 2 Skenario Berdampingan (🟢 Skenario Baik vs 🔴 Skenario Buruk) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            
            <!-- 🟢 Skenario Baik / Target Prima (Best Case) -->
            <div class="bg-gradient-to-br from-emerald-50/70 via-white to-slate-50 p-6 rounded-2xl border-2 border-emerald-300 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="h-8 w-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-black text-sm shadow-2xs">
                            🟢
                        </span>
                        <div>
                            <span class="text-[11px] font-black uppercase tracking-wider text-emerald-800 block">
                                Skenario Baik (Target Prima)
                            </span>
                            <h3 class="text-base font-black text-slate-900" 
                                x-text="horizon === 'weekly' ? 'Target Performa Minggu Depan' : 'Target Performa Bulan Depan'"></h3>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-xl text-xs font-black bg-emerald-100 text-emerald-900 border border-emerald-200"
                          x-text="'+' + goodGrowthPct + '% Pertumbuhan'">
                    </span>
                </div>

                <!-- 1. Omset yang Baik -->
                <div class="bg-white p-4 rounded-xl border border-emerald-200 mb-3 shadow-2xs">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wide block">💰 Omset yang Baik Itu Berapa?</span>
                    <div class="flex items-baseline justify-between mt-1 flex-wrap gap-2">
                        <div class="text-3xl font-black text-emerald-700" x-text="formatRupiah(goodRevenue)"></div>
                        <span class="text-xs font-black px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800"
                              x-text="'+' + formatRupiah(goodRevenueDelta) + ' (' + '+' + goodGrowthPct + '%)'"></span>
                    </div>
                    <div class="text-[11px] text-slate-500 font-semibold mt-1">
                        Kenaikan omset berhasil diraih melalui percepatan saji dan konversi pengunjung prima.
                    </div>
                </div>

                <!-- 2. Jumlah Pengunjung yang Dibutuhkan -->
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div class="bg-white p-3.5 rounded-xl border border-emerald-100 shadow-2xs">
                        <span class="text-[11px] font-bold text-slate-500 block">👥 Target Pengunjung:</span>
                        <div class="text-xl font-black text-slate-900 mt-0.5" x-text="goodVisitors.toLocaleString('id-ID') + ' Orang'"></div>
                        <span class="text-[11px] text-emerald-700 font-bold" x-text="'+' + goodVisitorsDelta + ' orang pax'"></span>
                    </div>
                    <div class="bg-white p-3.5 rounded-xl border border-emerald-100 shadow-2xs">
                        <span class="text-[11px] font-bold text-slate-500 block">📦 Estimasi Transaksi:</span>
                        <div class="text-xl font-black text-slate-900 mt-0.5" x-text="goodEstimatedOrders.toLocaleString('id-ID') + ' Order'"></div>
                        <span class="text-[11px] text-emerald-700 font-bold">Konversi Belanja ~55%</span>
                    </div>
                </div>

                <!-- 3. Rata-Rata Waktu Saji yang Harus Dijaga -->
                <div class="bg-white p-4 rounded-xl border border-emerald-200 mb-3 shadow-2xs">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">⏱️ Waktu Saji Melayani Pesanan:</span>
                        <span class="text-[10px] font-black px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800">
                            100% Tepat Waktu Boarding
                        </span>
                    </div>
                    <div class="flex items-baseline gap-2 mt-0.5">
                        <div class="text-2xl font-black text-emerald-700" x-text="goodSla + ' Menit / Pesanan'"></div>
                        <span class="text-xs text-slate-500 font-bold" x-text="'(Target percepatan -' + (Number(inputSlaMinutes) - goodSla).toFixed(1) + ' mnt)'"></span>
                    </div>
                    <div class="mt-2 text-xs text-slate-700 font-semibold flex items-center gap-1.5">
                        <span>🎯</span>
                        <span class="leading-relaxed">
                            <strong>Alasan Target <span x-text="goodSla + ' Menit'"></span>:</strong> 
                            Makanan tersaji kilat sebelum boarding &rarr; Pembatalan pesanan ditekan hingga <strong class="text-emerald-700">&lt; 1%</strong> &amp; bebas komplain!
                        </span>
                    </div>
                </div>

                <!-- Rekomendasi Tindakan Skenario Baik (Dinamis Berdasarkan Angka Input) -->
                <div class="bg-emerald-100/60 p-3.5 rounded-xl border border-emerald-200 text-xs text-emerald-950 font-semibold">
                    <div class="flex items-center justify-between mb-1.5 pb-1 border-b border-emerald-200/60">
                        <span class="font-black text-emerald-900 flex items-center gap-1.5">
                            <span>✅</span>
                            <span>Langkah Kerja untuk Meraih Target Baik:</span>
                        </span>
                        <span class="text-[10px] font-extrabold bg-emerald-200 text-emerald-900 px-1.5 py-0.5 rounded">Rekomendasi Operasional</span>
                    </div>
                    <ul class="space-y-1.5 text-[11px]">
                        <template x-for="(step, idx) in goodActionSteps" :key="idx">
                            <li class="flex items-start gap-1.5">
                                <span class="text-emerald-700 font-bold shrink-0">•</span>
                                <span class="leading-relaxed" x-html="step"></span>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            <!-- 🔴 Skenario Buruk / Perlu Waspada (Worst Case) -->
            <div class="bg-gradient-to-br from-rose-50/70 via-white to-slate-50 p-6 rounded-2xl border-2 border-rose-300 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="h-8 w-8 rounded-xl bg-rose-600 text-white flex items-center justify-center font-black text-sm shadow-2xs">
                            🔴
                        </span>
                        <div>
                            <span class="text-[11px] font-black uppercase tracking-wider text-rose-800 block">
                                Skenario Buruk (Perlu Waspada)
                            </span>
                            <h3 class="text-base font-black text-slate-900" 
                                x-text="horizon === 'weekly' ? 'Risiko Performa Minggu Depan' : 'Risiko Performa Bulan Depan'"></h3>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-xl text-xs font-black bg-rose-100 text-rose-900 border border-rose-200"
                          x-text="'-' + badDropPct + '% Penurunan'">
                    </span>
                </div>

                <!-- 1. Omset yang Buruk -->
                <div class="bg-white p-4 rounded-xl border border-rose-200 mb-3 shadow-2xs">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wide block">⚠️ Omset yang Buruk Itu Berapa?</span>
                    <div class="flex items-baseline justify-between mt-1 flex-wrap gap-2">
                        <div class="text-3xl font-black text-rose-700" x-text="formatRupiah(badRevenue)"></div>
                        <span class="text-xs font-black px-2 py-0.5 rounded-md bg-rose-100 text-rose-800"
                              x-text="'-' + formatRupiah(badRevenueDelta) + ' (' + '-' + badDropPct + '%)'"></span>
                    </div>
                    <div class="text-[11px] text-slate-500 font-semibold mt-1">
                        Terjadi penurunan omset akibat antrean panjang dan lonjakan pesanan yang dibatalkan.
                    </div>
                </div>

                <!-- 2. Pengunjung & Pembatalan yang Membengkak -->
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div class="bg-white p-3.5 rounded-xl border border-rose-100 shadow-2xs">
                        <span class="text-[11px] font-bold text-slate-500 block">👥 Trafik Pengunjung:</span>
                        <div class="text-xl font-black text-slate-900 mt-0.5" x-text="badVisitors.toLocaleString('id-ID') + ' Orang'"></div>
                        <span class="text-[11px] text-rose-700 font-bold" x-text="'-' + badVisitorsDelta + ' orang berkurang'"></span>
                    </div>
                    <div class="bg-white p-3.5 rounded-xl border border-rose-100 shadow-2xs">
                        <span class="text-[11px] font-bold text-slate-500 block">🚫 Pesanan Batal:</span>
                        <div class="text-xl font-black text-rose-700 mt-0.5" x-text="badCancelRate + '% Batal'"></div>
                        <span class="text-[11px] text-rose-800 font-bold" x-text="'Hangus ' + formatRupiah(badLostRevenue)"></span>
                    </div>
                </div>

                <!-- 3. Rata-Rata Waktu Saji yang Menyebabkan Masalah -->
                <div class="bg-white p-4 rounded-xl border border-rose-200 mb-3 shadow-2xs">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">⏱️ Waktu Saji yang Menyebabkan Masalah:</span>
                        <span class="text-[10px] font-black px-2 py-0.5 rounded-md bg-rose-100 text-rose-800">
                            Melebihi SOP Maks. 15 Mnt
                        </span>
                    </div>
                    <div class="flex items-baseline gap-2 mt-0.5">
                        <div class="text-2xl font-black text-rose-700" x-text="badSla + ' Menit / Pesanan'"></div>
                        <span class="text-xs text-rose-600 font-bold">(Terlalu lambat & antrean menumpuk)</span>
                    </div>
                    <div class="mt-2 text-xs text-slate-700 font-semibold flex items-center gap-1.5">
                        <span>🚨</span>
                        <span class="leading-relaxed">
                            <strong>Dampak Keterlambatan:</strong> Penumpang mendengar <em>last call boarding</em> &rarr; Pesanan ditinggal/batal &rarr; Est. komplain naik ke <strong class="text-rose-700" x-text="badComplaints + ' kasus'"></strong>!
                        </span>
                    </div>
                </div>

                <!-- Peringatan & Mitigasi Skenario Buruk (Dinamis Berdasarkan Angka Input) -->
                <div class="bg-rose-100/60 p-3.5 rounded-xl border border-rose-200 text-xs text-rose-950 font-semibold">
                    <div class="flex items-center justify-between mb-1.5 pb-1 border-b border-rose-200/60">
                        <span class="font-black text-rose-900 flex items-center gap-1.5">
                            <span>⚠️</span>
                            <span>Langkah Mitigasi untuk Mencegah Skenario Buruk:</span>
                        </span>
                        <span class="text-[10px] font-extrabold bg-rose-200 text-rose-900 px-1.5 py-0.5 rounded">Langkah Pencegahan</span>
                    </div>
                    <ul class="space-y-1.5 text-[11px]">
                        <template x-for="(step, idx) in badMitigationSteps" :key="idx">
                            <li class="flex items-start gap-1.5">
                                <span class="text-rose-700 font-bold shrink-0">•</span>
                                <span class="leading-relaxed" x-html="step"></span>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

        </div>

        <!-- Tabel Perbandingan Komparasi Eksekutif (Head-to-Head Table) -->
        <div class="overflow-x-auto rounded-2xl border-2 border-slate-200 mb-6 shadow-2xs">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 border-b border-slate-200 text-slate-700 uppercase font-black text-[11px]">
                    <tr>
                        <th class="p-3.5">Indikator Kunci</th>
                        <th class="p-3.5 bg-slate-200/60">Kondisi Input Saat Ini</th>
                        <th class="p-3.5 bg-emerald-100/70 text-emerald-950">🟢 Skenario Baik (Target Prima)</th>
                        <th class="p-3.5 bg-rose-100/70 text-rose-950">🔴 Skenario Buruk (Perlu Waspada)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 font-semibold text-slate-800">
                    <tr class="hover:bg-slate-50/80">
                        <td class="p-3.5 font-black text-slate-900">💰 Proyeksi Omset Total</td>
                        <td class="p-3.5 bg-slate-50 font-bold" x-text="formatRupiah(inputRevenue)"></td>
                        <td class="p-3.5 bg-emerald-50/50 font-black text-emerald-700" x-text="formatRupiah(goodRevenue) + ' (+' + goodGrowthPct + '%)'"></td>
                        <td class="p-3.5 bg-rose-50/50 font-black text-rose-700" x-text="formatRupiah(badRevenue) + ' (-' + badDropPct + '%)'"></td>
                    </tr>
                    <tr class="hover:bg-slate-50/80">
                        <td class="p-3.5 font-black text-slate-900">👥 Jumlah Pengunjung / Pax</td>
                        <td class="p-3.5 bg-slate-50" x-text="Number(inputVisitors).toLocaleString('id-ID') + ' orang'"></td>
                        <td class="p-3.5 bg-emerald-50/50 font-bold text-emerald-800" x-text="goodVisitors.toLocaleString('id-ID') + ' orang (+' + goodVisitorsDelta + ')'"></td>
                        <td class="p-3.5 bg-rose-50/50 font-bold text-rose-800" x-text="badVisitors.toLocaleString('id-ID') + ' orang (-' + badVisitorsDelta + ')'"></td>
                    </tr>
                    <tr class="hover:bg-slate-50/80">
                        <td class="p-3.5 font-black text-slate-900">⏱️ Waktu Saji Rata-Rata (SLA)</td>
                        <td class="p-3.5 bg-slate-50" x-text="Number(inputSlaMinutes).toFixed(1) + ' Menit/Pesanan'"></td>
                        <td class="p-3.5 bg-emerald-50/50 font-black text-emerald-700" x-text="goodSla + ' Menit (Kilat & Aman)'"></td>
                        <td class="p-3.5 bg-rose-50/50 font-black text-rose-700" x-text="badSla + ' Menit (Terlambat & Delay)'"></td>
                    </tr>
                    <tr class="hover:bg-slate-50/80">
                        <td class="p-3.5 font-black text-slate-900">🚫 Rasio Pesanan Dibatalkan</td>
                        <td class="p-3.5 bg-slate-50">~5.0% (Standar)</td>
                        <td class="p-3.5 bg-emerald-50/50 text-emerald-800 font-extrabold">&lt; 1.0% (0 Komplain Boarding)</td>
                        <td class="p-3.5 bg-rose-50/50 text-rose-800 font-extrabold" x-text="badCancelRate + '% (Banyak Batal Mendadak)'"></td>
                    </tr>
                    <tr class="hover:bg-slate-50/80">
                        <td class="p-3.5 font-black text-slate-900">🎯 Kepatuhan Standar Juanda (&le; 15m)</td>
                        <td class="p-3.5 bg-slate-50" x-text="inputSlaMinutes <= 15 ? '100% Memenuhi' : 'Melanggar Batas'"></td>
                        <td class="p-3.5 bg-emerald-50/50 font-bold text-emerald-700">100% Siap Sebelum Boarding</td>
                        <td class="p-3.5 bg-rose-50/50 font-bold text-rose-700" x-text="badCompliance + '% (Sebagian Penumpang Telat)'"></td>
                    </tr>
                </tbody>
            </table>
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

// Alpine.js Component: Executive Target Planning & Predictive Simulator
window.targetPlanningSimulator = function() {
    return {
        // 1. Pilihan Horizon Waktu Proyeksi
        horizon: 'weekly',

        // 2. Data Riil Aktual dari Database Sistem
        realWeeklyRevenue: {{ (float)$weeklyTotalRevenue }},
        realWeeklyOrders: {{ (int)$weeklyOrdersCount }},
        realWeeklyVisitors: {{ (int)$weeklyVisitors }},
        realWeeklySla: {{ (float)$weeklyAvgSla }},
        realWeeklyAov: {{ (float)$weeklyAov }},

        realMonthlyRevenue: {{ (float)$monthlyTotalRevenue }},
        realMonthlyOrders: {{ (int)$monthlyOrdersCount }},
        realMonthlyVisitors: {{ (int)$monthlyVisitors }},
        realMonthlySla: {{ (float)$monthlyAvgSla }},
        realMonthlyAov: {{ (float)$monthlyAov }},

        // 3. Form Input Aktif (Dapat diedit bebas oleh Admin atau diisi otomatis)
        inputRevenue: {{ (float)($weeklyTotalRevenue > 0 ? $weeklyTotalRevenue : ($totalRevenue > 0 ? $totalRevenue : 15000000)) }},
        rawRevenue: '',
        inputVisitors: {{ (int)($weeklyVisitors > 0 ? $weeklyVisitors : 450) }},
        rawVisitors: '',
        inputSlaMinutes: {{ (float)($weeklyAvgSla > 0 ? $weeklyAvgSla : 12.0) }},

        // 4. Parameter Penyesuaian Persentase
        goodGrowthPct: 20,
        badDropPct: 20,

        // 5. UI Toggles
        showGuide: false,
        showFormula: false,
        copied: false,

        init() {
            this.rawRevenue = Number(this.inputRevenue).toLocaleString('id-ID');
            this.rawVisitors = Number(this.inputVisitors).toLocaleString('id-ID');
        },

        // 6. Methods Kontrol & Input Sanitizer (Mencegah bug 90.000 terbaca 90)
        handleRevenueInput(val) {
            const clean = String(val || '').replace(/[^0-9]/g, '');
            this.inputRevenue = parseInt(clean, 10) || 0;
            this.rawRevenue = val;
        },

        formatRevenueField() {
            this.rawRevenue = this.inputRevenue > 0 ? Number(this.inputRevenue).toLocaleString('id-ID') : '0';
        },

        setRevenue(amount) {
            this.inputRevenue = Number(amount) || 0;
            this.rawRevenue = this.inputRevenue > 0 ? Number(this.inputRevenue).toLocaleString('id-ID') : '0';
        },

        handleVisitorsInput(val) {
            const clean = String(val || '').replace(/[^0-9]/g, '');
            this.inputVisitors = parseInt(clean, 10) || 0;
            this.rawVisitors = val;
        },

        formatVisitorsField() {
            this.rawVisitors = this.inputVisitors > 0 ? Number(this.inputVisitors).toLocaleString('id-ID') : '0';
        },

        setVisitors(amount) {
            this.inputVisitors = Number(amount) || 0;
            this.rawVisitors = this.inputVisitors > 0 ? Number(this.inputVisitors).toLocaleString('id-ID') : '0';
        },

        setHorizon(type) {
            this.horizon = type;
            if (type === 'weekly') {
                this.inputRevenue = this.realWeeklyRevenue > 0 ? this.realWeeklyRevenue : 15000000;
                this.inputVisitors = this.realWeeklyVisitors > 0 ? this.realWeeklyVisitors : 450;
                this.inputSlaMinutes = this.realWeeklySla > 0 ? this.realWeeklySla : 12.0;
            } else {
                this.inputRevenue = this.realMonthlyRevenue > 0 ? this.realMonthlyRevenue : 60000000;
                this.inputVisitors = this.realMonthlyVisitors > 0 ? this.realMonthlyVisitors : 1800;
                this.inputSlaMinutes = this.realMonthlySla > 0 ? this.realMonthlySla : 12.0;
            }
            this.rawRevenue = this.inputRevenue.toLocaleString('id-ID');
            this.rawVisitors = this.inputVisitors.toLocaleString('id-ID');
        },

        loadRealData() {
            if (this.horizon === 'weekly') {
                this.inputRevenue = this.realWeeklyRevenue > 0 ? this.realWeeklyRevenue : 15000000;
                this.inputVisitors = this.realWeeklyVisitors > 0 ? this.realWeeklyVisitors : 450;
                this.inputSlaMinutes = this.realWeeklySla > 0 ? this.realWeeklySla : 12.0;
            } else {
                this.inputRevenue = this.realMonthlyRevenue > 0 ? this.realMonthlyRevenue : 60000000;
                this.inputVisitors = this.realMonthlyVisitors > 0 ? this.realMonthlyVisitors : 1800;
                this.inputSlaMinutes = this.realMonthlySla > 0 ? this.realMonthlySla : 12.0;
            }
            this.rawRevenue = this.inputRevenue.toLocaleString('id-ID');
            this.rawVisitors = this.inputVisitors.toLocaleString('id-ID');
        },

        // 7. Getters & Kalkulasi Logis Skenario Baik vs Buruk
        get currentAov() {
            const visitors = Math.max(1, Number(this.inputVisitors) || 1);
            const rev = Math.max(0, Number(this.inputRevenue) || 0);
            const estBuyers = Math.max(1, Math.round(visitors * 0.5));
            return Math.round(rev / estBuyers);
        },

        // 🟢 SKENARIO BAIK / TARGET PRIMA (Best Case)
        get goodRevenue() {
            const base = Number(this.inputRevenue) || 0;
            return Math.round(base * (1 + (Number(this.goodGrowthPct) / 100)));
        },
        get goodRevenueDelta() {
            return this.goodRevenue - (Number(this.inputRevenue) || 0);
        },
        get goodVisitors() {
            const base = Number(this.inputVisitors) || 0;
            return Math.round(base * (1 + (Number(this.goodGrowthPct) * 0.75 / 100)));
        },
        get goodVisitorsDelta() {
            return this.goodVisitors - (Number(this.inputVisitors) || 0);
        },
        get goodEstimatedOrders() {
            return Math.round(this.goodVisitors * 0.55);
        },
        get goodSla() {
            const fast = Number(this.inputSlaMinutes) - 2.5;
            return Math.max(6.0, Math.min(9.5, +fast.toFixed(1)));
        },
        get goodCancelRate() {
            return 0.8;
        },
        get goodCompliance() {
            return 100;
        },
        get goodSavedRevenue() {
            return Math.round(this.goodRevenue * 0.08);
        },

        // Langkah Kerja Dinamis Skenario Baik
        get goodActionSteps() {
            const steps = [];
            const sla = Number(this.inputSlaMinutes) || 12;
            const aov = this.currentAov;
            const visitors = Number(this.inputVisitors) || 0;
            const period = this.horizon === 'weekly' ? 'minggu depan' : 'bulan depan';

            if (sla > 15) {
                steps.push('<strong>Pangkas SLA Kritis:</strong> Waktu saji saat ini (' + sla.toFixed(1) + ' mnt) melanggar batas maksimal SOP Bandara Juanda (15 mnt). Wajib pangkas waktu masak ke <strong>' + this.goodSla + ' menit</strong> dengan sistem pra-bumbu agar pesanan tidak batal saat panggilan boarding.');
            } else if (sla > 10) {
                steps.push('<strong>Akselerasi Fast-Prep:</strong> Pangkas waktu saji dari ' + sla.toFixed(1) + ' menit menjadi <strong>' + this.goodSla + ' menit/pesanan</strong>. Siapkan bahan baku siap saji sebelum jam sibuk penerbangan agar penumpang merasa tenang memesan makanan.');
            } else {
                steps.push('<strong>Promosikan Layanan Kilat:</strong> SLA saat ini sangat cepat (' + sla.toFixed(1) + ' mnt). Pasang jaminan promosi <em>Pasti Siap dalam ' + this.goodSla + ' Menit</em> untuk menarik penumpang transit yang terburu-buru menuju boarding gate.');
            }

            if (aov < 25000) {
                steps.push('<strong>Upselling & Bundling:</strong> Rata-rata belanja saat ini relatif hemat (' + this.formatRupiah(aov) + '/order). Tawarkan paket kombo makanan + minuman untuk meningkatkan nilai belanja dan mencapai target <strong>' + this.formatRupiah(this.goodRevenue) + '</strong>.');
            } else if (aov >= 25000 && aov < 65000) {
                steps.push('<strong>Optimalkan Menu Terlaris:</strong> Nilai transaksi sudah stabil (' + this.formatRupiah(aov) + '/order). Siapkan stok ekstra untuk 3 menu terlaris agar tidak terjadi kehabisan stok saat jam penerbangan padat.');
            } else {
                steps.push('<strong>Layanan Pesanan Premium:</strong> Nilai transaksi tinggi (' + this.formatRupiah(aov) + '/order). Gunakan kemasan takeaway eksklusif ramah kabin pesawat untuk menjaga kepuasan penumpang eksekutif.');
            }

            if (visitors >= 600 || (this.horizon === 'weekly' && visitors >= 250)) {
                steps.push('<strong>Manajemen Antrean Padat:</strong> Menghadapi target <strong>' + this.goodVisitors.toLocaleString('id-ID') + ' pengunjung</strong>, operasikan jalur antrean takeaway khusus dan siagakan staf runner gate.');
            } else {
                steps.push('<strong>Tingkatkan Daya Tarik Toko:</strong> Dari proyeksi <strong>' + this.goodVisitors.toLocaleString('id-ID') + ' pengunjung</strong>, pasang display banner promo di lorong terminal untuk menaikkan konversi belanja.');
            }

            steps.push('<strong>Realisasi Target Omset:</strong> Amankan tambahan omset <strong>+' + this.formatRupiah(this.goodRevenueDelta) + '</strong> (+' + this.goodGrowthPct + '%) ' + period + ' dengan mengejar sekitar <strong>' + Math.max(1, Math.round(this.goodVisitorsDelta * 0.55)) + ' transaksi baru</strong>.');

            return steps;
        },

        // 🔴 SKENARIO BURUK / PERLU WASPADA (Worst Case)
        get badRevenue() {
            const base = Number(this.inputRevenue) || 0;
            return Math.round(base * (1 - (Number(this.badDropPct) / 100)));
        },
        get badRevenueDelta() {
            return (Number(this.inputRevenue) || 0) - this.badRevenue;
        },
        get badVisitors() {
            const base = Number(this.inputVisitors) || 0;
            return Math.round(base * (1 - (Number(this.badDropPct) * 0.6 / 100)));
        },
        get badVisitorsDelta() {
            return (Number(this.inputVisitors) || 0) - this.badVisitors;
        },
        get badEstimatedOrders() {
            return Math.round(this.badVisitors * 0.40);
        },
        get badSla() {
            const slow = Math.max(16.0, Number(this.inputSlaMinutes) + 4.5);
            return Math.min(22.0, +slow.toFixed(1));
        },
        get badCancelRate() {
            return 14.5;
        },
        get badCompliance() {
            const over = this.badSla - 15;
            return Math.max(30, +(85 - over * 12).toFixed(1));
        },
        get badLostRevenue() {
            return Math.round(Number(this.inputRevenue) * (this.badCancelRate / 100));
        },
        get badComplaints() {
            return Math.max(3, Math.round(Number(this.inputVisitors) * 0.02));
        },

        // Langkah Mitigasi Dinamis Skenario Buruk
        get badMitigationSteps() {
            const steps = [];
            const sla = Number(this.inputSlaMinutes) || 12;

            if (sla > 15) {
                steps.push('<strong>Krisis Antrean Fatal:</strong> SLA awal (' + sla.toFixed(1) + ' mnt) sudah melanggar SOP dan berisiko melonjak tembus <strong>' + this.badSla + ' menit</strong>. Penumpang yang terdesak jam boarding dipastikan membatalkan pesanan secara massal.');
            } else {
                steps.push('<strong>Cegah Lonjakan Waktu Saji:</strong> Jangan biarkan waktu masak melorot ke <strong>' + this.badSla + ' menit/pesanan</strong>. Batasi menu masak lama (> 12 mnt) saat jam puncak keberangkatan pesawat.');
            }

            steps.push('<strong>Selamatkan Omset Hangus:</strong> Skenario buruk berisiko menghilangkan omset sebesar <strong>' + this.formatRupiah(this.badRevenueDelta) + '</strong>, termasuk potensi pembatalan langsung <strong>' + this.formatRupiah(this.badLostRevenue) + '</strong>. Sediakan menu grab-and-go instan.');

            steps.push('<strong>Antisipasi ' + this.badComplaints + ' Kasus Komplain:</strong> Pasang estimasi waktu tunggu transparan di kasir sebelum transaksi agar penumpang yang waktu boardingnya mepet tidak membatalkan di tengah proses masak.');

            steps.push('<strong>Kontrol Efisiensi Bahan Baku:</strong> Jika omset turun ke <strong>' + this.formatRupiah(this.badRevenue) + '</strong>, kurangi pemesanan bahan baku mudah basi harian agar tidak timbul pembengkakan biaya waste.');

            return steps;
        },

        formatRupiah(val) {
            return 'Rp ' + Number(val || 0).toLocaleString('id-ID');
        },

        copyPlanSummary() {
            const periodLabel = this.horizon === 'weekly' ? 'Minggu Depan' : 'Bulan Depan';
            const currentLabel = this.horizon === 'weekly' ? 'Minggu Ini' : 'Bulan Ini';

            const goodStepsText = this.goodActionSteps.map((s, i) => '  ' + (i + 1) + '. ' + s.replace(/<[^>]*>/g, '')).join('\n');
            const badStepsText = this.badMitigationSteps.map((s, i) => '  ' + (i + 1) + '. ' + s.replace(/<[^>]*>/g, '')).join('\n');

            const text = 'FlyDine Juanda - Rencana Target & Proyeksi Eksekutif (' + periodLabel + '):\n\n' +
                '📊 KONDISI ACUAN (' + currentLabel + '):\n' +
                '• Omset Saat Ini: ' + this.formatRupiah(this.inputRevenue) + '\n' +
                '• Estimasi Pengunjung: ' + Number(this.inputVisitors).toLocaleString('id-ID') + ' orang\n' +
                '• Rata-rata Waktu Saji: ' + Number(this.inputSlaMinutes).toFixed(1) + ' menit/pesanan\n' +
                '• Rata-rata Belanja (AOV): ' + this.formatRupiah(this.currentAov) + '/order\n\n' +
                '🟢 SKENARIO BAIK (TARGET PRIMA):\n' +
                '• Proyeksi Omset Baik: ' + this.formatRupiah(this.goodRevenue) + ' (+' + this.goodGrowthPct + '%)\n' +
                '• Target Pengunjung: ' + this.goodVisitors.toLocaleString('id-ID') + ' orang (+' + this.goodVisitorsDelta + ' orang)\n' +
                '• Wajib Waktu Saji Kilat: ' + this.goodSla + ' menit/pesanan (SOP Juanda maks 15m)\n' +
                '• Tingkat Batal: < 1.0% (Terkendali aman sebelum boarding)\n' +
                'Langkah Kerja Meraih Target:\n' + goodStepsText + '\n\n' +
                '🔴 SKENARIO BURUK (PERLU WASPADA):\n' +
                '• Risiko Omset Melorot: ' + this.formatRupiah(this.badRevenue) + ' (-' + this.badDropPct + '%)\n' +
                '• Trafik Terlayani: ' + this.badVisitors.toLocaleString('id-ID') + ' orang\n' +
                '• Waktu Saji Bottleneck: ' + this.badSla + ' menit/pesanan (MELEWATI BATAS 15 MENIT!)\n' +
                '• Tingkat Batal Membengkak: ' + this.badCancelRate + '% (Risiko omset hangus: ' + this.formatRupiah(this.badLostRevenue) + ')\n' +
                'Langkah Mitigasi Risiko:\n' + badStepsText;

            navigator.clipboard.writeText(text).then(() => {
                this.copied = true;
                setTimeout(() => this.copied = false, 2500);
            });
        }
    };
};
</script>
@endsection
