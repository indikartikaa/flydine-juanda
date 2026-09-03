<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Eksekutif - FlyDine Juanda</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1, h2 { text-align: center; color: #005ea2; }
        .header { margin-bottom: 20px; border-bottom: 2px solid #005ea2; padding-bottom: 10px; }
        .meta { text-align: center; font-style: italic; color: #666; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8fafc; font-weight: bold; color: #005ea2; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .section-title { font-size: 14px; font-weight: bold; margin-bottom: 10px; color: #333; border-bottom: 1px solid #ccc; padding-bottom: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Sistem Informasi Eksekutif (SIE)</h1>
        <h2>FlyDine - Bandara Internasional Juanda</h2>
    </div>

    <div class="meta">
        Laporan Periode: {{ $startDate->format('d M Y') }} s/d {{ $endDate->format('d M Y') }} ({{ $days }} Hari)<br>
        Dicetak pada: {{ now()->format('d M Y H:i') }}
    </div>

    <!-- Ringkasan Eksekutif -->
    <div class="section-title">Ringkasan Eksekutif</div>
    @php
        $totalOrders = $statusDistribution->sum('total');
        $canceled = $statusDistribution->where('status', 'dibatalkan')->first()->total ?? 0;
        $cancelRate = $totalOrders > 0 ? round(($canceled / $totalOrders) * 100, 1) : 0;
        $avgSLA = $slaPerformance->avg('avg_minutes');
    @endphp
    <table>
        <tr>
            <th>Total Volume Pesanan</th>
            <th>Tingkat Pembatalan (Cancel Rate)</th>
            <th>Rata-rata Waktu Kesiapan (SLA)</th>
        </tr>
        <tr>
            <td class="text-center">{{ $totalOrders }} Pesanan</td>
            <td class="text-center">{{ $cancelRate }}%</td>
            <td class="text-center">{{ round($avgSLA, 1) }} Menit</td>
        </tr>
    </table>

    <!-- Kinerja SLA Tenant -->
    <div class="section-title">Kinerja Waktu Pelayanan per Tenant (SLA)</div>
    <table>
        <thead>
            <tr>
                <th width="10%">No</th>
                <th width="60%">Nama Tenant</th>
                <th width="30%" class="text-right">Rata-rata Waktu (Menit)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($slaPerformance as $index => $sla)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $sla->name }}</td>
                <td class="text-right">{{ round($sla->avg_minutes, 1) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">Tidak ada data pesanan pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Distribusi Status -->
    <div class="section-title">Distribusi Status Pesanan</div>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th class="text-right">Jumlah</th>
                <th class="text-right">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @foreach($statusDistribution as $stat)
            <tr>
                <td style="text-transform: capitalize;">{{ $stat->status }}</td>
                <td class="text-right">{{ $stat->total }}</td>
                <td class="text-right">{{ $totalOrders > 0 ? round(($stat->total / $totalOrders) * 100, 1) : 0 }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
