<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panduan & Metodologi Simulasi Target - FlyDine Juanda</title>
    <style>
        @page {
            margin: 22px 26px 26px 26px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9.5px;
            color: #1e293b;
            line-height: 1.45;
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
        .w-full { width: 100%; }
        
        /* Colors */
        .text-primary { color: #005ea2; }
        .text-secondary { color: #8dc63f; }
        .text-slate { color: #64748b; }
        .text-dark { color: #0f172a; }
        .text-emerald { color: #059669; }
        .text-rose { color: #e11d48; }

        /* Header / Kop Surat */
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
            font-size: 15px;
            font-weight: 900;
            letter-spacing: 0.5px;
            color: #0b2b48;
            margin: 0;
            text-transform: uppercase;
        }
        .doc-subtitle {
            font-size: 10.5px;
            font-weight: 700;
            color: #005ea2;
            margin: 2px 0 0 0;
        }
        .doc-location {
            font-size: 8px;
            font-weight: 600;
            color: #64748b;
            margin-top: 1px;
        }
        .decorative-line {
            height: 3px;
            background-color: #005ea2;
            width: 100%;
            margin-bottom: 12px;
            border-radius: 2px;
        }

        /* Metadata Badges Bar */
        .meta-bar {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
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

        /* Section Headings */
        .section-header {
            margin-top: 10px;
            margin-bottom: 6px;
            border-bottom: 1.5px solid #cbd5e1;
            padding-bottom: 3px;
        }
        .section-title {
            font-size: 10.5px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Comparison Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 9px;
        }
        .data-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 8px;
            padding: 6px 8px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }
        .data-table td {
            padding: 5.5px 8px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Info Boxes */
        .box-emerald {
            background-color: #f0fdf4;
            border: 1px solid #86efac;
            border-left: 4px solid #16a34a;
            padding: 8px 10px;
            margin-bottom: 8px;
            border-radius: 4px;
        }
        .box-rose {
            background-color: #fff1f2;
            border: 1px solid #fecdd3;
            border-left: 4px solid #e11d48;
            padding: 8px 10px;
            margin-bottom: 8px;
            border-radius: 4px;
        }
        .box-sky {
            background-color: #f0f9ff;
            border: 1px solid #bae6fd;
            border-left: 4px solid #0284c7;
            padding: 8px 10px;
            margin-bottom: 8px;
            border-radius: 4px;
        }

        .badge-good {
            background-color: #dcfce7;
            color: #166534;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 8px;
        }
        .badge-bad {
            background-color: #ffe4e6;
            color: #9f1239;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 8px;
        }

        /* Sign-off footer */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            page-break-inside: avoid;
        }
        .footer-table td {
            vertical-align: top;
            border: none;
            padding: 0;
            font-size: 8.5px;
        }
        .sign-box {
            width: 190px;
            text-align: center;
            border: 1px dashed #cbd5e1;
            padding: 8px;
            background-color: #f8fafc;
            border-radius: 6px;
        }
        .sign-space {
            height: 40px;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT MODERN -->
    <table class="header-table">
        <tr>
            @if($logoFlydine)
                <td style="width: 125px;">
                    <img src="{{ $logoFlydine }}" style="max-height: 42px; max-width: 120px;" alt="FlyDine Logo">
                </td>
            @endif
            <td style="text-align: {{ $logoAngkasaPura ? 'center' : 'left' }};">
                <div class="doc-title">FlyDine Juanda • Executive Dashboard</div>
                <div class="doc-subtitle">Panduan & Metodologi Model Simulasi Target (What-If Analysis)</div>
                <div class="doc-location">Bandara Internasional Juanda Surabaya (SUB) • Manajemen Komersial & Operasional F&B</div>
            </td>
            @if($logoAngkasaPura)
                <td style="width: 125px; text-align: right;">
                    <img src="{{ $logoAngkasaPura }}" style="max-height: 38px; max-width: 120px;" alt="Angkasa Pura Logo">
                </td>
            @endif
        </tr>
    </table>

    <div class="decorative-line"></div>

    <!-- METADATA BAR -->
    <table class="meta-bar">
        <tr>
            <td style="width: 25%;"><strong>Horizon Evaluasi:</strong> {{ $horizon === 'weekly' ? 'Mingguan (Minggu Ini → Depan)' : 'Bulanan (Bulan Ini → Depan)' }}</td>
            <td style="width: 25%;"><strong>Mitra F&B:</strong> {{ $tenant ? $tenant->name : 'Seluruh Mitra F&B (Konsolidasi)' }}</td>
            <td style="width: 25%;"><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</td>
            <td style="width: 25%; text-align: right;"><strong>Klasifikasi:</strong> <span style="color: #005ea2; font-weight: bold;">Dokumen Resmi Eksekutif</span></td>
        </tr>
    </table>

    <!-- 1. LATAR BELAKANG & TUJUAN BISNIS -->
    <div class="section-header">
        <span class="section-title">1. Filosofi Bisnis & Keterikatan Waktu Saji di Bandara</span>
    </div>
    <p style="margin: 3px 0 8px 0; color: #334155;">
        Karakteristik transaksi F&B di bandara sangat berbeda dari restoran di perkotaan karena adanya <strong>panggilan boarding pesawat (boarding time window)</strong>. Penumpang memiliki batas waktu toleransi yang sangat ketat. Model simulasi ini memetakan hubungan kausal langsung antara <strong>Omset</strong>, <strong>Trafik Penumpang</strong>, dan <strong>Waktu Saji Dapur (SLA)</strong>.
    </p>

    <!-- BOX 2 SKENARIO -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
        <tr>
            <td style="width: 49%; vertical-align: top; padding-right: 5px;">
                <div class="box-emerald">
                    <strong style="color: #166534; font-size: 9.5px; display: block; margin-bottom: 3px;">
                        🟢 Skenario Baik (Target Prima / Best Case)
                    </strong>
                    <div style="color: #334155; font-size: 8.5px;">
                        Jika waktu saji dijaga kilat (<strong>≤ 9.5 Menit/Pesanan</strong>), penumpang merasa sangat aman untuk memesan makanan sebelum boarding. Tidak ada pesanan batal, tingkat konversi belanja naik hingga <strong>~55%</strong>, dan omset mencapai target optimal.
                    </div>
                </div>
            </td>
            <td style="width: 49%; vertical-align: top; padding-left: 5px;">
                <div class="box-rose">
                    <strong style="color: #9f1239; font-size: 9.5px; display: block; margin-bottom: 3px;">
                        🔴 Skenario Buruk (Risiko Bottleneck / Worst Case)
                    </strong>
                    <div style="color: #334155; font-size: 8.5px;">
                        Jika waktu saji molor melewati batas SOP (<strong>> 15 Menit/Pesanan</strong>), penumpang yang mendengar panggilan pesawat akan panik dan membatalkan pesanan. Hal ini memicu omset hangus (lost revenue), lonjakan komplain, dan kerugian bahan baku.
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- 2. MATRIKS PERBANDINGAN SKENARIO (HEAD-TO-HEAD TABLE) -->
    <div class="section-header">
        <span class="section-title">2. Matriks Komparasi Target Skenario Aktif</span>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 28%;">Indikator Kinerja Utama (KPI)</th>
                <th style="width: 24%; background-color: #e2e8f0;">Kondisi Acuan Saat Ini</th>
                <th style="width: 24%; background-color: #dcfce7; color: #166534;">🟢 Target Skenario Baik (+{{ $goodGrowthPct }}%)</th>
                <th style="width: 24%; background-color: #ffe4e6; color: #9f1239;">🔴 Risiko Skenario Buruk (-{{ $badDropPct }}%)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Proyeksi Omset Total</strong></td>
                <td style="background-color: #f8fafc; font-weight: bold;">Rp {{ number_format($baseRevenue, 0, ',', '.') }}</td>
                <td style="color: #166534; font-weight: bold;">Rp {{ number_format($goodRevenue, 0, ',', '.') }} <span class="badge-good">+Rp {{ number_format($goodRevenueDelta, 0, ',', '.') }}</span></td>
                <td style="color: #9f1239; font-weight: bold;">Rp {{ number_format($badRevenue, 0, ',', '.') }} <span class="badge-bad">-Rp {{ number_format($badRevenueDelta, 0, ',', '.') }}</span></td>
            </tr>
            <tr>
                <td><strong>Trafik Pengunjung (Pax)</strong></td>
                <td style="background-color: #f8fafc;">{{ number_format($baseVisitors, 0, ',', '.') }} orang</td>
                <td style="color: #166534; font-weight: bold;">{{ number_format($goodVisitors, 0, ',', '.') }} orang (+{{ number_format($goodVisitorsDelta, 0, ',', '.') }})</td>
                <td style="color: #9f1239; font-weight: bold;">{{ number_format($badVisitors, 0, ',', '.') }} orang (-{{ number_format($badVisitorsDelta, 0, ',', '.') }})</td>
            </tr>
            <tr>
                <td><strong>Estimasi Transaksi Selesai</strong></td>
                <td style="background-color: #f8fafc;">~{{ number_format($estBuyers, 0, ',', '.') }} pesanan</td>
                <td style="color: #166534; font-weight: bold;">{{ number_format($goodOrders, 0, ',', '.') }} pesanan (Konversi 55%)</td>
                <td style="color: #9f1239; font-weight: bold;">{{ number_format($badOrders, 0, ',', '.') }} pesanan (Konversi turun 40%)</td>
            </tr>
            <tr>
                <td><strong>Rata-Rata Waktu Saji (SLA)</strong></td>
                <td style="background-color: #f8fafc;">{{ number_format($baseSla, 1) }} Menit / Order</td>
                <td style="color: #166534; font-weight: bold;">{{ number_format($goodSla, 1) }} Menit (Kilat & Aman Boarding)</td>
                <td style="color: #9f1239; font-weight: bold;">{{ number_format($badSla, 1) }} Menit (Bottleneck & Delay)</td>
            </tr>
            <tr>
                <td><strong>Tingkat Pesanan Batal</strong></td>
                <td style="background-color: #f8fafc;">~5.0% (Standar Industri)</td>
                <td style="color: #166534; font-weight: bold;">&lt; 1.0% (Terkendali Aman)</td>
                <td style="color: #9f1239; font-weight: bold;">{{ number_format($badCancelRate, 1) }}% (Potensi rugi Rp {{ number_format($badLostRevenue, 0, ',', '.') }})</td>
            </tr>
            <tr>
                <td><strong>Kepatuhan Standar Juanda (≤ 15m)</strong></td>
                <td style="background-color: #f8fafc;">{{ $baseSla <= 15 ? 'Memenuhi Standar' : 'Melebihi Batas SOP' }}</td>
                <td style="color: #166534; font-weight: bold;">100% Memenuhi Standar</td>
                <td style="color: #9f1239; font-weight: bold;">{{ number_format($badCompliance, 1) }}% (Est. {{ $badComplaints }} komplain)</td>
            </tr>
        </tbody>
    </table>

    <!-- 3. METODOLOGI & RUMUS MATEMATIKA LENGKAP -->
    <div class="section-header">
        <span class="section-title">3. Metodologi & Transparansi Rumus Matematika</span>
    </div>
    <div class="box-sky" style="margin-bottom: 10px;">
        <table style="width: 100%; border-collapse: collapse; font-size: 8.5px;">
            <tr>
                <td style="width: 33%; vertical-align: top; padding-right: 8px;">
                    <strong style="color: #0369a1; display: block; margin-bottom: 2px;">A. Proyeksi Omset:</strong>
                    <code>Omset Baik = Omset Awal × (1 + Pertumbuhan%)</code><br>
                    <code>Omset Buruk = Omset Awal × (1 - Penurunan%)</code><br>
                    <span style="color: #64748b;">Menghitung batas potensi pendapatan berdasarkan elastisitas volume beli penumpang.</span>
                </td>
                <td style="width: 34%; vertical-align: top; padding-right: 8px;">
                    <strong style="color: #0369a1; display: block; margin-bottom: 2px;">B. Nilai Rata-Rata Belanja (AOV):</strong>
                    <code>AOV = Omset Awal / (Pengunjung × 50%)</code><br>
                    <span style="color: #64748b;">Estimasi konversi belanja normal adalah 50% dari pengunjung transit yang singgah di area tenant.</span>
                </td>
                <td style="width: 33%; vertical-align: top;">
                    <strong style="color: #0369a1; display: block; margin-bottom: 2px;">C. Standar Waktu Saji (SLA):</strong>
                    <code>SLA Baik = Min(9.5, SLA Awal - 2.5 mnt)</code><br>
                    <code>SLA Buruk = Max(16.0, SLA Awal + 4.5 mnt)</code><br>
                    <span style="color: #64748b;">Batas SOP Juanda adalah 15 menit. Lebih dari 15 menit memicu pembatalan akibat last call boarding.</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- 4. PANDUAN LANGKAH KERJA OPERASIONAL -->
    <div class="section-header">
        <span class="section-title">4. Panduan Langkah Kerja Operasional Tenant F&B</span>
    </div>
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 8.5px;">
        <tr>
            <td style="width: 50%; vertical-align: top; padding-right: 6px;">
                <div style="background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px; padding: 7px 9px;">
                    <strong style="color: #166534; display: block; margin-bottom: 4px;">✅ Langkah Kerja Meraih Target Baik:</strong>
                    <ul style="margin: 0; padding-left: 14px; color: #334155; line-height: 1.4;">
                        <li><strong>Fast-Prep Station:</strong> Siapkan bahan baku siap saji 30 menit sebelum jadwal penerbangan padat (peak hours Juanda: 06.00-09.00 & 16.00-19.00 WIB).</li>
                        <li><strong>Runner Pengantar Gate:</strong> Siagakan staf runner untuk mengantar pesanan langsung ke ruang tunggu boarding bagi penumpang terburu-buru.</li>
                        <li><strong>Strategi Bundling:</strong> Tawarkan menu paket kombo (makanan + minuman kemasan cepat saji) untuk meningkatkan rata-rata belanja (AOV).</li>
                    </ul>
                </div>
            </td>
            <td style="width: 50%; vertical-align: top; padding-left: 6px;">
                <div style="background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px; padding: 7px 9px;">
                    <strong style="color: #9f1239; display: block; margin-bottom: 4px;">⚠️ Langkah Mitigasi Mencegah Skenario Buruk:</strong>
                    <ul style="margin: 0; padding-left: 14px; color: #334155; line-height: 1.4;">
                        <li><strong>Batasi Menu Masak Lama:</strong> Nonaktifkan sementara menu dengan durasi masak &gt; 12 menit saat antrean kasir melebihi 5 antrean.</li>
                        <li><strong>Transparansi Estimasi Waktu:</strong> Kasir wajib menginformasikan estimasi waktu saji secara jujur sebelum pembayaran diproses.</li>
                        <li><strong>Grab-and-Go Siap Bawa:</strong> Sediakan etalase produk siap bawa di kasir untuk menyelamatkan calon transaksi yang batal memesan menu masak.</li>
                    </ul>
                </div>
            </td>
        </tr>
    </table>

    <!-- TANDA TANGAN / PENGESAHAN DOKUMEN EKSEKUTIF -->
    <table class="footer-table">
        <tr>
            <td style="width: 60%; color: #64748b;">
                <strong>Catatan Sistem Informasi Eksekutif (FlyDine Juanda):</strong><br>
                Model prediktif ini dirancang berdasarkan standar operasional bandara komersial untuk membantu manajemen dan mitra tenant mengambil keputusan berbasis data (Data-Driven Decision Making). Dokumen ini sah dan dapat dijadikan acuan SOP operasional bulanan.
            </td>
            <td style="width: 40%; text-align: right;">
                <table style="float: right;" class="sign-box">
                    <tr>
                        <td>
                            <strong style="color: #0f172a;">Disetujui Oleh:</strong><br>
                            <span style="color: #64748b; font-size: 8px;">Admin Ops Komersial & SLA</span>
                            <div class="sign-space"></div>
                            <strong style="color: #005ea2; text-decoration: underline;">{{ auth()->user()->name ?? 'Admin Ops Komersial' }}</strong><br>
                            <span style="color: #64748b; font-size: 7.5px;">Bandara Internasional Juanda</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>
