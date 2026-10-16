@extends('layouts.admin')

@section('title', 'Simulasi What-If')

@section('content')
<script nonce="{{ $cspNonce ?? '' }}">
window.capacityPlannerApp = function(initialPayload) {
    return {
        startDate: '{{ $startDateFormatted }}',
        endDate: '{{ $endDateFormatted }}',
        tenantId: '{{ $tenantId ?: "" }}',
        orderGrowthPct: 0,
        staffCount: 1,
        targetSlaMinutes: '',

        // Data & Loading States
        loading: false,
        errorMessage: '',
        data: initialPayload || {
            period: { start_date: '', end_date: '', days: 30 },
            inputs: { tenant_id: null, order_growth_pct: 0, staff_count: 1, target_sla_minutes: null },
            summary: {
                selected_tenant: null,
                count_total_tenants: 0,
                count_sufficient: 0,
                avg_utilization_before: 0,
                avg_utilization_after: 0,
                total_capacity_new: 0,
                status_distribution: { AMAN: 0, WASPADA: 0, KRITIS: 0, OVERLOAD: 0, INSUFFICIENT: 0 },
                overall_status: 'AMAN',
                min_staff_recommended: 1
            },
            top_10_chart: { labels: [], tenant_ids: [], utilization_before: [], utilization_after: [], thresholds: { safe: 70, warning: 90 } },
            tenants: []
        },

        debounceTimeout: null,

        init() {
            this.$nextTick(() => {
                this.renderChart();
            });
        },

        calculateDebounced() {
            clearTimeout(this.debounceTimeout);
            this.debounceTimeout = setTimeout(() => {
                this.calculateNow();
            }, 300);
        },

        calculateNow() {
            this.loading = true;
            this.errorMessage = '';

            const payload = {
                start_date: this.startDate,
                end_date: this.endDate,
                tenant_id: this.tenantId && this.tenantId !== 'semua' ? this.tenantId : null,
                order_growth_pct: parseFloat(this.orderGrowthPct) || 0,
                staff_count: parseInt(this.staffCount, 10) || 1,
                target_sla_minutes: this.targetSlaMinutes ? parseFloat(this.targetSlaMinutes) : null
            };

            fetch('{{ route("admin.analytics.whatif.calculate", [], false) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            })
            .then(res => {
                if (!res.ok) throw new Error('HTTP Error ' + res.status);
                return res.json();
            })
            .then(json => {
                this.data = json;
                this.renderChart();
            })
            .catch(err => {
                console.error('Calculation error:', err);
                this.errorMessage = 'Gagal memproses perhitungan simulasi. Silakan coba kembali.';
            })
            .finally(() => {
                this.loading = false;
            });
        },

        adjustStaff(delta) {
            const next = this.staffCount + delta;
            if (next >= 1 && next <= 10) {
                this.staffCount = next;
                this.calculateDebounced();
            }
        },

        setGrowthPreset(pct) {
            this.orderGrowthPct = pct;
            this.calculateDebounced();
        },

        focusTenant(id) {
            this.tenantId = id ? String(id) : '';
            this.calculateNow();
            const el = document.getElementById('capacityControlPanel');
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        },

        resetSimulation() {
            this.orderGrowthPct = 0;
            this.staffCount = 1;
            this.targetSlaMinutes = '';
            this.tenantId = '';
            this.calculateNow();
        },

        renderChart() {
            const canvas = document.getElementById('capacityTop10Chart');
            if (!canvas || typeof window.Chart === 'undefined') return;

            const existingChart = window.Chart.getChart(canvas);
            if (existingChart) {
                existingChart.destroy();
            }

            const chartData = this.data.top_10_chart || { labels: [], utilization_before: [], utilization_after: [] };
            if (!chartData.labels || chartData.labels.length === 0) return;

            // Custom plugin untuk garis acuan 70% dan 90%
            const thresholdLinePlugin = {
                id: 'thresholdLines',
                afterDraw(chart) {
                    const ctx = chart.ctx;
                    if (!ctx || !chart.chartArea || !chart.scales?.y) return;
                    const { left, right, top, bottom } = chart.chartArea;
                    const y = chart.scales.y;

                    // Garis Acuan 70% (Kuning Waspada)
                    const y70 = y.getPixelForValue(70);
                    if (y70 >= top && y70 <= bottom) {
                        ctx.save();
                        ctx.strokeStyle = '#eab308';
                        ctx.lineWidth = 1.5;
                        ctx.setLineDash([5, 5]);
                        ctx.beginPath();
                        ctx.moveTo(left, y70);
                        ctx.lineTo(right, y70);
                        ctx.stroke();
                        ctx.fillStyle = '#b45309';
                        ctx.font = 'bold 10px sans-serif';
                        ctx.fillText('Batas Waspada (70%)', right - 110, y70 - 4);
                        ctx.restore();
                    }

                    // Garis Acuan 90% (Merah Kritis)
                    const y90 = y.getPixelForValue(90);
                    if (y90 >= top && y90 <= bottom) {
                        ctx.save();
                        ctx.strokeStyle = '#ef4444';
                        ctx.lineWidth = 1.5;
                        ctx.setLineDash([5, 5]);
                        ctx.beginPath();
                        ctx.moveTo(left, y90);
                        ctx.lineTo(right, y90);
                        ctx.stroke();
                        ctx.fillStyle = '#b91c1c';
                        ctx.font = 'bold 10px sans-serif';
                        ctx.fillText('Batas Kritis (90%)', right - 100, y90 - 4);
                        ctx.restore();
                    }
                }
            };

            const colorsAfter = chartData.utilization_after.map(val => {
                if (val < 70) return '#10b981'; // Emerald
                if (val <= 90) return '#f59e0b'; // Amber
                if (val <= 100) return '#f43f5e'; // Rose
                return '#991b1b'; // Dark Red Overload
            });

            new window.Chart(canvas, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Sebelum (Baseline)',
                            data: chartData.utilization_before,
                            backgroundColor: '#005ea2',
                            borderRadius: 4,
                            barPercentage: 0.8,
                            categoryPercentage: 0.8,
                        },
                        {
                            label: 'Sesudah (Proyeksi What-If)',
                            data: chartData.utilization_after,
                            backgroundColor: colorsAfter,
                            borderRadius: 4,
                            barPercentage: 0.8,
                            categoryPercentage: 0.8,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: { weight: 'bold', size: 11 }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: {
                                label: (ctx) => `${ctx.dataset.label}: ${ctx.raw}%`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: 110,
                            ticks: {
                                callback: (v) => v + '%',
                                font: { size: 11, weight: 'bold' }
                            },
                            grid: { color: '#f1f5f9' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 10, weight: 'bold' },
                                maxRotation: 45,
                                minRotation: 0
                            }
                        }
                    }
                },
                plugins: [thresholdLinePlugin]
            });
        },

        getStatusBadgeClass(status) {
            switch(status) {
                case 'AMAN':
                    return 'bg-emerald-100 text-emerald-800 border-emerald-300';
                case 'WASPADA':
                    return 'bg-amber-100 text-amber-800 border-amber-300';
                case 'KRITIS':
                    return 'bg-rose-100 text-rose-800 border-rose-300';
                case 'OVERLOAD':
                    return 'bg-red-700 text-white border-red-800 animate-pulse';
                default:
                    return 'bg-slate-100 text-slate-600 border-slate-300';
            }
        }
    };
};

window.targetPlanningSimulator = function() {
    return {
        horizon: 'monthly',
        showGuide: false,
        copied: false,

        baseWeeklyRevenue: {{ $weeklyTotalRevenue ?? 25000000 }},
        baseWeeklyVisitors: {{ $weeklyVisitors ?? 800 }},
        baseWeeklySla: {{ $weeklyAvgSla ?? 11.5 }},

        baseMonthlyRevenue: {{ $monthlyTotalRevenue ?? 110000000 }},
        baseMonthlyVisitors: {{ $monthlyVisitors ?? 3500 }},
        baseMonthlySla: {{ $monthlyAvgSla ?? 11.5 }},

        inputRevenue: {{ $monthlyTotalRevenue ?? 110000000 }},
        inputVisitors: {{ $monthlyVisitors ?? 3500 }},
        inputSlaMinutes: {{ $monthlyAvgSla ?? 11.5 }},

        goodGrowthPct: 15,
        badDropPct: 10,

        setHorizon(h) {
            this.horizon = h;
            if (h === 'weekly') {
                this.inputRevenue = this.baseWeeklyRevenue;
                this.inputVisitors = this.baseWeeklyVisitors;
                this.inputSlaMinutes = this.baseWeeklySla;
            } else {
                this.inputRevenue = this.baseMonthlyRevenue;
                this.inputVisitors = this.baseMonthlyVisitors;
                this.inputSlaMinutes = this.baseMonthlySla;
            }
        },

        get currentAov() {
            const rev = Number(this.inputRevenue) || 0;
            const vis = Number(this.inputVisitors) || 1;
            const orders = Math.max(1, Math.round(vis * 0.45));
            return Math.round(rev / orders);
        },

        get goodRevenue() {
            const base = Number(this.inputRevenue) || 0;
            return Math.round(base * (1 + (Number(this.goodGrowthPct) / 100)));
        },
        get goodRevenueDelta() {
            return this.goodRevenue - (Number(this.inputRevenue) || 0);
        },
        get goodVisitors() {
            const base = Number(this.inputVisitors) || 0;
            return Math.round(base * (1 + (Number(this.goodGrowthPct) * 0.8 / 100)));
        },
        get goodVisitorsDelta() {
            return this.goodVisitors - (Number(this.inputVisitors) || 0);
        },
        get goodEstimatedOrders() {
            return Math.round(this.goodVisitors * 0.48);
        },
        get goodSla() {
            const fast = Math.max(7.5, Number(this.inputSlaMinutes) - 1.5);
            return Math.min(15.0, +fast.toFixed(1));
        },
        get goodCancelRate() {
            return 0.8;
        },
        get goodCompliance() {
            return 98.5;
        },

        get goodActionSteps() {
            const steps = [];
            const addRev = this.formatRupiah(this.goodRevenueDelta);
            const addPax = this.goodVisitorsDelta;

            steps.push('<strong>Optimalisasi Antrean Kilat:</strong> Pertahankan waktu saji maksimal <strong>' + this.goodSla + ' menit/pesanan</strong> untuk mengonversi tambahan <strong>' + addPax.toLocaleString('id-ID') + ' pengunjung</strong> menjadi pembeli aktif.');
            steps.push('<strong>Dorong Upselling & Menu Bundling:</strong> Naikkan rata-rata belanja (AOV) sebesar 10-15% lewat paket kombo sarapan/kopi sebelum boarding guna mengamankan target omset tambahan <strong>' + addRev + '</strong>.');
            steps.push('<strong>Pertahankan Kepuasan SOP Prima:</strong> Capai tingkat kepatuhan waktu saji minimal <strong>' + this.goodCompliance + '%</strong> agar tingkat pembatalan pesanan ditekan di bawah <strong>1.0%</strong>.');

            return steps;
        },

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

<div class="space-y-8 pb-12">

    <!-- Page Header & Identity -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <!-- Breadcrumb -->
                <nav class="flex items-center gap-2 text-xs font-bold text-slate-400 mb-2">
                    <span>Analitik Operasional</span>
                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-[#005ea2] font-extrabold">Simulasi What-If</span>
                </nav>

                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    Simulasi What-If: Kapasitas Tenant & Skenario Omset
                </h1>
                <p class="text-sm font-semibold text-slate-600 mt-1">
                    Eksplorasi skenario operasional multi-dimensi: estimasi beban puncak dan kebutuhan staf gerai F&B serta proyeksi target omset bandara.
                </p>

                <div class="mt-2.5 inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-xs font-bold text-slate-800">
                    <svg class="w-4 h-4 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Periode Dasar Historis: <strong class="text-[#005ea2]">{{ \Carbon\Carbon::parse($startDateFormatted)->format('d M Y') }}</strong> s/d <strong class="text-[#005ea2]">{{ \Carbon\Carbon::parse($endDateFormatted)->format('d M Y') }}</strong> ({{ (int)$days }} Hari)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar Component -->
    <x-analytics.filter-bar 
        :action="route('admin.analytics.whatif')" 
        :startDateFormatted="$startDateFormatted" 
        :endDateFormatted="$endDateFormatted" 
        :tenantId="$tenantId" 
        :tenants="$tenants" 
    />

    <!-- ========================================================================= -->
    <!-- BAGIAN 1: KAPASITAS TENANT (BARU)                                        -->
    <!-- ========================================================================= -->
    <div id="capacitySimulationSection" 
         x-data="window.capacityPlannerApp(@js($initialCapacity))" 
         x-init="init()" 
         class="space-y-6">

        <!-- Header Seksi Bagian 1 -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-r from-blue-900 to-[#005ea2] text-white p-6 rounded-2xl shadow-sm">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-xs font-black uppercase tracking-wider bg-white/10 text-blue-100 border border-white/20 mb-2">
                    <span>⚡ BAGIAN I • KAPASITAS & BOTTLENECK TENANT</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black tracking-tight text-white">
                    Perencanaan Kapasitas & Utilisasi Beban Puncak Gerai
                </h2>
                <p class="text-xs sm:text-sm text-blue-100 font-medium mt-1 max-w-3xl">
                    Uji ketahanan gerai saat lonjakan jam sibuk bandara. Hitung utilisasi (&lambda; / &mu;), proyeksi antrean, dan rekomendasi penambahan staf minimum agar utilisasi tetap aman (&le; 80%).
                </p>
            </div>
            <div class="flex items-center gap-2">
                <template x-if="loading">
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/20 text-white text-xs font-bold animate-pulse">
                        <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span>Menghitung...</span>
                    </span>
                </template>
            </div>
        </div>

        <!-- Grid: Panel Kontrol Kiri & Hasil Kanan -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- PANEL KONTROL KIRI (col-span-4) -->
            <div id="capacityControlPanel" class="lg:col-span-4 bg-white rounded-2xl p-6 shadow-sm border-2 border-slate-200 space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                        <span>🎛️ Panel Kontrol Simulasi</span>
                    </h3>
                    <button type="button" 
                            @click="resetSimulation()" 
                            class="text-xs font-extrabold text-rose-600 hover:text-rose-800 hover:bg-rose-50 px-2.5 py-1 rounded-lg transition cursor-pointer">
                        Reset
                    </button>
                </div>

                <!-- 1. Pemilih Tenant -->
                <div>
                    <label for="controlTenantSelect" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                        Fokus Tenant
                    </label>
                    <select id="controlTenantSelect" 
                            x-model="tenantId" 
                            @change="calculateNow()" 
                            class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white border-2 border-slate-300 focus:border-[#005ea2] focus:bg-white rounded-xl text-sm font-extrabold text-slate-900 focus:ring-2 focus:ring-[#005ea2] transition cursor-pointer">
                        <option value="">🏢 Semua Tenant (Konsolidasi)</option>
                        @foreach($tenants as $t)
                            <option value="{{ $t->id }}">🏢 {{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 2. Slider Kenaikan Pesanan (-50% sampai +200%) -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="orderGrowthSlider" class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                            Kenaikan Pesanan (&Delta; &lambda;)
                        </label>
                        <span class="text-xs font-black px-2.5 py-0.5 rounded-md" 
                              :class="orderGrowthPct > 0 ? 'bg-emerald-100 text-emerald-800' : (orderGrowthPct < 0 ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-700')"
                              x-text="(orderGrowthPct > 0 ? '+' : '') + orderGrowthPct + '%'">
                        </span>
                    </div>
                    <input type="range" 
                           id="orderGrowthSlider" 
                           min="-50" 
                           max="200" 
                           step="5" 
                           x-model.number="orderGrowthPct" 
                           @input="calculateDebounced()" 
                           class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-[#005ea2]">
                    
                    <div class="flex justify-between text-[10px] font-bold text-slate-400 mt-1">
                        <span>-50%</span>
                        <span>0% (Aktual)</span>
                        <span>+100%</span>
                        <span>+200%</span>
                    </div>

                    <!-- Preset Chips -->
                    <div class="flex items-center flex-wrap gap-1.5 mt-2.5">
                        <span class="text-[10px] font-bold text-slate-400">Preset:</span>
                        <button type="button" @click="setGrowthPreset(-20)" class="px-2 py-0.5 text-[11px] font-extrabold rounded bg-slate-100 hover:bg-slate-200 text-slate-700">-20%</button>
                        <button type="button" @click="setGrowthPreset(0)" class="px-2 py-0.5 text-[11px] font-extrabold rounded bg-slate-100 hover:bg-slate-200 text-slate-700">0%</button>
                        <button type="button" @click="setGrowthPreset(25)" class="px-2 py-0.5 text-[11px] font-extrabold rounded bg-slate-100 hover:bg-slate-200 text-slate-700">+25%</button>
                        <button type="button" @click="setGrowthPreset(50)" class="px-2 py-0.5 text-[11px] font-extrabold rounded bg-slate-100 hover:bg-slate-200 text-slate-700">+50%</button>
                        <button type="button" @click="setGrowthPreset(100)" class="px-2 py-0.5 text-[11px] font-extrabold rounded bg-slate-100 hover:bg-slate-200 text-slate-700">+100%</button>
                    </div>
                </div>

                <!-- 3. Stepper Staf (1 - 10) -->
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                        Jumlah Staf Kasir / Dapur (1 - 10)
                    </label>
                    <div class="flex items-center gap-2">
                        <button type="button" 
                                @click="adjustStaff(-1)" 
                                :disabled="staffCount <= 1"
                                class="w-11 h-11 flex items-center justify-center rounded-xl bg-slate-100 hover:bg-slate-200 disabled:opacity-30 disabled:cursor-not-allowed font-black text-lg text-slate-700 border border-slate-300 transition">
                            -
                        </button>
                        <input type="number" 
                               min="1" 
                               max="10" 
                               x-model.number="staffCount" 
                               @input="calculateDebounced()" 
                               class="flex-1 text-center py-2.5 bg-slate-50 hover:bg-white border-2 border-slate-300 focus:border-[#005ea2] focus:bg-white rounded-xl text-base font-black text-slate-900">
                        <button type="button" 
                                @click="adjustStaff(1)" 
                                :disabled="staffCount >= 10"
                                class="w-11 h-11 flex items-center justify-center rounded-xl bg-slate-100 hover:bg-slate-200 disabled:opacity-30 disabled:cursor-not-allowed font-black text-lg text-slate-700 border border-slate-300 transition">
                            +
                        </button>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-400 mt-1 block">Default: 1 staf per gerai</span>
                </div>

                <!-- 4. Input Target Waktu Saji (menit) -->
                <div>
                    <label for="targetSlaInput" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                        Target Waktu Saji (Menit)
                    </label>
                    <div class="relative">
                        <input type="number" 
                               id="targetSlaInput" 
                               step="0.5" 
                               min="1" 
                               max="60" 
                               placeholder="Gunakan Waktu Saji Historis (S₀)" 
                               x-model="targetSlaMinutes" 
                               @input="calculateDebounced()" 
                               class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-white border-2 border-slate-300 focus:border-[#005ea2] focus:bg-white rounded-xl text-sm font-extrabold text-slate-900 placeholder:text-slate-400 placeholder:font-normal">
                    </div>
                    <span class="text-[11px] font-semibold text-slate-400 mt-1 block">
                        Kosongkan untuk otomatis menggunakan waktu saji historis masing-masing tenant (S₀).
                    </span>
                </div>

                <template x-if="errorMessage">
                    <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs font-bold text-rose-700" x-text="errorMessage"></div>
                </template>
            </div>

            <!-- HASIL KANAN (col-span-8) -->
            <div class="lg:col-span-8 space-y-6">

                <!-- 4 Kartu Metrik Ringkasan -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                    
                    <!-- Kartu 1: Utilisasi Sebelum vs Sesudah -->
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 relative overflow-hidden">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Utilisasi Puncak</span>
                        <div class="mt-1 flex items-baseline gap-2">
                            <span class="text-3xl font-black text-slate-900" x-text="(data.summary.selected_tenant ? data.summary.selected_tenant.utilization_new : data.summary.avg_utilization_after) + '%'"></span>
                            <span class="text-xs font-bold text-slate-400">
                                (Awal: <span x-text="(data.summary.selected_tenant ? data.summary.selected_tenant.utilization_before : data.summary.avg_utilization_before) + '%'"></span>)
                            </span>
                        </div>
                        <div class="w-full bg-slate-100 h-1.5 rounded-full mt-3 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-300"
                                 :class="(data.summary.selected_tenant ? data.summary.selected_tenant.utilization_new : data.summary.avg_utilization_after) < 70 ? 'bg-emerald-500' : ((data.summary.selected_tenant ? data.summary.selected_tenant.utilization_new : data.summary.avg_utilization_after) <= 90 ? 'bg-amber-500' : 'bg-rose-500')"
                                 :style="'width: ' + Math.min(100, (data.summary.selected_tenant ? data.summary.selected_tenant.utilization_new : data.summary.avg_utilization_after)) + '%;'">
                            </div>
                        </div>
                        <span class="text-[11px] font-semibold text-slate-500 mt-2 block">
                            Beban / Kapasitas (&lambda; / &mu;)
                        </span>
                    </div>

                    <!-- Kartu 2: Kapasitas per Jam Baru -->
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 relative overflow-hidden">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Kapasitas Baru (&mu;)</span>
                        <div class="mt-1 flex items-baseline gap-1">
                            <span class="text-3xl font-black text-blue-700" 
                                  x-text="data.summary.selected_tenant ? data.summary.selected_tenant.capacity_new : data.summary.total_capacity_new"></span>
                            <span class="text-xs font-bold text-slate-500">order/jam</span>
                        </div>
                        <span class="text-[11px] font-semibold text-slate-500 mt-3 block"
                              x-text="'Sisa: ' + (data.summary.selected_tenant ? data.summary.selected_tenant.capacity_remaining : 'Konsolidasi') + (data.summary.selected_tenant ? ' order/jam' : '')">
                        </span>
                    </div>

                    <!-- Kartu 3: Status Kapasitas -->
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 relative overflow-hidden">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Status Ketahanan</span>
                        <div class="mt-2">
                            <span class="inline-block px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wider border shadow-2xs"
                                  :class="getStatusBadgeClass(data.summary.selected_tenant ? data.summary.selected_tenant.status : data.summary.overall_status)"
                                  x-text="data.summary.selected_tenant ? data.summary.selected_tenant.status : data.summary.overall_status">
                            </span>
                        </div>
                        <span class="text-[11px] font-semibold text-slate-500 mt-3 block">
                            <span x-show="(data.summary.selected_tenant ? data.summary.selected_tenant.utilization_new : data.summary.avg_utilization_after) < 70">🟢 Kondisi Prima (&lt; 70%)</span>
                            <span x-show="(data.summary.selected_tenant ? data.summary.selected_tenant.utilization_new : data.summary.avg_utilization_after) >= 70 && (data.summary.selected_tenant ? data.summary.selected_tenant.utilization_new : data.summary.avg_utilization_after) <= 90">🟡 Perlu Waspada (70-90%)</span>
                            <span x-show="(data.summary.selected_tenant ? data.summary.selected_tenant.utilization_new : data.summary.avg_utilization_after) > 90 && (data.summary.selected_tenant ? data.summary.selected_tenant.utilization_new : data.summary.avg_utilization_after) <= 100">🟠 Risiko Kritis (90-100%)</span>
                            <span x-show="(data.summary.selected_tenant ? data.summary.selected_tenant.utilization_new : data.summary.avg_utilization_after) > 100">🔴 Overload Kapasitas (&gt; 100%)</span>
                        </span>
                    </div>

                    <!-- Kartu 4: Rekomendasi Staf Minimum -->
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 relative overflow-hidden">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Staf Minimum (&le; 80%)</span>
                        <div class="mt-1 flex items-baseline gap-1">
                            <span class="text-3xl font-black text-purple-700" 
                                  x-text="(data.summary.min_staff_recommended || 1) + ' Staf'"></span>
                        </div>
                        <span class="text-[11px] font-semibold text-slate-500 mt-3 block">
                            Dibutuhkan agar utilisasi puncak &le; 80%
                        </span>
                    </div>

                </div>

                <!-- Grafik Chart.js Batang Berpasangan: Sebelum vs Sesudah -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                        <div>
                            <h4 class="text-sm font-black text-slate-900 tracking-tight flex items-center gap-2">
                                <span>📊 Top 10 Tenant Utilisasi Tertinggi (Sebelum vs Sesudah)</span>
                            </h4>
                            <p class="text-xs font-semibold text-slate-500 mt-0.5">
                                Perbandingan utilisasi sebelum (biru) vs setelah simulasi what-if dengan garis acuan batas waspada (70%) dan kritis (90%).
                            </p>
                        </div>
                        <div class="flex items-center gap-3 text-xs font-bold">
                            <span class="flex items-center gap-1.5 text-amber-700">
                                <span class="w-3 h-0.5 border-t-2 border-dashed border-amber-500"></span>
                                <span>70% Waspada</span>
                            </span>
                            <span class="flex items-center gap-1.5 text-rose-700">
                                <span class="w-3 h-0.5 border-t-2 border-dashed border-rose-500"></span>
                                <span>90% Kritis</span>
                            </span>
                        </div>
                    </div>

                    <div class="relative h-72 w-full">
                        <canvas id="capacityTop10Chart"></canvas>
                    </div>
                </div>

                <!-- Teks Asumsi Simulasi -->
                <div class="bg-blue-50/60 border border-blue-200 rounded-2xl p-4 text-xs font-semibold text-blue-900 flex items-start gap-3">
                    <span class="text-lg shrink-0">ℹ️</span>
                    <div>
                        <strong class="font-black text-blue-950 block mb-0.5">Asumsi Operasional Simulasi:</strong>
                        <ul class="list-disc list-inside space-y-0.5 text-blue-800">
                            <li>Satu staf menangani satu pesanan pada satu waktu di gerai tenant.</li>
                            <li>Beban puncak (&lambda;₀) dihitung dari data historis pesanan non-dibatalkan pada jam tersibuk gerai dibagi jumlah hari dalam periode terpilih.</li>
                            <li>Waktu saji awal (S₀) merupakan rata-rata selisih pesanan masuk ke status siap saji (SLA) yang sah.</li>
                        </ul>
                    </div>
                </div>

            </div>

        </div>

        <!-- TABEL SEMUA TENANT (Diurutkan dari Utilisasi Tertinggi) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-black text-slate-900 tracking-tight">
                        📋 Matriks Kapasitas Seluruh Mitra Gerai Tenant
                    </h3>
                    <p class="text-xs font-semibold text-slate-500 mt-0.5">
                        Daftar seluruh tenant diurutkan dari utilisasi tertinggi berdasarkan parameter what-if aktif. Klik baris untuk memfokuskan tenant.
                    </p>
                </div>
                <div class="text-xs font-bold text-slate-500">
                    Total: <strong class="text-slate-900" x-text="data.tenants ? data.tenants.length : 0"></strong> Tenant
                </div>
            </div>

            <!-- Mobile scroll hint -->
            <div class="sm:hidden flex items-center justify-between gap-1 text-[11px] font-bold text-slate-500 mb-2 px-6 pt-3">
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                    <span>Geser ke kanan untuk rincian utilisasi</span>
                </span>
                <span class="text-slate-400">10 Kolom</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-extrabold uppercase tracking-wider">
                            <th class="py-3 px-4">Mitra Gerai Tenant</th>
                            <th class="py-3 px-3">Lokasi / Zona</th>
                            <th class="py-3 px-3 text-right">Beban Puncak (&lambda;₀)</th>
                            <th class="py-3 px-3 text-right">Waktu Saji (S₀)</th>
                            <th class="py-3 px-3 text-right">Kapasitas Baru (&mu;)</th>
                            <th class="py-3 px-3 text-right">Utilisasi Awal</th>
                            <th class="py-3 px-3 text-right">Utilisasi Baru</th>
                            <th class="py-3 px-3 text-center">Status</th>
                            <th class="py-3 px-3 text-center">Staf Min. (&le;80%)</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                        <template x-for="t in data.tenants" :key="t.tenant_id">
                            <tr class="hover:bg-blue-50/50 transition cursor-pointer group"
                                :class="tenantId == t.tenant_id ? 'bg-blue-50/80 font-bold' : ''"
                                @click="focusTenant(t.tenant_id)">
                                
                                <!-- 1. Nama Tenant -->
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full" 
                                              :class="t.has_sufficient_data ? (t.utilization_new < 70 ? 'bg-emerald-500' : (t.utilization_new <= 90 ? 'bg-amber-500' : 'bg-rose-500')) : 'bg-slate-300'"></span>
                                        <div>
                                            <span class="font-extrabold text-slate-900 group-hover:text-[#005ea2]" x-text="t.tenant_name"></span>
                                            <span class="block text-[10px] text-slate-400" x-text="t.category"></span>
                                        </div>
                                    </div>
                                </td>

                                <!-- 2. Lokasi -->
                                <td class="py-3 px-3 text-slate-500" x-text="t.floor_location"></td>

                                <!-- 3. Lambda 0 -->
                                <td class="py-3 px-3 text-right">
                                    <template x-if="t.has_sufficient_data">
                                        <span>
                                            <span class="font-black text-slate-900" x-text="t.lambda_0"></span>
                                            <span class="text-[10px] text-slate-400 block" x-text="t.peak_hour_formatted"></span>
                                        </span>
                                    </template>
                                    <template x-if="!t.has_sufficient_data">
                                        <span class="text-slate-400 italic">-</span>
                                    </template>
                                </td>

                                <!-- 4. Waktu Saji S0 -->
                                <td class="py-3 px-3 text-right">
                                    <template x-if="t.has_sufficient_data">
                                        <span class="font-bold text-slate-800" x-text="t.service_minutes_0 + ' mnt'"></span>
                                    </template>
                                    <template x-if="!t.has_sufficient_data">
                                        <span class="text-slate-400 italic">-</span>
                                    </template>
                                </td>

                                <!-- 5. Kapasitas Baru Mu -->
                                <td class="py-3 px-3 text-right">
                                    <template x-if="t.has_sufficient_data">
                                        <span class="font-black text-blue-700" x-text="t.capacity_new + '/jam'"></span>
                                    </template>
                                    <template x-if="!t.has_sufficient_data">
                                        <span class="text-slate-400 italic">-</span>
                                    </template>
                                </td>

                                <!-- 6. Utilisasi Awal -->
                                <td class="py-3 px-3 text-right">
                                    <template x-if="t.has_sufficient_data">
                                        <span class="font-bold text-slate-600" x-text="t.utilization_before + '%'"></span>
                                    </template>
                                    <template x-if="!t.has_sufficient_data">
                                        <span class="text-slate-400 italic">-</span>
                                    </template>
                                </td>

                                <!-- 7. Utilisasi Baru -->
                                <td class="py-3 px-3 text-right">
                                    <template x-if="t.has_sufficient_data">
                                        <span class="text-sm font-black" 
                                              :class="t.utilization_new < 70 ? 'text-emerald-700' : (t.utilization_new <= 90 ? 'text-amber-700' : 'text-rose-700')"
                                              x-text="t.utilization_new + '%'"></span>
                                    </template>
                                    <template x-if="!t.has_sufficient_data">
                                        <span class="text-slate-400 italic">-</span>
                                    </template>
                                </td>

                                <!-- 8. Status Badge -->
                                <td class="py-3 px-3 text-center">
                                    <span class="inline-block px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider border shadow-2xs"
                                          :class="getStatusBadgeClass(t.status)"
                                          x-text="t.status">
                                    </span>
                                </td>

                                <!-- 9. Staf Min Needed -->
                                <td class="py-3 px-3 text-center">
                                    <template x-if="t.has_sufficient_data">
                                        <span class="px-2 py-0.5 rounded bg-purple-50 text-purple-700 font-extrabold text-xs" 
                                              x-text="t.min_staff_needed + ' Staf'"></span>
                                    </template>
                                    <template x-if="!t.has_sufficient_data">
                                        <span class="text-slate-400 italic">-</span>
                                    </template>
                                </td>

                                <!-- 10. Aksi Link ke Drilldown -->
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a :href="'{{ route('admin.analytics.drilldown') }}?tenant_id=' + t.tenant_id + '&start_date=' + startDate + '&end_date=' + endDate" 
                                           @click.stop 
                                           class="inline-flex items-center gap-1 text-[11px] font-black text-[#005ea2] hover:text-blue-800 hover:underline"
                                           title="Buka rincian data transaksi tenant ini di halaman Drill-Down">
                                            <span>Rincian Pesanan &rarr;</span>
                                        </a>
                                    </div>
                                </td>

                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- DIVIDER SEKSI -->
    <div class="relative py-4">
        <div class="absolute inset-0 flex items-center" aria-hidden="true">
            <div class="w-full border-t-2 border-slate-200"></div>
        </div>
        <div class="relative flex justify-center">
            <span class="bg-[#f8fafc] px-4 text-xs font-black uppercase tracking-widest text-slate-400">
                Pemisah Modul Simulasi
            </span>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- BAGIAN 2: SKENARIO OMSET (KALKULATOR LAMA DARI LANGKAH 2)                 -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-2xl p-6 sm:p-7 shadow-sm border-2 border-indigo-200" 
         x-data="window.targetPlanningSimulator()">
        
        <!-- Header: Identitas & Toolbar Kontrol -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-xs font-black uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200 mb-1.5">
                    <span>🎯 BAGIAN II • TARGET PLANNING & FORECASTING • BANDARA JUANDA</span>
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
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="mb-6 p-5 sm:p-6 bg-slate-50 border-2 border-indigo-200 rounded-2xl space-y-4">
            
            <div class="flex items-center gap-2">
                <span class="text-xl">📚</span>
                <h3 class="text-base font-black text-slate-900 tracking-tight">Buku Panduan Eksekutif: Metodologi & Rumus Simulasi Target</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <!-- Kolom Panduan 1: Filosofi Bisnis Bandara -->
                <div class="bg-white p-4 rounded-xl border border-slate-200 space-y-2">
                    <p class="font-extrabold text-indigo-900 flex items-center gap-1.5">
                        <span>✈️</span>
                        <span>1. Karakteristik Trafik & Penumpang Bandara</span>
                    </p>
                    <ul class="list-disc list-inside space-y-1 text-slate-600 leading-relaxed font-semibold">
                        <li><strong>Trafik Bersifat Terikat (Captive Market):</strong> Lonjakan omset bandara dipengaruhi oleh tingkat konversi penumpang (conversion rate), bukan sekadar penambahan fisik toko.</li>
                        <li><strong>Sensitivitas Waktu Ekstrem:</strong> Penumpang memiliki batas toleransi waktu saji maksimal <strong>15 menit</strong> sebelum jadwal boarding dimulai.</li>
                        <li><strong>Efek Bola Salju Waktu Saji:</strong> Jika rata-rata waktu saji naik melewati 15 menit, pembatalan pesanan meningkat drastis akibat penumpang terburu-buru masuk gate.</li>
                    </ul>
                </div>

                <!-- Kolom Panduan 2: Rumus Matematika Proyeksi -->
                <div class="bg-white p-4 rounded-xl border border-slate-200 space-y-2">
                    <p class="font-extrabold text-indigo-900 flex items-center gap-1.5">
                        <span>📐</span>
                        <span>2. Rumus Matematika Model Simulasi</span>
                    </p>
                    <div class="space-y-1.5 text-slate-700 font-mono text-[11px]">
                        <p class="p-1.5 bg-slate-50 rounded border border-slate-200">
                            <strong>Omset Baik:</strong> Omset_Awal &times; (1 + %Target_Baik)
                        </p>
                        <p class="p-1.5 bg-slate-50 rounded border border-slate-200">
                            <strong>Omset Buruk:</strong> Omset_Awal &times; (1 - %Risiko_Buruk)
                        </p>
                        <p class="p-1.5 bg-slate-50 rounded border border-slate-200">
                            <strong>Omset Hangus:</strong> Omset_Awal &times; 14.5% (Tingkat Batal Buruk)
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toolbar Pemilih Horizon Waktu (Mingguan vs Bulanan) -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-5 p-3 bg-slate-50 rounded-xl border border-slate-200">
            <div class="flex items-center gap-2">
                <span class="text-xs font-black text-slate-600 uppercase tracking-wider">Horizon Waktu Perencanaan:</span>
                <div class="inline-flex rounded-lg p-0.5 bg-slate-200">
                    <button type="button" @click="setHorizon('weekly')" 
                            class="px-3 py-1 rounded-md text-xs font-black transition cursor-pointer"
                            :class="horizon === 'weekly' ? 'bg-white text-indigo-700 shadow-2xs' : 'text-slate-600 hover:text-slate-900'">
                        📅 Mingguan (7 Hari)
                    </button>
                    <button type="button" @click="setHorizon('monthly')" 
                            class="px-3 py-1 rounded-md text-xs font-black transition cursor-pointer"
                            :class="horizon === 'monthly' ? 'bg-white text-indigo-700 shadow-2xs' : 'text-slate-600 hover:text-slate-900'">
                        🗓️ Bulanan (30 Hari)
                    </button>
                </div>
            </div>
            
            <div class="text-xs font-bold text-slate-500">
                Data Dasar Diambil dari Riwayat: <span class="font-black text-indigo-900" x-text="horizon === 'weekly' ? '7 Hari Terakhir' : '30 Hari Terakhir'"></span>
            </div>
        </div>

        <!-- 3 INPUT KONTROL UTAMA -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <!-- Input 1: Omset Baseline -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">
                    💰 Omset Acuan Saat Ini
                </label>
                <div class="relative mt-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-bold text-sm">Rp</span>
                    <input type="number" step="100000" min="0" x-model.number="inputRevenue" 
                           class="w-full pl-9 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-sm font-black text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-2xs">
                </div>
                <span class="text-[11px] font-semibold text-slate-400 mt-1 block" x-text="formatRupiah(inputRevenue)"></span>
            </div>

            <!-- Input 2: Estimasi Pengunjung -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">
                    👥 Estimasi Pengunjung
                </label>
                <div class="relative mt-1">
                    <input type="number" step="10" min="1" x-model.number="inputVisitors" 
                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm font-black text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-2xs">
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 font-bold text-xs">orang</span>
                </div>
                <span class="text-[11px] font-semibold text-slate-400 mt-1 block">Trafik penumpang di terminal</span>
            </div>

            <!-- Input 3: Rata-Rata Waktu Saji Baseline -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <label class="block text-xs font-black text-slate-600 uppercase tracking-wider mb-1">
                    ⏱️ Rata-rata Waktu Saji Saat Ini
                </label>
                <div class="relative mt-1">
                    <input type="number" step="0.5" min="1" max="45" x-model.number="inputSlaMinutes" 
                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm font-black text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-2xs">
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 font-bold text-xs">menit</span>
                </div>
                <span class="text-[11px] font-semibold text-slate-400 mt-1 block">Batas toleransi SOP: 15.0 menit</span>
            </div>
        </div>

        <!-- SLIDER PERSENTASE PERTUMBUHAN / PENURUNAN -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 p-4 bg-indigo-50/50 rounded-xl border border-indigo-100">
            <!-- Slider Kiri: Target Kenaikan Baik -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="text-xs font-black text-emerald-800 uppercase tracking-wider">
                        📈 Target Kenaikan Skenario Baik
                    </label>
                    <span class="text-xs font-black px-2 py-0.5 rounded bg-emerald-100 text-emerald-800" x-text="'+' + goodGrowthPct + '%'"></span>
                </div>
                <input type="range" min="5" max="50" step="1" x-model.number="goodGrowthPct" 
                       class="w-full h-2 bg-emerald-200 rounded-lg appearance-none cursor-pointer accent-emerald-600">
                <span class="text-[11px] font-semibold text-slate-500 mt-1 block">Proyeksi pertumbuhan optimis (5% - 50%)</span>
            </div>

            <!-- Slider Kanan: Risiko Penurunan Buruk -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="text-xs font-black text-rose-800 uppercase tracking-wider">
                        📉 Batas Waspada Penurunan Skenario Buruk
                    </label>
                    <span class="text-xs font-black px-2 py-0.5 rounded bg-rose-100 text-rose-800" x-text="'-' + badDropPct + '%'"></span>
                </div>
                <input type="range" min="5" max="50" step="1" x-model.number="badDropPct" 
                       class="w-full h-2 bg-rose-200 rounded-lg appearance-none cursor-pointer accent-rose-600">
                <span class="text-[11px] font-semibold text-slate-500 mt-1 block">Toleransi risiko penurunan omset (5% - 50%)</span>
            </div>
        </div>

        <!-- HASIL DUA SKENARIO (BERDAMPINGAN) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- KARTU HIJAU: SKENARIO BAIK / TARGET PRIMA -->
            <div class="bg-gradient-to-br from-emerald-50/60 to-white rounded-2xl p-5 border-2 border-emerald-300 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-emerald-200 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="p-2 rounded-xl bg-emerald-600 text-white font-black text-sm shadow-2xs">🟢</span>
                        <div>
                            <h3 class="text-base font-black text-emerald-950 tracking-tight">Skenario Baik (Target Prima)</h3>
                            <span class="text-xs font-bold text-emerald-700">Peluang Penjualan & Kepuasan Maksimal</span>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                        Target +<span x-text="goodGrowthPct"></span>%
                    </span>
                </div>

                <!-- Angka Metrik Utama Skenario Baik -->
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="p-3 bg-white rounded-xl border border-emerald-200">
                        <span class="text-slate-500 font-bold block mb-0.5">Target Omset</span>
                        <span class="text-lg font-black text-emerald-700" x-text="formatRupiah(goodRevenue)"></span>
                        <span class="text-[10px] font-extrabold text-emerald-600 block mt-0.5" x-text="'+' + formatRupiah(goodRevenueDelta)"></span>
                    </div>

                    <div class="p-3 bg-white rounded-xl border border-emerald-200">
                        <span class="text-slate-500 font-bold block mb-0.5">Target Pengunjung Terlayani</span>
                        <span class="text-lg font-black text-slate-900" x-text="goodVisitors.toLocaleString('id-ID') + ' pax'"></span>
                        <span class="text-[10px] font-extrabold text-emerald-600 block mt-0.5" x-text="'+' + goodVisitorsDelta.toLocaleString('id-ID') + ' pengunjung'"></span>
                    </div>

                    <div class="p-3 bg-white rounded-xl border border-emerald-200">
                        <span class="text-slate-500 font-bold block mb-0.5">Wajib Waktu Saji Kilat</span>
                        <span class="text-lg font-black text-emerald-700" x-text="goodSla + ' mnt'"></span>
                        <span class="text-[10px] font-semibold text-slate-500 block mt-0.5">SOP Juanda &le; 15 menit</span>
                    </div>

                    <div class="p-3 bg-white rounded-xl border border-emerald-200">
                        <span class="text-slate-500 font-bold block mb-0.5">Kepatuhan Waktu Saji</span>
                        <span class="text-lg font-black text-emerald-700" x-text="goodCompliance + '%'"></span>
                        <span class="text-[10px] font-semibold text-slate-500 block mt-0.5">Batal &lt; 1%</span>
                    </div>
                </div>

                <!-- Langkah Kerja Menuju Target Prima -->
                <div class="pt-2 border-t border-emerald-100">
                    <span class="text-xs font-black text-emerald-900 uppercase tracking-wider block mb-2">
                        🚀 Langkah Kerja Meraih Target:
                    </span>
                    <ul class="space-y-1.5 text-xs text-slate-700 leading-relaxed">
                        <template x-for="(step, idx) in goodActionSteps" :key="idx">
                            <li class="flex items-start gap-2 bg-white/70 p-2 rounded-lg border border-emerald-100">
                                <span class="font-black text-emerald-700" x-text="(idx + 1) + '.'"></span>
                                <span x-html="step"></span>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            <!-- KARTU MERAH: SKENARIO BURUK / PERLU WASPADA -->
            <div class="bg-gradient-to-br from-rose-50/60 to-white rounded-2xl p-5 border-2 border-rose-300 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-rose-200 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="p-2 rounded-xl bg-rose-600 text-white font-black text-sm shadow-2xs">🔴</span>
                        <div>
                            <h3 class="text-base font-black text-rose-950 tracking-tight">Skenario Buruk (Perlu Waspada)</h3>
                            <span class="text-xs font-bold text-rose-700">Mitigasi Risiko Waktu Saji & Pembatalan</span>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-rose-100 text-rose-800 border border-rose-300">
                        Penurunan -<span x-text="badDropPct"></span>%
                    </span>
                </div>

                <!-- Angka Metrik Utama Skenario Buruk -->
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="p-3 bg-white rounded-xl border border-rose-200">
                        <span class="text-slate-500 font-bold block mb-0.5">Risiko Omset Melorot</span>
                        <span class="text-lg font-black text-rose-700" x-text="formatRupiah(badRevenue)"></span>
                        <span class="text-[10px] font-extrabold text-rose-600 block mt-0.5" x-text="'-' + formatRupiah(badRevenueDelta)"></span>
                    </div>

                    <div class="p-3 bg-white rounded-xl border border-rose-200">
                        <span class="text-slate-500 font-bold block mb-0.5">Trafik Terlayani</span>
                        <span class="text-lg font-black text-slate-900" x-text="badVisitors.toLocaleString('id-ID') + ' pax'"></span>
                        <span class="text-[10px] font-extrabold text-rose-600 block mt-0.5" x-text="'-' + badVisitorsDelta.toLocaleString('id-ID') + ' pengunjung'"></span>
                    </div>

                    <div class="p-3 bg-white rounded-xl border border-rose-200">
                        <span class="text-slate-500 font-bold block mb-0.5">Risiko Waktu Saji Bottleneck</span>
                        <span class="text-lg font-black text-rose-700" x-text="badSla + ' mnt'"></span>
                        <span class="text-[10px] font-extrabold text-rose-600 block mt-0.5">MELEBIHI BATAS 15 MENIT!</span>
                    </div>

                    <div class="p-3 bg-white rounded-xl border border-rose-200">
                        <span class="text-slate-500 font-bold block mb-0.5">Potensi Omset Hangus</span>
                        <span class="text-lg font-black text-rose-700" x-text="formatRupiah(badLostRevenue)"></span>
                        <span class="text-[10px] font-semibold text-rose-600 block mt-0.5" x-text="'Batal ' + badCancelRate + '%'"></span>
                    </div>
                </div>

                <!-- Langkah Mitigasi Skenario Buruk -->
                <div class="pt-2 border-t border-rose-100">
                    <span class="text-xs font-black text-rose-900 uppercase tracking-wider block mb-2">
                        🛡️ Rencana Mitigasi Risiko:
                    </span>
                    <ul class="space-y-1.5 text-xs text-slate-700 leading-relaxed">
                        <template x-for="(step, idx) in badMitigationSteps" :key="idx">
                            <li class="flex items-start gap-2 bg-white/70 p-2 rounded-lg border border-rose-100">
                                <span class="font-black text-rose-700" x-text="(idx + 1) + '.'"></span>
                                <span x-html="step"></span>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
