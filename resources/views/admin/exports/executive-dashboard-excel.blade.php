<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:x="urn:schemas-microsoft-com:office:excel"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
@verbatim
<!--[if gte mso 9]>
<xml>
 <x:ExcelWorkbook>
  <x:ExcelWorksheets>
   <x:ExcelWorksheet>
    <x:Name>Laporan Eksekutif</x:Name>
    <x:WorksheetOptions>
     <x:DisplayGridlines/>
     <x:FitToPage/>
    </x:WorksheetOptions>
   </x:ExcelWorksheet>
  </x:ExcelWorksheets>
 </x:ExcelWorkbook>
</xml>
<![endif]-->
@endverbatim
<style>
    body, table {
        font-family: 'Calibri', 'Segoe UI', Arial, sans-serif;
        font-size: 11pt;
        color: #1e293b;
    }
    .title-main {
        font-size: 16pt;
        font-weight: bold;
        color: #005ea2;
        text-align: left;
    }
    .title-sub {
        font-size: 11pt;
        font-weight: bold;
        color: #475569;
        text-align: left;
    }
    .meta-label {
        font-weight: bold;
        color: #64748b;
        background-color: #f1f5f9;
        border: 1px solid #cbd5e1;
    }
    .meta-value {
        color: #0f172a;
        border: 1px solid #cbd5e1;
    }
    .section-header {
        font-size: 12pt;
        font-weight: bold;
        color: #ffffff;
        background-color: #005ea2;
        border: 1px solid #005ea2;
        padding: 6px 10px;
    }
    .th-navy {
        background-color: #0f2744;
        color: #ffffff;
        font-weight: bold;
        text-align: center;
        border: 1px solid #0b1d33;
        padding: 6px;
    }
    .th-sub {
        background-color: #e2e8f0;
        color: #1e293b;
        font-weight: bold;
        border: 1px solid #cbd5e1;
        padding: 5px;
    }
    .td-data {
        border: 1px solid #cbd5e1;
        padding: 5px;
    }
    .td-center {
        border: 1px solid #cbd5e1;
        text-align: center;
        padding: 5px;
    }
    .td-right {
        border: 1px solid #cbd5e1;
        text-align: right;
        padding: 5px;
    }
    .td-bold-right {
        border: 1px solid #cbd5e1;
        text-align: right;
        font-weight: bold;
        padding: 5px;
    }
    .row-zebra {
        background-color: #f8fafc;
    }
    .badge-success {
        color: #059669;
        font-weight: bold;
    }
    .badge-danger {
        color: #e11d48;
        font-weight: bold;
    }
</style>
</head>
<body>

    <!-- KOP & METADATA LAPORAN EKSEKUTIF -->
    <table>
        <tr>
            <td colspan="6" class="title-main">FLYDINE JUANDA AIRPORT - SISTEM INFORMASI EKSEKUTIF</td>
        </tr>
        <tr>
            <td colspan="6" class="title-sub">Laporan Kinerja Operasional & Analisis Bisnis Multi-Tenant</td>
        </tr>
        <tr><td colspan="6"></td></tr>
        <tr>
            <td class="meta-label">Periode Analisis:</td>
            <td colspan="2" class="meta-value">{{ (int)$days }} Hari ({{ $startDate->translatedFormat('d M Y') }} s/d {{ $endDate->translatedFormat('d M Y') }})</td>
            <td class="meta-label">Waktu Unduh:</td>
            <td colspan="2" class="meta-value">{{ now()->translatedFormat('d F Y, H:i') }} WIB</td>
        </tr>
        <tr>
            <td class="meta-label">Filter Tenant:</td>
            <td colspan="2" class="meta-value">{{ $tenant ? $tenant->name : 'Semua Tenant (Konsolidasi Seluruh Bandara)' }}</td>
            <td class="meta-label">Diunduh Oleh:</td>
            <td colspan="2" class="meta-value">{{ auth()->user()->name }} (Admin Operasional)</td>
        </tr>
    </table>

    <br>

    <!-- 1. RINGKASAN KPI EKSEKUTIF -->
    <table border="1">
        <tr>
            <th colspan="4" class="section-header">1. RINGKASAN KPI UTAMA (EXECUTIVE SUMMARY)</th>
        </tr>
        <tr class="th-sub">
            <th width="220">Indikator Kinerja (KPI)</th>
            <th width="160">Nilai Realisasi</th>
            <th width="120">Satuan</th>
            <th width="240">Catatan / Keterangan</th>
        </tr>
        <tr>
            <td class="td-data font-bold">Total Gross Revenue (Omset Lunas)</td>
            <td class="td-bold-right" style="color: #005ea2;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            <td class="td-center">Rupiah (IDR)</td>
            <td class="td-data">Total transaksi berstatus lunas pada periode terpilih</td>
        </tr>
        <tr class="row-zebra">
            <td class="td-data font-bold">Total Volume Transaksi</td>
            <td class="td-bold-right">{{ number_format($totalOrders, 0, ',', '.') }}</td>
            <td class="td-center">Pesanan</td>
            <td class="td-data">Total akumulasi order masuk ke sistem FlyDine</td>
        </tr>
        @php
            $canceledCount = $statusDistribution->where('status', 'dibatalkan')->first()->total ?? 0;
            $cancelRate = $totalOrders > 0 ? round(($canceledCount / $totalOrders) * 100, 1) : 0;
            $slaMap = $slaPerformance->keyBy('name');
            $avgAllSla = $slaPerformance->avg('avg_minutes');
        @endphp
        <tr>
            <td class="td-data font-bold">Tingkat Pembatalan (Cancel Rate)</td>
            <td class="td-bold-right {{ $cancelRate > 10 ? 'badge-danger' : 'badge-success' }}">{{ $cancelRate }}%</td>
            <td class="td-center">Persentase</td>
            <td class="td-data">{{ $canceledCount }} pesanan dibatalkan {{ $cancelRate <= 5 ? '(Kondisi Sehat)' : '(Perlu Perhatian)' }}</td>
        </tr>
        <tr class="row-zebra">
            <td class="td-data font-bold">Rata-rata Waktu Masak (SLA Operasional)</td>
            <td class="td-bold-right">{{ $avgAllSla ? round($avgAllSla, 1) : '-' }}</td>
            <td class="td-center">Menit / Pesanan</td>
            <td class="td-data">Target SLA standar bandara: &le; 15 menit</td>
        </tr>
        <tr>
            <td class="td-data font-bold">Tingkat Penyelesaian Komplain CS</td>
            <td class="td-bold-right badge-success">{{ $resolvedComplaints }} / {{ $openComplaints + $resolvedComplaints }}</td>
            <td class="td-center">Tiket Komplain</td>
            <td class="td-data">{{ $openComplaints }} komplain aktif memerlukan tindak lanjut</td>
        </tr>
    </table>

    <br>

    <!-- 2. PERFORMA TENANT -->
    <table border="1">
        <tr>
            <th colspan="6" class="section-header">2. PERFORMA TENANT RESTORAN & KAFE</th>
        </tr>
        <tr class="th-navy">
            <th width="40">No</th>
            <th width="240">Nama Tenant</th>
            <th width="130">Total Pesanan</th>
            <th width="170">Total Omset (Rp)</th>
            <th width="130">Rata-rata SLA (Menit)</th>
            <th width="150">Status Kinerja SLA</th>
        </tr>
        @forelse($tenantPerformance as $idx => $t)
            @php
                $tSla = $slaMap->get($t->name)->avg_minutes ?? null;
            @endphp
            <tr class="{{ $idx % 2 == 1 ? 'row-zebra' : '' }}">
                <td class="td-center">{{ $idx + 1 }}</td>
                <td class="td-data font-bold">{{ $t->name }}</td>
                <td class="td-center">{{ number_format($t->total_orders, 0, ',', '.') }}</td>
                <td class="td-right">Rp {{ number_format($t->total_omset, 0, ',', '.') }}</td>
                <td class="td-center">{{ $tSla ? round($tSla, 1) : '-' }}</td>
                <td class="td-center">
                    @if(!$tSla)
                        <span style="color: #64748b;">Belum ada data</span>
                    @elseif($tSla <= 15)
                        <span class="badge-success">Cepat (&le; 15m)</span>
                    @elseif($tSla <= 25)
                        <span style="color: #d97706; font-weight: bold;">Normal (16-25m)</span>
                    @else
                        <span class="badge-danger">Lambat (&gt; 25m)</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="td-center">Tidak ada data transaksi pada periode ini.</td>
            </tr>
        @endforelse
    </table>

    <br>

    <!-- 3. TOP PRODUK TERLARIS -->
    <table border="1">
        <tr>
            <th colspan="4" class="section-header">3. DAFTAR MENU TERLARIS (TOP FAVORITE MENU)</th>
        </tr>
        <tr class="th-navy">
            <th width="40">No</th>
            <th width="300">Nama Menu / Produk</th>
            <th width="150">Porsi Terjual (Qty)</th>
            <th width="180">Total Penjualan (Rp)</th>
        </tr>
        @forelse($topProducts as $idx => $prod)
            <tr class="{{ $idx % 2 == 1 ? 'row-zebra' : '' }}">
                <td class="td-center">{{ $idx + 1 }}</td>
                <td class="td-data font-bold">{{ $prod->product_name_snapshot }}</td>
                <td class="td-center font-bold">{{ number_format($prod->total_qty, 0, ',', '.') }} porsi</td>
                <td class="td-right">Rp {{ number_format($prod->total_sales, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="td-center">Tidak ada rincian item menu pada periode ini.</td>
            </tr>
        @endforelse
    </table>

    <br>

    <!-- 4. DISTRIBUSI STATUS PESANAN -->
    <table border="1">
        <tr>
            <th colspan="3" class="section-header">4. DISTRIBUSI STATUS PESANAN</th>
        </tr>
        <tr class="th-navy">
            <th width="200">Status Pesanan</th>
            <th width="140">Jumlah Pesanan</th>
            <th width="140">Proporsi (%)</th>
        </tr>
        @php
            $statusLabels = [
                'menunggu' => 'Menunggu Konfirmasi / Bayar',
                'diproses' => 'Sedang Dimasak / Diproses',
                'siap' => 'Siap Diambil / Diantar',
                'selesai' => 'Pesanan Selesai Diterima',
                'dibatalkan' => 'Pesanan Dibatalkan',
                'ditolak' => 'Pesanan Ditolak'
            ];
        @endphp
        @forelse($statusDistribution as $idx => $s)
            @php
                $pct = $totalOrders > 0 ? round(($s->total / $totalOrders) * 100, 1) : 0;
            @endphp
            <tr class="{{ $idx % 2 == 1 ? 'row-zebra' : '' }}">
                <td class="td-data font-bold">{{ $statusLabels[$s->status] ?? ucfirst($s->status) }}</td>
                <td class="td-center">{{ number_format($s->total, 0, ',', '.') }}</td>
                <td class="td-center font-bold">{{ $pct }}%</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="td-center">Tidak ada data status pesanan.</td>
            </tr>
        @endforelse
    </table>

    <br>

    <!-- 5. TREN TRANSAKSI HARIAN -->
    <table border="1">
        <tr>
            <th colspan="3" class="section-header">5. TREN TRANSAKSI HARIAN (DAILY ORDER VOLUME)</th>
        </tr>
        <tr class="th-navy">
            <th width="40">No</th>
            <th width="200">Tanggal Transaksi</th>
            <th width="160">Volume Pesanan</th>
        </tr>
        @forelse($volumePerDay as $idx => $v)
            <tr class="{{ $idx % 2 == 1 ? 'row-zebra' : '' }}">
                <td class="td-center">{{ $idx + 1 }}</td>
                <td class="td-center font-bold">{{ \Carbon\Carbon::parse($v->date)->translatedFormat('d F Y') }}</td>
                <td class="td-center font-bold" style="color: #005ea2;">{{ number_format($v->total, 0, ',', '.') }} Order</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="td-center">Tidak ada data volume harian.</td>
            </tr>
        @endforelse
    </table>

    <br>

    <!-- 6. ANALISIS CRM & SEGMENTASI PELANGGAN -->
    <table border="1">
        <tr>
            <th colspan="4" class="section-header">6. SEGMENTASI PELANGGAN BANDARA (CRM INSIGHTS)</th>
        </tr>
        <tr class="th-navy">
            <th width="220">Kategori Segmen Pelanggan</th>
            <th width="180">Kriteria Frekuensi Order</th>
            <th width="140">Jumlah Pengguna</th>
            <th width="140">Persentase (%)</th>
        </tr>
        @php
            $totCust = $customerSegmentation->total_customers ?? 0;
            $newPct = $totCust > 0 ? round(($customerSegmentation->new_customers / $totCust) * 100, 1) : 0;
            $regPct = $totCust > 0 ? round(($customerSegmentation->regular_customers / $totCust) * 100, 1) : 0;
            $freqPct = $totCust > 0 ? round(($customerSegmentation->frequent_customers / $totCust) * 100, 1) : 0;
        @endphp
        <tr>
            <td class="td-data font-bold">Pelanggan Baru (First-Time Buyer)</td>
            <td class="td-data">Tepat 1 kali bertransaksi</td>
            <td class="td-center font-bold">{{ number_format($customerSegmentation->new_customers ?? 0, 0, ',', '.') }}</td>
            <td class="td-center font-bold">{{ $newPct }}%</td>
        </tr>
        <tr class="row-zebra">
            <td class="td-data font-bold">Pelanggan Reguler (Returning Customer)</td>
            <td class="td-data">2 s/d 5 kali bertransaksi</td>
            <td class="td-center font-bold">{{ number_format($customerSegmentation->regular_customers ?? 0, 0, ',', '.') }}</td>
            <td class="td-center font-bold">{{ $regPct }}%</td>
        </tr>
        <tr>
            <td class="td-data font-bold">Pelanggan Setia (Frequent Flyer / Loyal)</td>
            <td class="td-data">&gt; 5 kali bertransaksi</td>
            <td class="td-center font-bold" style="color: #059669;">{{ number_format($customerSegmentation->frequent_customers ?? 0, 0, ',', '.') }}</td>
            <td class="td-center font-bold badge-success">{{ $freqPct }}%</td>
        </tr>
        <tr class="th-sub">
            <td colspan="2" class="td-bold-right">Total Akun Pelanggan Terdaftar:</td>
            <td class="td-center font-bold">{{ number_format($totCust, 0, ',', '.') }}</td>
            <td class="td-center font-bold">100%</td>
        </tr>
    </table>

    <br>
    <table>
        <tr>
            <td colspan="6" style="font-size: 9pt; color: #94a3b8; font-style: italic;">
                Laporan ini dibuat otomatis oleh Sistem Informasi Eksekutif FlyDine Bandara Internasional Juanda Surabaya.
            </td>
        </tr>
    </table>

</body>
</html>
