<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DailyReportEtl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:etl-daily 
                            {--date= : Tanggal spesifik proses ETL (format: YYYY-MM-DD)} 
                            {--from= : Tanggal awal rentang backfill (format: YYYY-MM-DD)} 
                            {--to= : Tanggal akhir rentang backfill (format: YYYY-MM-DD)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menjalankan proses ETL harian agregasi pesanan per tenant untuk Executive Dashboard';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateInput = $this->option('date');
        $fromInput = $this->option('from');
        $toInput = $this->option('to');

        $datesToProcess = [];

        if ($dateInput) {
            try {
                $datesToProcess[] = Carbon::parse($dateInput, 'Asia/Jakarta')->format('Y-m-d');
            } catch (\Throwable $e) {
                $this->error("Format parameter --date tidak valid. Gunakan format YYYY-MM-DD.");
                return 1;
            }
        } elseif ($fromInput || $toInput) {
            if (!$fromInput) $fromInput = $toInput;
            if (!$toInput) $toInput = $fromInput;

            try {
                $from = Carbon::parse($fromInput, 'Asia/Jakarta')->startOfDay();
                $to = Carbon::parse($toInput, 'Asia/Jakarta')->startOfDay();
            } catch (\Throwable $e) {
                $this->error("Format parameter --from atau --to tidak valid. Gunakan format YYYY-MM-DD.");
                return 1;
            }

            if ($from->gt($to)) {
                $temp = $from;
                $from = $to;
                $to = $temp;
            }

            $current = $from->copy();
            while ($current->lte($to)) {
                $datesToProcess[] = $current->format('Y-m-d');
                $current->addDay();
            }
        } else {
            // Default scheduler harian: memproses ringkasan hari kemarin (zona WIB)
            $datesToProcess[] = Carbon::yesterday('Asia/Jakarta')->format('Y-m-d');
        }

        $this->info(sprintf("Memulai proses ETL harian untuk %d tanggal...", count($datesToProcess)));

        $totalProcessedAll = 0;
        $totalDiscardedAll = 0;
        $totalLoadedAll = 0;

        foreach ($datesToProcess as $summaryDate) {
            [$processed, $discarded, $loaded] = $this->processDate($summaryDate);
            $totalProcessedAll += $processed;
            $totalDiscardedAll += $discarded;
            $totalLoadedAll += $loaded;
        }

        if (count($datesToProcess) > 1) {
            $this->info(sprintf(
                "Selesai semua backfill ETL! Total Diproses: %d | Total Dibuang: %d | Total Dimuat: %d",
                $totalProcessedAll,
                $totalDiscardedAll,
                $totalLoadedAll
            ));
        }

        return 0;
    }

    /**
     * Memproses satu tanggal summary (Extract, Transform & Clean, Load).
     */
    protected function processDate(string $summaryDate): array
    {
        $startOfDay = Carbon::parse($summaryDate, 'Asia/Jakarta')->startOfDay();
        $endOfDay = Carbon::parse($summaryDate, 'Asia/Jakarta')->endOfDay();

        // 1. EXTRACT: orders per tanggal ordered_at (zona WIB) join tenants
        // Menggunakan leftJoin agar anomali (misal tenant_id tidak valid) dapat diekstrak untuk dibersihkan/dibuang
        $rawOrders = DB::table('orders')
            ->leftJoin('tenants', 'orders.tenant_id', '=', 'tenants.id')
            ->select(
                'orders.id',
                'orders.order_code',
                'orders.tenant_id',
                'orders.status',
                'orders.is_paid',
                'orders.total_amount',
                'orders.ordered_at',
                'orders.ready_at',
                'tenants.id as valid_tenant_id'
            )
            ->where(function ($q) use ($startOfDay, $endOfDay) {
                $q->whereBetween('orders.ordered_at', [$startOfDay, $endOfDay])
                  ->orWhere(function ($sub) use ($startOfDay, $endOfDay) {
                      $sub->whereNull('orders.ordered_at')
                          ->whereBetween('orders.created_at', [$startOfDay, $endOfDay]);
                  });
            })
            ->get();

        $processedCount = $rawOrders->count();
        $discardedCount = 0;
        $tenantBuckets = [];

        // 2. TRANSFORM + CLEANING
        foreach ($rawOrders as $order) {
            // Aturan cleaning: abaikan order tanpa tenant_id atau ordered_at
            if (empty($order->tenant_id) || empty($order->valid_tenant_id) || empty($order->ordered_at)) {
                $discardedCount++;
                continue;
            }

            $tenantId = (int) $order->tenant_id;

            if (!isset($tenantBuckets[$tenantId])) {
                $tenantBuckets[$tenantId] = [
                    'summary_date' => $summaryDate,
                    'tenant_id' => $tenantId,
                    'total_orders' => 0,
                    'paid_orders' => 0,
                    'cancelled_orders' => 0,
                    'total_revenue' => 0.0,
                    'ready_orders' => 0,
                    'sla_compliant_orders' => 0,
                    'total_sla_minutes' => 0.0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Aturan cleaning: nilai null jadi 0
            $tenantBuckets[$tenantId]['total_orders']++;

            $isPaid = (bool) ($order->is_paid ?? 0);
            $totalAmount = (float) ($order->total_amount ?? 0);

            // Aturan transform: omset hanya dari is_paid = true
            if ($isPaid) {
                $tenantBuckets[$tenantId]['paid_orders']++;
                $tenantBuckets[$tenantId]['total_revenue'] += $totalAmount;
            }

            $status = $order->status ?? '';
            if ($status === 'dibatalkan') {
                $tenantBuckets[$tenantId]['cancelled_orders']++;
            }

            // Aturan cleaning: abaikan ready_at yang lebih awal dari ordered_at; SLA patuh jika selisih menit <= 15
            if (!empty($order->ready_at)) {
                $orderedAt = Carbon::parse($order->ordered_at);
                $readyAt = Carbon::parse($order->ready_at);

                if ($readyAt->gte($orderedAt)) {
                    $tenantBuckets[$tenantId]['ready_orders']++;
                    $diffMinutes = $orderedAt->diffInMinutes($readyAt);
                    $tenantBuckets[$tenantId]['total_sla_minutes'] += $diffMinutes;

                    if ($diffMinutes <= 15) {
                        $tenantBuckets[$tenantId]['sla_compliant_orders']++;
                    }
                }
                // Jika ready_at < ordered_at, ready_at diabaikan (tidak dihitung sebagai order siap/SLA)
            }
        }

        // 3. LOAD: upsert ke daily_tenant_summaries, idempotent
        $upsertData = [];
        foreach ($tenantBuckets as $tId => $bucket) {
            $avgSla = $bucket['ready_orders'] > 0
                ? round($bucket['total_sla_minutes'] / $bucket['ready_orders'], 2)
                : null;

            $upsertData[] = [
                'summary_date' => $bucket['summary_date'],
                'tenant_id' => $bucket['tenant_id'],
                'total_orders' => (int) $bucket['total_orders'],
                'paid_orders' => (int) $bucket['paid_orders'],
                'cancelled_orders' => (int) $bucket['cancelled_orders'],
                'total_revenue' => round($bucket['total_revenue'], 2),
                'ready_orders' => (int) $bucket['ready_orders'],
                'sla_compliant_orders' => (int) $bucket['sla_compliant_orders'],
                'avg_sla_minutes' => $avgSla,
                'created_at' => $bucket['created_at'],
                'updated_at' => $bucket['updated_at'],
            ];
        }

        $loadedCount = count($upsertData);

        if ($loadedCount > 0) {
            DB::table('daily_tenant_summaries')->upsert(
                $upsertData,
                ['summary_date', 'tenant_id'],
                [
                    'total_orders',
                    'paid_orders',
                    'cancelled_orders',
                    'total_revenue',
                    'ready_orders',
                    'sla_compliant_orders',
                    'avg_sla_minutes',
                    'updated_at',
                ]
            );
        }

        // 4. LOG: Tulis log ringkas (jumlah baris diproses, dibuang, dimuat)
        $logMsg = sprintf(
            'ETL Daily Summary [%s] - Diproses: %d, Dibuang: %d, Dimuat: %d',
            $summaryDate,
            $processedCount,
            $discardedCount,
            $loadedCount
        );

        $this->info($logMsg);
        Log::info($logMsg);

        return [$processedCount, $discardedCount, $loadedCount];
    }
}
