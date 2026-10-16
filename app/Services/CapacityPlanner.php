<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CapacityPlanner
{
    /**
     * Tentukan status utilisasi berdasarkan persentase utilisasi.
     * Batas:
     * - < 70%   : AMAN
     * - 70-90%  : WASPADA
     * - 90-100% : KRITIS
     * - > 100%  : OVERLOAD
     */
    public static function determineStatus(?float $utilization): string
    {
        if ($utilization === null) {
            return 'Data belum cukup';
        }

        if ($utilization < 70.0) {
            return 'AMAN';
        }

        if ($utilization <= 90.0) {
            return 'WASPADA';
        }

        if ($utilization <= 100.0) {
            return 'KRITIS';
        }

        return 'OVERLOAD';
    }

    /**
     * Hitung staf minimum agar utilisasi <= 80%.
     * Utilisasi = lambda / (staf * 60 / waktu_saji) * 100 <= 80
     * staf >= (lambda * waktu_saji) / 48
     */
    public static function calculateMinimumStaff(?float $projectedLambda, ?float $serviceMinutes): ?int
    {
        if ($projectedLambda === null || $serviceMinutes === null || $serviceMinutes <= 0) {
            return null;
        }

        if ($projectedLambda <= 0) {
            return 1;
        }

        $needed = ($projectedLambda * $serviceMinutes) / 48.0;
        return max(1, (int) ceil($needed));
    }

    /**
     * Hitung simulasi kapasitas untuk kumpulan tenant atau tenant tunggal.
     *
     * @param Carbon|string $startDate
     * @param Carbon|string $endDate
     * @param int|null $tenantId
     * @param float $orderGrowthPct (-50 sampai +200)
     * @param int $staffCount (1 sampai 10)
     * @param float|null $targetSlaMinutes
     * @return array
     */
    public function calculate(
        $startDate,
        $endDate,
        ?int $tenantId = null,
        float $orderGrowthPct = 0.0,
        int $staffCount = 1,
        ?float $targetSlaMinutes = null
    ): array {
        $start = $startDate instanceof Carbon ? $startDate->copy()->startOfDay() : Carbon::parse($startDate)->startOfDay();
        $end = $endDate instanceof Carbon ? $endDate->copy()->endOfDay() : Carbon::parse($endDate)->endOfDay();

        if ($start->gt($end)) {
            $temp = $start;
            $start = $end->copy()->startOfDay();
            $end = $temp->copy()->endOfDay();
        }

        $days = max(1, (int) round($start->copy()->startOfDay()->diffInDays($end->copy()->startOfDay())) + 1);

        $isSqlite = DB::getDriverName() === 'sqlite';
        $diffMinutesSql = $isSqlite
            ? '((strftime(\'%s\', ready_at) - strftime(\'%s\', ordered_at)) / 60)'
            : 'TIMESTAMPDIFF(MINUTE, ordered_at, ready_at)';
        $hourSql = $isSqlite
            ? "CAST(strftime('%H', ordered_at) AS INTEGER)"
            : "HOUR(ordered_at)";

        // Ambil semua tenant aktif
        $allTenants = Tenant::where('is_active', true)->orderBy('name')->get();

        $tenantsResults = [];

        foreach ($allTenants as $tenant) {
            // 1. Pesanan non-dibatalkan dalam periode
            $validOrdersQuery = Order::where('tenant_id', $tenant->id)
                ->whereBetween('ordered_at', [$start, $end])
                ->where('status', '!=', 'dibatalkan');

            $validOrdersCount = (int) (clone $validOrdersQuery)->count();

            // 2. Pesanan valid untuk SLA (ready_at valid >= ordered_at)
            $slaQuery = (clone $validOrdersQuery)
                ->whereNotNull('ready_at')
                ->whereRaw('ready_at >= ordered_at');

            $slaOrdersCount = (int) (clone $slaQuery)->count();

            $avgServiceMinutes = null;
            if ($slaOrdersCount > 0) {
                $avgServiceMinutes = round(
                    (float) ((clone $slaQuery)->selectRaw("AVG({$diffMinutesSql}) as avg_m")->value('avg_m') ?? 0),
                    1
                );
            }

            // 3. Jam tersibuk tenant (beban puncak lambda0)
            $peakHourOrdersCount = 0;
            $peakHour = null;
            if ($validOrdersCount > 0) {
                $peakRow = (clone $validOrdersQuery)
                    ->selectRaw("{$hourSql} as order_hour, COUNT(*) as cnt")
                    ->groupBy('order_hour')
                    ->orderByDesc('cnt')
                    ->first();

                if ($peakRow) {
                    $peakHour = (int) $peakRow->order_hour;
                    $peakHourOrdersCount = (int) $peakRow->cnt;
                }
            }

            // Lambda0: beban puncak (pesanan per jam) = pesanan jam tersibuk / jumlah hari
            $lambda0 = round($peakHourOrdersCount / $days, 2);

            // Cek kriteria kecukupan data:
            // "Jika pesanan valid tenant kurang dari 10 atau S0 tidak ada, tampilkan 'Data belum cukup' dan JANGAN mengisi angka karangan"
            $hasSufficientData = ($validOrdersCount >= 10 && $avgServiceMinutes !== null && $avgServiceMinutes > 0);

            if (!$hasSufficientData) {
                $tenantsResults[] = [
                    'tenant_id' => $tenant->id,
                    'tenant_name' => $tenant->name,
                    'tenant_code' => $tenant->tenant_code ?? ('T-' . $tenant->id),
                    'category' => $tenant->category ?? 'Lainnya',
                    'floor_location' => $tenant->floor_location ?? '-',
                    'zone' => $tenant->zone ?? '-',
                    'has_sufficient_data' => false,
                    'valid_orders_count' => $validOrdersCount,
                    'sla_orders_count' => $slaOrdersCount,
                    'peak_hour' => $peakHour,
                    'peak_hour_formatted' => $peakHour !== null ? sprintf('%02d:00 - %02d:00', $peakHour, ($peakHour + 1) % 24) : '-',
                    'lambda_0' => null,
                    'service_minutes_0' => null,
                    'lambda_projected' => null,
                    'capacity_before' => null,
                    'utilization_before' => null,
                    'target_service_minutes' => null,
                    'capacity_new' => null,
                    'utilization_new' => null,
                    'capacity_remaining' => null,
                    'status' => 'Data belum cukup',
                    'min_staff_needed' => null,
                ];
                continue;
            }

            // Hitung metrik kapasitas untuk tenant yang datanya cukup:
            // Skenario baseline: staf 1, waktu saji S0
            $capacityBefore = round(60.0 / $avgServiceMinutes, 2);
            $utilizationBefore = $capacityBefore > 0
                ? round(($lambda0 / $capacityBefore) * 100.0, 1)
                : 0.0;

            // Proyeksi kenaikan beban: lambda_projected = lambda0 * (1 + kenaikan)
            $lambdaProjected = round($lambda0 * (1.0 + ($orderGrowthPct / 100.0)), 2);

            // Waktu saji target: jika diisi gunakan targetSlaMinutes, jika null gunakan S0
            $effectiveServiceMinutes = ($targetSlaMinutes !== null && $targetSlaMinutes > 0)
                ? (float) $targetSlaMinutes
                : $avgServiceMinutes;

            // Kapasitas baru = staf * 60 / waktu_saji
            $capacityNew = $effectiveServiceMinutes > 0
                ? round(($staffCount * 60.0) / $effectiveServiceMinutes, 2)
                : 0.0;

            // Utilisasi sesudah = (lambda_projected / capacity_new) * 100
            $utilizationNew = $capacityNew > 0
                ? round(($lambdaProjected / $capacityNew) * 100.0, 1)
                : 0.0;

            // Sisa kapasitas = capacity_new - lambda_projected
            $capacityRemaining = round($capacityNew - $lambdaProjected, 2);

            // Status utilisasi
            $status = self::determineStatus($utilizationNew);

            // Staf minimum agar utilisasi <= 80%
            $minStaffNeeded = self::calculateMinimumStaff($lambdaProjected, $effectiveServiceMinutes);

            $tenantsResults[] = [
                'tenant_id' => $tenant->id,
                'tenant_name' => $tenant->name,
                'tenant_code' => $tenant->tenant_code ?? ('T-' . $tenant->id),
                'category' => $tenant->category ?? 'Lainnya',
                'floor_location' => $tenant->floor_location ?? '-',
                'zone' => $tenant->zone ?? '-',
                'has_sufficient_data' => true,
                'valid_orders_count' => $validOrdersCount,
                'sla_orders_count' => $slaOrdersCount,
                'peak_hour' => $peakHour,
                'peak_hour_formatted' => sprintf('%02d:00 - %02d:00', $peakHour, ($peakHour + 1) % 24),
                'lambda_0' => $lambda0,
                'service_minutes_0' => $avgServiceMinutes,
                'lambda_projected' => $lambdaProjected,
                'capacity_before' => $capacityBefore,
                'utilization_before' => $utilizationBefore,
                'target_service_minutes' => $effectiveServiceMinutes,
                'capacity_new' => $capacityNew,
                'utilization_new' => $utilizationNew,
                'capacity_remaining' => $capacityRemaining,
                'status' => $status,
                'min_staff_needed' => $minStaffNeeded,
            ];
        }

        // Urutkan semua tenant dari utilisasi sesudah tertinggi,
        // lalu tenant dengan data tidak cukup ditempatkan di bawah
        usort($tenantsResults, function ($a, $b) {
            if ($a['has_sufficient_data'] && !$b['has_sufficient_data']) return -1;
            if (!$a['has_sufficient_data'] && $b['has_sufficient_data']) return 1;
            if (!$a['has_sufficient_data'] && !$b['has_sufficient_data']) return strcmp($a['tenant_name'], $b['tenant_name']);
            return ($b['utilization_new'] <=> $a['utilization_new']);
        });

        // Top 10 Tenant untuk Chart.js (hanya yang memiliki data cukup)
        $sufficientTenants = array_values(array_filter($tenantsResults, fn($t) => $t['has_sufficient_data']));
        $top10Tenants = array_slice($sufficientTenants, 0, 10);

        $chartLabels = array_map(fn($t) => $t['tenant_name'], $top10Tenants);
        $chartIds = array_map(fn($t) => $t['tenant_id'], $top10Tenants);
        $chartBefore = array_map(fn($t) => $t['utilization_before'], $top10Tenants);
        $chartAfter = array_map(fn($t) => $t['utilization_new'], $top10Tenants);

        // Jika filter tenant tertentu dipilih, ambil tenant tersebut
        $selectedTenantData = null;
        if ($tenantId) {
            foreach ($tenantsResults as $t) {
                if ($t['tenant_id'] == $tenantId) {
                    $selectedTenantData = $t;
                    break;
                }
            }
        }

        // Ringkasan Agregat (Summary Cards)
        $statusCounts = [
            'AMAN' => 0,
            'WASPADA' => 0,
            'KRITIS' => 0,
            'OVERLOAD' => 0,
            'INSUFFICIENT' => 0,
        ];

        $sumUtilBefore = 0.0;
        $sumUtilAfter = 0.0;
        $sumCapacityNew = 0.0;
        $countSufficient = count($sufficientTenants);

        foreach ($tenantsResults as $t) {
            if (!$t['has_sufficient_data']) {
                $statusCounts['INSUFFICIENT']++;
            } else {
                $statusCounts[$t['status']]++;
                $sumUtilBefore += $t['utilization_before'];
                $sumUtilAfter += $t['utilization_new'];
                $sumCapacityNew += $t['capacity_new'];
            }
        }

        $avgUtilBefore = $countSufficient > 0 ? round($sumUtilBefore / $countSufficient, 1) : 0.0;
        $avgUtilAfter = $countSufficient > 0 ? round($sumUtilAfter / $countSufficient, 1) : 0.0;

        // Target rekomendasi staf untuk tenant terpilih atau total maksimum
        $overallMinStaff = 1;
        if ($selectedTenantData && $selectedTenantData['has_sufficient_data']) {
            $overallMinStaff = $selectedTenantData['min_staff_needed'] ?? 1;
        } elseif (!empty($sufficientTenants)) {
            $overallMinStaff = max(array_column($sufficientTenants, 'min_staff_needed') ?: [1]);
        }

        return [
            'period' => [
                'start_date' => $start->format('Y-m-d'),
                'end_date' => $end->format('Y-m-d'),
                'days' => $days,
            ],
            'inputs' => [
                'tenant_id' => $tenantId,
                'order_growth_pct' => $orderGrowthPct,
                'staff_count' => $staffCount,
                'target_sla_minutes' => $targetSlaMinutes,
            ],
            'summary' => [
                'selected_tenant' => $selectedTenantData,
                'count_total_tenants' => count($allTenants),
                'count_sufficient' => $countSufficient,
                'avg_utilization_before' => $avgUtilBefore,
                'avg_utilization_after' => $avgUtilAfter,
                'total_capacity_new' => round($sumCapacityNew, 1),
                'status_distribution' => $statusCounts,
                'overall_status' => self::determineStatus($selectedTenantData ? ($selectedTenantData['utilization_new'] ?? null) : $avgUtilAfter),
                'min_staff_recommended' => $overallMinStaff,
            ],
            'top_10_chart' => [
                'labels' => $chartLabels,
                'tenant_ids' => $chartIds,
                'utilization_before' => $chartBefore,
                'utilization_after' => $chartAfter,
                'thresholds' => [
                    'safe' => 70,
                    'warning' => 90,
                ],
            ],
            'tenants' => $tenantsResults,
        ];
    }
}
