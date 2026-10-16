<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Tenant;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use App\Models\DailyTenantSummary;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DailyEtlReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_etl_daily_aggregates_correctly_is_idempotent_and_discards_order_without_tenant()
    {
        // 1. Arrange Master Data
        $tenantA = Tenant::create([
            'tenant_code' => 'T-KFC-01',
            'name' => 'KFC Terminal 1',
            'floor_location' => 'Lantai 1 Gate 3',
            'is_active' => true,
        ]);

        $tenantB = Tenant::create([
            'tenant_code' => 'T-SBX-01',
            'name' => 'Starbucks Terminal 1',
            'floor_location' => 'Lantai 1 Gate 5',
            'is_active' => true,
        ]);

        $customer = Customer::create([
            'phone_number' => '081234567890',
            'name' => 'Budi Passenger',
            'total_orders' => 0,
        ]);

        $targetDate = '2026-10-13';

        // 2. Arrange Orders for Tenant A on target date
        // Order 1: Paid, Selesai, Ready in 10 mins (<= 15 -> SLA compliant)
        Order::create([
            'order_code' => 'ORD-A-01',
            'tenant_id' => $tenantA->id,
            'customer_id' => $customer->id,
            'customer_name' => 'Budi Passenger',
            'status' => 'selesai',
            'is_paid' => true,
            'total_amount' => 50000.00,
            'ordered_at' => "{$targetDate} 10:00:00",
            'ready_at' => "{$targetDate} 10:10:00",
            'auto_cancel_at' => "{$targetDate} 10:45:00",
        ]);

        // Order 2: Paid, Selesai, Ready in 25 mins (> 15 -> Ready but NOT SLA compliant)
        Order::create([
            'order_code' => 'ORD-A-02',
            'tenant_id' => $tenantA->id,
            'customer_id' => $customer->id,
            'customer_name' => 'Budi Passenger',
            'status' => 'selesai',
            'is_paid' => true,
            'total_amount' => 30000.00,
            'ordered_at' => "{$targetDate} 11:00:00",
            'ready_at' => "{$targetDate} 11:25:00",
            'auto_cancel_at' => "{$targetDate} 11:45:00",
        ]);

        // Order 3: Unpaid (is_paid = false), Dibatalkan, ready_at is null
        Order::create([
            'order_code' => 'ORD-A-03',
            'tenant_id' => $tenantA->id,
            'customer_id' => $customer->id,
            'customer_name' => 'Budi Passenger',
            'status' => 'dibatalkan',
            'is_paid' => false,
            'total_amount' => 20000.00,
            'ordered_at' => "{$targetDate} 12:00:00",
            'ready_at' => null,
            'auto_cancel_at' => "{$targetDate} 12:15:00",
        ]);

        // Order 4: Paid, Selesai, but ready_at earlier than ordered_at (anomaly -> ready_at MUST BE IGNORED)
        Order::create([
            'order_code' => 'ORD-A-04',
            'tenant_id' => $tenantA->id,
            'customer_id' => $customer->id,
            'customer_name' => 'Budi Passenger',
            'status' => 'selesai',
            'is_paid' => true,
            'total_amount' => 40000.00,
            'ordered_at' => "{$targetDate} 14:00:00",
            'ready_at' => "{$targetDate} 13:50:00", // 10 minutes earlier than ordered_at
            'auto_cancel_at' => "{$targetDate} 14:45:00",
        ]);

        // 3. Arrange Orders for Tenant B on target date
        // Order 5: Paid, Selesai, Ready in 12 mins (<= 15 -> SLA compliant)
        Order::create([
            'order_code' => 'ORD-B-01',
            'tenant_id' => $tenantB->id,
            'customer_id' => $customer->id,
            'customer_name' => 'Budi Passenger',
            'status' => 'selesai',
            'is_paid' => true,
            'total_amount' => 75000.00,
            'ordered_at' => "{$targetDate} 09:00:00",
            'ready_at' => "{$targetDate} 09:12:00",
            'auto_cancel_at' => "{$targetDate} 09:45:00",
        ]);

        // 4. Arrange Order WITHOUT tenant (tenant_id = null)
        // Order 6: MUST BE DISCARDED (dibuang) by ETL data cleaning
        Order::create([
            'order_code' => 'ORD-ANOMALY-NO-TENANT',
            'tenant_id' => null,
            'customer_id' => $customer->id,
            'customer_name' => 'Anonim',
            'status' => 'selesai',
            'is_paid' => true,
            'total_amount' => 99000.00,
            'ordered_at' => "{$targetDate} 15:00:00",
            'ready_at' => "{$targetDate} 15:10:00",
            'auto_cancel_at' => "{$targetDate} 15:45:00",
        ]);

        // Order 7 on different date (should not be in 2026-10-13 summary)
        Order::create([
            'order_code' => 'ORD-OTHER-DAY',
            'tenant_id' => $tenantA->id,
            'customer_id' => $customer->id,
            'customer_name' => 'Budi Passenger',
            'status' => 'selesai',
            'is_paid' => true,
            'total_amount' => 15000.00,
            'ordered_at' => "2026-10-12 10:00:00",
            'ready_at' => "2026-10-12 10:10:00",
            'auto_cancel_at' => "2026-10-12 10:45:00",
        ]);

        // ==========================================
        // FIRST RUN: Eksekusi pertama command ETL
        // ==========================================
        $this->artisan('report:etl-daily', ['--date' => $targetDate])
            ->expectsOutputToContain("ETL Daily Summary [{$targetDate}] - Diproses: 6, Dibuang: 1, Dimuat: 2")
            ->assertSuccessful();

        // Verifikasi jumlah baris yang dimuat
        $this->assertEquals(2, DailyTenantSummary::where('summary_date', $targetDate)->count());

        // Verifikasi agregat Tenant A
        $summaryA = DailyTenantSummary::where('summary_date', $targetDate)
            ->where('tenant_id', $tenantA->id)
            ->first();

        $this->assertNotNull($summaryA);
        $this->assertEquals(4, $summaryA->total_orders, 'Total orders Tenant A harus 4 (Order 1, 2, 3, 4)');
        $this->assertEquals(3, $summaryA->paid_orders, 'Paid orders Tenant A harus 3 (Order 1, 2, 4)');
        $this->assertEquals(1, $summaryA->cancelled_orders, 'Cancelled orders Tenant A harus 1 (Order 3)');
        $this->assertEquals(120000.00, (float)$summaryA->total_revenue, 'Omset Tenant A harus 120.000 (hanya is_paid = true)');
        $this->assertEquals(2, $summaryA->ready_orders, 'Ready orders harus 2 (Order 1 & 2; Order 4 ready_at < ordered_at diabaikan)');
        $this->assertEquals(1, $summaryA->sla_compliant_orders, 'SLA compliant harus 1 (Order 1: 10m <= 15m; Order 2: 25m > 15m)');
        $this->assertEquals(17.50, (float)$summaryA->avg_sla_minutes, 'Rata-rata SLA Tenant A: (10 + 25) / 2 = 17.50 menit');

        // Verifikasi agregat Tenant B
        $summaryB = DailyTenantSummary::where('summary_date', $targetDate)
            ->where('tenant_id', $tenantB->id)
            ->first();

        $this->assertNotNull($summaryB);
        $this->assertEquals(1, $summaryB->total_orders);
        $this->assertEquals(1, $summaryB->paid_orders);
        $this->assertEquals(0, $summaryB->cancelled_orders);
        $this->assertEquals(75000.00, (float)$summaryB->total_revenue);
        $this->assertEquals(1, $summaryB->ready_orders);
        $this->assertEquals(1, $summaryB->sla_compliant_orders);
        $this->assertEquals(12.00, (float)$summaryB->avg_sla_minutes);

        // Verifikasi Order tanpa tenant dibuang dan tidak dimuat ke tabel summary
        $this->assertNull(DailyTenantSummary::whereNull('tenant_id')->first());
        $this->assertEquals(0, DailyTenantSummary::where('total_revenue', 99000.00)->count());

        // ==========================================
        // SECOND RUN (IDEMPOTENCE CHECK)
        // Jalankan command kedua kali pada data yang sama
        // ==========================================
        $this->artisan('report:etl-daily', ['--date' => $targetDate])
            ->expectsOutputToContain("ETL Daily Summary [{$targetDate}] - Diproses: 6, Dibuang: 1, Dimuat: 2")
            ->assertSuccessful();

        // Pastikan jumlah baris tetap 2 (tidak menduplikasi baris)
        $this->assertEquals(2, DailyTenantSummary::where('summary_date', $targetDate)->count());

        // Pastikan angka agregat tetap persis sama
        $summaryAAfter = DailyTenantSummary::where('summary_date', $targetDate)
            ->where('tenant_id', $tenantA->id)
            ->first();

        $this->assertEquals(4, $summaryAAfter->total_orders);
        $this->assertEquals(3, $summaryAAfter->paid_orders);
        $this->assertEquals(1, $summaryAAfter->cancelled_orders);
        $this->assertEquals(120000.00, (float)$summaryAAfter->total_revenue);
        $this->assertEquals(2, $summaryAAfter->ready_orders);
        $this->assertEquals(1, $summaryAAfter->sla_compliant_orders);
        $this->assertEquals(17.50, (float)$summaryAAfter->avg_sla_minutes);
    }

    public function test_etl_daily_backfill_range_processes_all_dates_in_period()
    {
        $tenant = Tenant::create([
            'tenant_code' => 'T-ROTI-01',
            'name' => 'Roti O Bandara',
            'floor_location' => 'Lantai 1 Gate 1',
            'is_active' => true,
        ]);

        $customer = Customer::create([
            'phone_number' => '081987654321',
            'name' => 'Dewi Passenger',
            'total_orders' => 0,
        ]);

        // Order di tanggal 2026-10-10
        Order::create([
            'order_code' => 'ORD-10',
            'tenant_id' => $tenant->id,
            'customer_id' => $customer->id,
            'customer_name' => 'Dewi Passenger',
            'status' => 'selesai',
            'is_paid' => true,
            'total_amount' => 25000.00,
            'ordered_at' => '2026-10-10 08:00:00',
            'ready_at' => '2026-10-10 08:08:00',
            'auto_cancel_at' => '2026-10-10 08:45:00',
        ]);

        // Order di tanggal 2026-10-11
        Order::create([
            'order_code' => 'ORD-11',
            'tenant_id' => $tenant->id,
            'customer_id' => $customer->id,
            'customer_name' => 'Dewi Passenger',
            'status' => 'selesai',
            'is_paid' => true,
            'total_amount' => 50000.00,
            'ordered_at' => '2026-10-11 09:00:00',
            'ready_at' => '2026-10-11 09:10:00',
            'auto_cancel_at' => '2026-10-11 09:45:00',
        ]);

        // Jalankan backfill dengan rentang --from dan --to
        $this->artisan('report:etl-daily', [
            '--from' => '2026-10-10',
            '--to' => '2026-10-11',
        ])->assertSuccessful();

        $this->assertEquals(2, DailyTenantSummary::count());
        $this->assertTrue(DailyTenantSummary::where('summary_date', '2026-10-10')->exists());
        $this->assertTrue(DailyTenantSummary::where('summary_date', '2026-10-11')->exists());
    }

    public function test_executive_dashboard_controller_reads_from_daily_tenant_summaries()
    {
        // 1. Arrange Data Tenant & Summary
        $tenantA = Tenant::create([
            'tenant_code' => 'T-A',
            'name' => 'Tenant Alfa',
            'floor_location' => 'Lantai 1',
            'is_active' => true,
        ]);

        $tenantB = Tenant::create([
            'tenant_code' => 'T-B',
            'name' => 'Tenant Beta',
            'floor_location' => 'Lantai 2',
            'is_active' => true,
        ]);

        $targetDate = '2026-10-14';

        // Buat data ringkasan di tabel daily_tenant_summaries
        DailyTenantSummary::create([
            'summary_date' => $targetDate,
            'tenant_id' => $tenantA->id,
            'total_orders' => 10,
            'paid_orders' => 8,
            'cancelled_orders' => 2,
            'total_revenue' => 250000.00,
            'ready_orders' => 8,
            'sla_compliant_orders' => 7,
            'avg_sla_minutes' => 11.20,
        ]);

        DailyTenantSummary::create([
            'summary_date' => $targetDate,
            'tenant_id' => $tenantB->id,
            'total_orders' => 5,
            'paid_orders' => 5,
            'cancelled_orders' => 0,
            'total_revenue' => 150000.00,
            'ready_orders' => 5,
            'sla_compliant_orders' => 5,
            'avg_sla_minutes' => 8.50,
        ]);

        // 2. Buat User Admin Ops
        $admin = User::create([
            'name' => 'Administrator Ops',
            'email' => 'admin.ops@flydine.id',
            'password' => bcrypt('password123'),
            'role' => 'admin_ops',
            'is_active' => true,
        ]);

        // 3. Request ke ExecutiveDashboardController::index
        $response = $this->actingAs($admin)->get(route('admin.executive-dashboard', [
            'start_date' => $targetDate,
            'end_date' => $targetDate,
        ]));

        $response->assertStatus(200);

        // Verifikasi KPI membaca dari daily_tenant_summaries
        $response->assertViewHas('totalOrdersCount', 15); // 10 + 5
        $response->assertViewHas('totalRevenue', 400000.00); // 250.000 + 150.000

        // Verifikasi tren harian ($volumePerDay)
        $volumePerDay = $response->viewData('volumePerDay');
        $this->assertCount(1, $volumePerDay);
        $this->assertEquals($targetDate, $volumePerDay->first()->date);
        $this->assertEquals(15, (int)$volumePerDay->first()->total);

        // Verifikasi performa tenant ($tenantPerformance)
        $tenantPerformance = $response->viewData('tenantPerformance');
        $this->assertCount(2, $tenantPerformance);
        $this->assertEquals('Tenant Alfa', $tenantPerformance->first()->name);
        $this->assertEquals(10, (int)$tenantPerformance->first()->total);
    }
}
