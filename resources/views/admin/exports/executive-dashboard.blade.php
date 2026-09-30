<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Eksekutif FlyDine - Bandara Internasional Juanda</title>
    <style>
        @page {
            margin: 20px 25px 25px 25px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #1e293b;
            line-height: 1.4;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        /* Utility */
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .capitalize { text-transform: capitalize; }
        .w-full { width: 100%; }
        
        /* Brand Colors */
        .text-primary { color: #005ea2; }
        .text-secondary { color: #8dc63f; }
        .text-slate { color: #64748b; }
        .text-dark { color: #0f172a; }
        .text-emerald { color: #059669; }
        .text-rose { color: #e11d48; }

        /* Header / Kop Surat Modern */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .header-table td {
            vertical-align: middle;
            border: none;
            padding: 0;
        }
        .doc-title {
            font-size: 16px;
            font-weight: 900;
            letter-spacing: 0.5px;
            color: #0b2b48;
            margin: 0;
            text-transform: uppercase;
        }
        .doc-subtitle {
            font-size: 11px;
            font-weight: 700;
            color: #005ea2;
            margin: 2px 0 0 0;
        }
        .doc-location {
            font-size: 8.5px;
            font-weight: 600;
            color: #64748b;
            margin-top: 1px;
        }
        .decorative-line {
            height: 3px;
            background-color: #005ea2;
            width: 100%;
            margin-bottom: 10px;
            border-radius: 2px;
        }

        /* Metadata Badges Bar */
        .meta-bar {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }
        .meta-bar td {
            padding: 6px 10px;
            font-size: 8.5px;
            border: none;
            color: #475569;
        }

        /* KPI Card Tiles */
        .kpi-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px;
            margin-bottom: 12px;
            margin-left: -6px;
            margin-right: -6px;
        }
        .kpi-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
            vertical-align: top;
        }
        .kpi-title {
            font-size: 7.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 4px;
        }
        .kpi-value {
            font-size: 16px;
            font-weight: 900;
            margin-bottom: 2px;
            letter-spacing: -0.3px;
        }
        .kpi-sub {
            font-size: 7.5px;
            color: #94a3b8;
            font-weight: 600;
        }

        /* Section Header */
        .section-header {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0b2b48;
            margin: 10px 0 6px 0;
            padding-bottom: 3px;
            border-bottom: 1.5px solid #005ea2;
        }

        /* Modern Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            overflow: hidden;
        }
        .data-table th {
            background-color: #f1f5f9;
            color: #0b2b48;
            font-weight: 800;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 6px 8px;
            border-bottom: 1.5px solid #cbd5e1;
            border-top: none;
            border-left: none;
            border-right: none;
        }
        .data-table td {
            padding: 5.5px 8px;
            font-size: 8.5px;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
            border-top: none;
            border-left: none;
            border-right: none;
        }
        .data-table tr:nth-child(even) td {
            background-color: #fafcff;
        }

        /* Badges / Status Pills */
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 12px;
            font-size: 7.5px;
            font-weight: 700;
            text-align: center;
        }
        .badge-success { background-color: #dcfce7; color: #15803d; }
        .badge-danger { background-color: #ffe4e6; color: #be123c; }
        .badge-warning { background-color: #fef3c7; color: #b45309; }
        .badge-info { background-color: #e0f2fe; color: #0369a1; }
        .badge-slate { background-color: #f1f5f9; color: #475569; }

        /* Progress Bar */
        .progress-bar-bg {
            background-color: #f1f5f9;
            border-radius: 4px;
            height: 6px;
            width: 100%;
            display: inline-block;
            vertical-align: middle;
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 6px;
            border-radius: 4px;
            background-color: #005ea2;
        }

        /* Two Columns Layout */
        .two-col-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .two-col-table td {
            vertical-align: top;
            padding: 0;
            border: none;
        }

        /* Callout Box */
        .callout-box {
            background-color: #f0f7ff;
            border-left: 3.5px solid #005ea2;
            padding: 8px 12px;
            border-radius: 0 6px 6px 0;
            margin-bottom: 14px;
            font-size: 8px;
            color: #1e3a8a;
        }

        /* Signature / Sign-off Block */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .signature-table td {
            vertical-align: top;
            border: none;
            padding: 0;
        }
        .signature-box {
            width: 200px;
            text-align: center;
            float: right;
            border-top: 1px solid #cbd5e1;
            padding-top: 5px;
        }

        .footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 7.5px;
            color: #94a3b8;
            padding-top: 4px;
        }
    </style>
</head>
<body>

    <!-- Header Section with Dual Logos -->
    <table class="header-table">
        <tr>
            <!-- Angkasa Pura Logo -->
            <td width="22%">
                @if(isset($logoAngkasaPura) && $logoAngkasaPura)
                    <img src="{{ $logoAngkasaPura }}" alt="Angkasa Pura" style="height: 38px; width: auto;">
                @else
                    <span style="font-weight: 900; font-size: 13px; color: #005ea2;">ANGKASA PURA</span>
                @endif
            </td>
            <!-- Center Title -->
            <td width="56%" class="text-center">
                <div class="doc-title">Laporan Eksekutif Performa Operasional</div>
                <div class="doc-subtitle">Sistem Informasi Eksekutif (SIE) FlyDine Juanda</div>
                <div class="doc-location">Bandara Internasional Juanda Surabaya (SUB) &bull; Commercial Division</div>
            </td>
            <!-- FlyDine Logo -->
            <td width="22%" class="text-right">
                @if(isset($logoFlydine) && $logoFlydine)
                    <img src="{{ $logoFlydine }}" alt="FlyDine" style="height: 38px; width: auto;">
                @else
                    <span style="font-weight: 900; font-size: 16px; color: #005ea2;">FlyDine<span style="color:#8dc63f;">.</span></span>
                @endif
            </td>
        </tr>
    </table>

    <!-- Gradient Divider Line -->
    <div class="decorative-line"></div>

    <!-- Metadata Badges Bar -->
    <table class="meta-bar">
        <tr>
            <td width="30%">
                <strong>Periode:</strong> {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }} ({{ $days }} Hari)
            </td>
            <td width="28%">
                <strong>Filter Tenant:</strong> 
                <span class="badge {{ $tenant ? 'badge-info' : 'badge-slate' }}">
                    {{ $tenant ? $tenant->name : 'Semua Tenant' }}
                </span>
            </td>
            <td width="22%">
                <strong>Klasifikasi:</strong> <span class="badge badge-warning">INTERNAL / CONFIDENTIAL</span>
            </td>
            <td width="20%" class="text-right">
                <strong>Dicetak:</strong> {{ now()->format('d/m/Y H:i') }} WIB
            </td>
        </tr>
    </table>

    @php
        $totalOrders = $statusDistribution->sum('total');
        $canceledOrders = $statusDistribution->where('status', 'dibatalkan')->first()->total ?? 0;
        $cancelRate = $totalOrders > 0 ? round(($canceledOrders / $totalOrders) * 100, 1) : 0;
        $avgSLA = $slaPerformance->count() > 0 ? $slaPerformance->avg('avg_minutes') : 0;
    @endphp

    <!-- 4 KPI Card Tiles -->
    <table class="kpi-table">
        <tr>
            <!-- Card 1: Total Omset -->
            <td width="25%">
                <div class="kpi-card" style="border-top: 3px solid #10b981;">
                    <div class="kpi-title">Gross Revenue (Omset)</div>
                    <div class="kpi-value text-emerald">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    <div class="kpi-sub">Total transaksi terbayar</div>
                </div>
            </td>
            <!-- Card 2: Total Volume -->
            <td width="25%">
                <div class="kpi-card" style="border-top: 3px solid #005ea2;">
                    <div class="kpi-title">Total Volume Pesanan</div>
                    <div class="kpi-value text-primary">{{ number_format($totalOrders) }} <span style="font-size: 10px; font-weight: 600; color: #64748b;">Order</span></div>
                    <div class="kpi-sub">Pesanan masuk sistem</div>
                </div>
            </td>
            <!-- Card 3: Cancel Rate -->
            <td width="25%">
                <div class="kpi-card" style="border-top: 3px solid #f43f5e;">
                    <div class="kpi-title">Tingkat Pembatalan</div>
                    <div class="kpi-value text-rose">{{ $cancelRate }}%</div>
                    <div class="kpi-sub">{{ $canceledOrders }} pesanan dibatalkan</div>
                </div>
            </td>
            <!-- Card 4: Average SLA -->
            <td width="25%">
                <div class="kpi-card" style="border-top: 3px solid #f59e0b;">
                    <div class="kpi-title">Rata-rata Waktu Kesiapan</div>
                    <div class="kpi-value" style="color: #d97706;">{{ round($avgSLA, 1) }} <span style="font-size: 10px; font-weight: 600; color: #64748b;">Menit</span></div>
                    <div class="kpi-sub">Standar SLA: &le; 15 menit</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Callout Insight Box -->
    <div class="callout-box">
        <strong>Ringkasan Eksekutif:</strong> 
        Pada periode ini tercatat total <strong>{{ $totalOrders }} pesanan</strong> dengan perputaran omset sebesar <strong>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong>. 
        Rata-rata kecepatan penyiapan pesanan (SLA) restoran adalah <strong>{{ round($avgSLA, 1) }} menit</strong> 
        ({{ $avgSLA <= 15 ? 'Memenuhi target efisiensi operasional bandara' : 'Perlu atensi akselerasi penyiapan di dapur tenant' }}).
        Status penanganan keluhan pelanggan: <strong>{{ $resolvedComplaints }} terselesaikan</strong>, <strong>{{ $openComplaints }} komplain aktif</strong>.
    </div>

    <!-- Section: Two Columns (Kinerja SLA Restoran & Distribusi Status) -->
    <table class="two-col-table">
        <tr>
            <!-- Left Col: SLA Restoran -->
            <td width="58%" style="padding-right: 8px;">
                <div class="section-header">Kinerja Kecepatan Pelayanan Restoran (SLA)</div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="8%" class="text-center">No</th>
                            <th width="42%">Nama Restoran / Tenant</th>
                            <th width="24%" class="text-right">Rata-rata Waktu</th>
                            <th width="26%" class="text-center">Kepatuhan SLA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($slaPerformance as $index => $sla)
                        <tr>
                            <td class="text-center font-bold" style="color: #64748b;">#{{ $index + 1 }}</td>
                            <td class="font-bold text-dark">{{ $sla->name }}</td>
                            <td class="text-right font-bold text-primary">{{ round($sla->avg_minutes, 1) }} Menit</td>
                            <td class="text-center">
                                @if($sla->avg_minutes <= 15)
                                    <span class="badge badge-success">&le; 15 Mnt (Optimal)</span>
                                @else
                                    <span class="badge badge-danger">&gt; 15 Mnt (Lambat)</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-slate" style="padding: 12px;">Tidak ada data SLA pesanan pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </td>

            <!-- Right Col: Distribusi Status Transaksi -->
            <td width="42%" style="padding-left: 8px;">
                <div class="section-header">Distribusi Status Pesanan</div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="32%">Status</th>
                            <th width="20%" class="text-right">Total</th>
                            <th width="48%">Persentase & Visual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($statusDistribution as $stat)
                        @php
                            $pct = $totalOrders > 0 ? round(($stat->total / $totalOrders) * 100, 1) : 0;
                            $badgeClass = match($stat->status) {
                                'selesai' => 'badge-success',
                                'dibatalkan' => 'badge-danger',
                                'diproses' => 'badge-info',
                                'menunggu' => 'badge-warning',
                                default => 'badge-slate'
                            };
                            $barColor = match($stat->status) {
                                'selesai' => '#10b981',
                                'dibatalkan' => '#f43f5e',
                                'diproses' => '#0284c7',
                                'menunggu' => '#f59e0b',
                                default => '#64748b'
                            };
                        @endphp
                        <tr>
                            <td><span class="badge {{ $badgeClass }} capitalize">{{ $stat->status }}</span></td>
                            <td class="text-right font-bold text-dark">{{ $stat->total }}</td>
                            <td>
                                <table style="width: 100%; border: none; border-collapse: collapse;">
                                    <tr>
                                        <td style="width: 70%; padding: 0; border: none;">
                                            <div class="progress-bar-bg">
                                                <div class="progress-bar-fill" style="width: {{ $pct }}%; background-color: {{ $barColor }};"></div>
                                            </div>
                                        </td>
                                        <td style="width: 30%; padding: 0 0 0 5px; border: none; font-size: 7.5px; font-weight: bold; text-align: right; color: #475569;">
                                            {{ $pct }}%
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-slate">Tidak ada status pesanan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <!-- Section: Two Columns (Top 5 Menu Terlaris & Top 5 Restoran Volume) -->
    <table class="two-col-table">
        <tr>
            <!-- Left: Top Products -->
            <td width="50%" style="padding-right: 8px;">
                <div class="section-header">Top 5 Menu Makanan / Minuman Terlaris</div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="10%" class="text-center">No</th>
                            <th width="50%">Nama Menu Hidangan</th>
                            <th width="15%" class="text-center">Qty</th>
                            <th width="25%" class="text-right">Total Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $idx => $prod)
                        <tr>
                            <td class="text-center font-bold" style="color: #64748b;">#{{ $idx + 1 }}</td>
                            <td class="font-bold text-dark">{{ $prod->product_name_snapshot }}</td>
                            <td class="text-center font-bold text-primary">{{ $prod->total_qty }}x</td>
                            <td class="text-right font-bold text-dark">Rp {{ number_format($prod->total_sales, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-slate">Belum ada item pesanan pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </td>

            <!-- Right: Top Tenants by Volume -->
            <td width="50%" style="padding-left: 8px;">
                <div class="section-header">Top 5 Restoran dengan Transaksi Terbanyak</div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="10%" class="text-center">No</th>
                            <th width="45%">Restoran</th>
                            <th width="20%" class="text-center">Volume</th>
                            <th width="25%" class="text-right">Omset</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tenantPerformance as $i => $tp)
                        <tr>
                            <td class="text-center font-bold" style="color: #64748b;">#{{ $i + 1 }}</td>
                            <td class="font-bold text-dark">{{ $tp->name }}</td>
                            <td class="text-center font-bold text-primary">{{ $tp->total_orders }} Pesanan</td>
                            <td class="text-right font-bold text-emerald">Rp {{ number_format($tp->total_omset, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-slate">Belum ada data tenant pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <!-- Section: Enterprise CRM Customer Segmentation -->
    <div class="section-header">Enterprise CRM &bull; Analisis Segmentasi Loyalitas Pelanggan Bandara</div>
    @php
        $newCust = $customerSegmentation->new_customers ?? 0;
        $regCust = $customerSegmentation->regular_customers ?? 0;
        $freqCust = $customerSegmentation->frequent_customers ?? 0;
        $totalCust = $customerSegmentation->total_customers ?? 0;

        $newPct = $totalCust > 0 ? round(($newCust / $totalCust) * 100, 1) : 0;
        $regPct = $totalCust > 0 ? round(($regCust / $totalCust) * 100, 1) : 0;
        $freqPct = $totalCust > 0 ? round(($freqCust / $totalCust) * 100, 1) : 0;
    @endphp
    <table class="data-table" style="margin-bottom: 8px;">
        <thead>
            <tr>
                <th width="26%">Segmen Pelanggan</th>
                <th width="24%">Kriteria Perilaku Transaksi</th>
                <th width="15%" class="text-right">Total User</th>
                <th width="35%">Distribusi Basis Pelanggan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><span class="badge badge-info">&bull; Pelanggan Baru (Newcomer)</span></td>
                <td>1 Kali Pemesanan</td>
                <td class="text-right font-bold">{{ number_format($newCust) }} Pelanggan</td>
                <td>
                    <table style="width: 100%; border: none; border-collapse: collapse;">
                        <tr>
                            <td style="width: 75%; padding: 0; border: none;">
                                <div class="progress-bar-bg">
                                    <div class="progress-bar-fill" style="width: {{ $newPct }}%; background-color: #0284c7;"></div>
                                </div>
                            </td>
                            <td style="width: 25%; padding: 0 0 0 6px; border: none; font-size: 7.5px; font-weight: bold; text-align: right; color: #0284c7;">
                                {{ $newPct }}%
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td><span class="badge badge-warning">&bull; Pelanggan Reguler (Returning)</span></td>
                <td>2 s/d 5 Kali Pemesanan</td>
                <td class="text-right font-bold">{{ number_format($regCust) }} Pelanggan</td>
                <td>
                    <table style="width: 100%; border: none; border-collapse: collapse;">
                        <tr>
                            <td style="width: 75%; padding: 0; border: none;">
                                <div class="progress-bar-bg">
                                    <div class="progress-bar-fill" style="width: {{ $regPct }}%; background-color: #f59e0b;"></div>
                                </div>
                            </td>
                            <td style="width: 25%; padding: 0 0 0 6px; border: none; font-size: 7.5px; font-weight: bold; text-align: right; color: #d97706;">
                                {{ $regPct }}%
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td><span class="badge badge-success">&bull; Pelanggan Setia (VIP Frequent Flyer)</span></td>
                <td>Lebih dari 5 Kali Pemesanan</td>
                <td class="text-right font-bold">{{ number_format($freqCust) }} Pelanggan</td>
                <td>
                    <table style="width: 100%; border: none; border-collapse: collapse;">
                        <tr>
                            <td style="width: 75%; padding: 0; border: none;">
                                <div class="progress-bar-bg">
                                    <div class="progress-bar-fill" style="width: {{ $freqPct }}%; background-color: #10b981;"></div>
                                </div>
                            </td>
                            <td style="width: 25%; padding: 0 0 0 6px; border: none; font-size: 7.5px; font-weight: bold; text-align: right; color: #059669;">
                                {{ $freqPct }}%
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr style="background-color: #f8fafc; font-weight: 800;">
                <td colspan="2" class="text-right" style="color: #475569;">Total Basis Pelanggan Terdaftar:</td>
                <td class="text-right text-primary font-bold">{{ number_format($totalCust) }} Pelanggan</td>
                <td class="font-bold text-primary">100% Tercatat di CRM</td>
            </tr>
        </tbody>
    </table>

    <!-- Signature & Verification Sign-off -->
    <table class="signature-table">
        <tr>
            <td width="60%" style="font-size: 8px; color: #64748b; line-height: 1.5;">
                <strong>Verifikasi Sistem Eksekutif:</strong><br>
                Dokumen ini merupakan laporan intelijen bisnis resmi yang di-generate secara otomatis oleh modul Sistem Informasi Eksekutif (SIE) FlyDine Bandara Internasional Juanda.<br>
                <em>Security Code: FLD-SUB-{{ strtoupper(substr(md5(now()), 0, 10)) }} &bull; Status: Validated</em>
            </td>
            <td width="40%" class="text-right">
                <div class="signature-box">
                    <p style="margin: 0; font-size: 8px; color: #64748b;">Surabaya, {{ now()->format('d M Y') }}</p>
                    <p style="margin: 2px 0 35px 0; font-weight: bold; font-size: 8.5px; color: #0b2b48;">Commercial & Operation Head<br>Bandara Internasional Juanda</p>
                    <p style="margin: 0; font-weight: 800; font-size: 9px; color: #005ea2; text-decoration: underline;">MANAGEMENT FLYDINE</p>
                    <p style="margin: 1px 0 0 0; font-size: 7.5px; color: #94a3b8;">PT Angkasa Pura Indonesia</p>
                </div>
            </td>
        </tr>
    </table>

    <!-- Fixed Footer -->
    <div class="footer">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; padding: 0; text-align: left;">
                    FlyDine Juanda &bull; Executive Operational Intelligence Report
                </td>
                <td style="border: none; padding: 0; text-align: right;">
                    Halaman 1 dari 1 &bull; Rahasia Perusahaan (Confidential)
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
