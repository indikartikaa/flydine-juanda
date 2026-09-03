@extends('layouts.admin')

@section('title', 'Dashboard Eksekutif')

@section('content')
<div class="space-y-6">
    <!-- Header & Filter -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <h2 class="text-lg font-extrabold text-slate-800">Sistem Informasi Eksekutif</h2>
            <p class="text-sm text-slate-500">Analisis Performa Operasional FlyDine</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('admin.executive-dashboard') }}" class="flex items-center gap-3" id="filterForm">
                <select name="days" class="rounded-xl border-slate-200 text-sm focus:ring-[#005ea2] focus:border-[#005ea2] w-40 truncate" onchange="document.getElementById('filterForm').submit()">
                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>7 Hari Terakhir</option>
                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>30 Hari Terakhir</option>
                    <option value="90" {{ $days == 90 ? 'selected' : '' }}>90 Hari Terakhir</option>
                </select>
                <select name="tenant_id" class="rounded-xl border-slate-200 text-sm focus:ring-[#005ea2] focus:border-[#005ea2] w-48 truncate" onchange="document.getElementById('filterForm').submit()">
                    <option value="">Semua Tenant</option>
                    @foreach($tenants as $t)
                        <option value="{{ $t->id }}" {{ $tenantId == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('admin.executive-dashboard.export', ['days' => $days, 'tenant_id' => $tenantId]) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:from-emerald-600 hover:to-emerald-700 active:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm shadow-emerald-500/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export PDF
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Volume</p>
                <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $volumePerDay->sum('total') }} <span class="text-sm font-medium text-slate-500">Order</span></p>
            </div>
            <div class="h-12 w-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
        </div>
        
        @php
            $totalOrders = $statusDistribution->sum('total');
            $canceled = $statusDistribution->where('status', 'dibatalkan')->first()->total ?? 0;
            $cancelRate = $totalOrders > 0 ? round(($canceled / $totalOrders) * 100, 1) : 0;
        @endphp
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Cancel Rate</p>
                <p class="text-2xl font-extrabold text-rose-600 mt-1">{{ $cancelRate }}%</p>
            </div>
            <div class="h-12 w-12 rounded-full bg-rose-50 flex items-center justify-center text-rose-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        @php
            $avgMinutes = $slaPerformance->avg('avg_minutes');
        @endphp
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rata-rata Waktu (SLA)</p>
                <p class="text-2xl font-extrabold {{ $avgMinutes > 15 ? 'text-rose-600' : 'text-emerald-600' }} mt-1">{{ round($avgMinutes, 1) }} <span class="text-sm font-medium text-slate-500">Menit</span></p>
            </div>
            <div class="h-12 w-12 rounded-full {{ $avgMinutes > 15 ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }} flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Open Complaints</p>
                <p class="text-2xl font-extrabold text-amber-500 mt-1">{{ $openComplaints }} <span class="text-sm font-medium text-slate-500">Kasus</span></p>
            </div>
            <div class="h-12 w-12 rounded-full bg-amber-50 flex items-center justify-center text-amber-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Volume Line Chart -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="text-sm font-bold text-slate-800 mb-4 uppercase tracking-wide">Tren Volume Pesanan</h3>
            <div class="relative h-72">
                <canvas id="volumeChart"></canvas>
            </div>
        </div>

        <!-- SLA Bar Chart -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="text-xs font-bold text-slate-500 mb-6 uppercase tracking-wider">Top 20 Tenant Terlambat (SLA)</h3>
            <div class="relative h-72">
                <canvas id="slaChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Status Doughnut -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="text-sm font-bold text-slate-800 mb-4 uppercase tracking-wide">Distribusi Status Pesanan</h3>
            <div class="relative h-64">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Tenant Performance Bar -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 lg:col-span-2">
            <h3 class="text-xs font-bold text-slate-500 mb-6 uppercase tracking-wider">Top 20 Tenant Terlaris (Volume)</h3>
            <p class="text-xs text-slate-500 mb-4">Klik bar untuk memfilter data (Drill-down)</p>
            <div class="relative h-64">
                <canvas id="tenantChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Top Products -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <h3 class="text-sm font-bold text-slate-800 mb-4 uppercase tracking-wide">10 Produk Terlaris</h3>
        <div class="relative h-72">
            <canvas id="productsChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Shared Styling Options
    Chart.defaults.font.family = "'Plus Jakarta Sans', 'Poppins', sans-serif";
    Chart.defaults.color = '#64748b';
    
    // 1. Volume Chart (Line)
    const volumeCtx = document.getElementById('volumeChart').getContext('2d');
    const volumeData = @json($volumePerDay);
    new Chart(volumeCtx, {
        type: 'line',
        data: {
            labels: volumeData.map(item => item.date),
            datasets: [{
                label: 'Jumlah Pesanan',
                data: volumeData.map(item => item.total),
                borderColor: '#005ea2',
                backgroundColor: 'rgba(0, 94, 162, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#005ea2',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [4, 4] } },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. SLA Chart (Bar)
    const slaCtx = document.getElementById('slaChart').getContext('2d');
    const slaData = @json($slaPerformance);
    const slaChart = new Chart(slaCtx, {
        type: 'bar',
        data: {
            labels: slaData.map(item => item.name),
            datasets: [{
                label: 'Rata-rata Menit',
                data: slaData.map(item => item.avg_minutes),
                backgroundColor: slaData.map(item => item.avg_minutes > 15 ? 'rgba(225, 29, 72, 0.8)' : 'rgba(16, 185, 129, 0.8)'), // Rose for >15m, Emerald otherwise
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        afterLabel: function(context) {
                            return context.raw > 15 ? '⚠️ Melewati SLA (15m)' : '✅ Sesuai SLA';
                        }
                    }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    title: { display: true, text: 'Menit' }
                }
            },
            onClick: (e) => {
                const canvasPosition = Chart.helpers.getRelativePosition(e, slaChart);
                const dataX = slaChart.scales.x.getValueForPixel(canvasPosition.x);
                if (dataX !== undefined && slaData[dataX]) {
                    const selectEl = document.querySelector('select[name="tenant_id"]');
                    if(selectEl) {
                        selectEl.value = slaData[dataX].id;
                        document.getElementById('filterForm').submit();
                    }
                }
            }
        }
    });

    // 3. Status Chart (Doughnut)
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusDataRaw = @json($statusDistribution);
    
    // Map status string to specific colors
    const statusColors = {
        'menunggu': '#cbd5e1', // Slate 300
        'diproses': '#3b82f6', // Blue 500
        'siap': '#f59e0b',     // Amber 500
        'selesai': '#10b981',  // Emerald 500
        'dibatalkan': '#ef4444'// Red 500
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
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
            }
        }
    });

    // 4. Tenant Performance Chart (Bar) with Drill-down
    const tenantCtx = document.getElementById('tenantChart').getContext('2d');
    const tenantDataRaw = @json($tenantPerformance);
    const tenantChart = new Chart(tenantCtx, {
        type: 'bar',
        data: {
            labels: tenantDataRaw.map(item => item.name),
            datasets: [{
                label: 'Total Pesanan',
                data: tenantDataRaw.map(item => item.total),
                backgroundColor: 'rgba(59, 130, 246, 0.8)', // Blue 500
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            },
            onClick: (e) => {
                const canvasPosition = Chart.helpers.getRelativePosition(e, tenantChart);
                const dataX = tenantChart.scales.x.getValueForPixel(canvasPosition.x);
                if (dataX !== undefined && tenantDataRaw[dataX]) {
                    const selectEl = document.querySelector('select[name="tenant_id"]');
                    if(selectEl) {
                        selectEl.value = tenantDataRaw[dataX].id;
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
                label: 'Terjual',
                data: productsData.map(item => item.total_qty),
                backgroundColor: 'rgba(139, 92, 246, 0.8)', // Violet 500
                borderRadius: 4
            }]
        },
        options: {
            indexAxis: 'y', // Makes it horizontal
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: { beginAtZero: true }
            }
        }
    });
});
</script>
@endsection
