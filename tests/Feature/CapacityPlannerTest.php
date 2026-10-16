<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Customer;
use App\Models\Order;
use App\Services\CapacityPlanner;
use Carbon\Carbon;

class CapacityPlannerTest extends TestCase
{
    use RefreshDatabase;

    private User $adminOps;
    private User $tenantStaff;
    private Tenant $tenantA;
    private Tenant $tenantB;
    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenantA = Tenant::create([
            'tenant_code' => 'T-KFC-01',
            'name' => 'KFC Terminal 1',
            'category' => 'Fast Food & Restaurant',
            'floor_location' => 'Lantai 1 Gate 3',
            'zone' => 'Keberangkatan',
            'is_active' => true,
        ]);

        $this->tenantB = Tenant::create([
            'tenant_code' => 'T-SBX-01',
            'name' => 'Starbucks Terminal 1',
            'category' => 'Beverages & Coffee',
            'floor_location' => 'Lantai 2 Gate 5',
            'zone' => 'Transit',
            'is_active' => true,
        ]);

        $this->customer = Customer::create([
            'phone_number' => '081234567890',
            'name' => 'Penumpang Juanda',
            'total_orders' => 1,
            'is_blocked' => false,
        ]);

        $this->adminOps = User::create([
            'name' => 'Admin Ops Juanda',
            'email' => 'admin_ops@flydine.com',
            'password' => bcrypt('password'),
            'role' => 'admin_ops',
            'is_active' => true,
        ]);

        $this->tenantStaff = User::create([
            'name' => 'Staff KFC',
            'email' => 'staff_kfc@flydine.com',
            'password' => bcrypt('password'),
            'role' => 'tenant_staff',
            'tenant_id' => $this->tenantA->id,
            'is_active' => true,
        ]);
    }

    /**
     * Uji Batas Status Utilisasi: 69.9, 70, 90, 100.
     */
    public function test_status_boundary_conditions(): void
    {
        // < 70% : AMAN
        $this->assertEquals('AMAN', CapacityPlanner::determineStatus(0.0));
        $this->assertEquals('AMAN', CapacityPlanner::determineStatus(50.0));
        $this->assertEquals('AMAN', CapacityPlanner::determineStatus(69.9));

        // 70% - 90% : WASPADA
        $this->assertEquals('WASPADA', CapacityPlanner::determineStatus(70.0));
        $this->assertEquals('WASPADA', CapacityPlanner::determineStatus(80.5));
        $this->assertEquals('WASPADA', CapacityPlanner::determineStatus(90.0));

        // 90% - 100% : KRITIS
        $this->assertEquals('KRITIS', CapacityPlanner::determineStatus(90.1));
        $this->assertEquals('KRITIS', CapacityPlanner::determineStatus(95.0));
        $this->assertEquals('KRITIS', CapacityPlanner::determineStatus(100.0));

        // > 100% : OVERLOAD
        $this->assertEquals('OVERLOAD', CapacityPlanner::determineStatus(100.1));
        $this->assertEquals('OVERLOAD', CapacityPlanner::determineStatus(150.0));

        // Null : Data belum cukup
        $this->assertEquals('Data belum cukup', CapacityPlanner::determineStatus(null));
    }

    /**
     * Uji Hitungan Jumlah Staf Minimum agar Utilisasi <= 80%.
     */
    public function test_minimum_staff_calculation(): void
    {
        // Formula: ceil((lambda * serviceMinutes) / 48)
        // 4.8 orders/hr, 10 min SLA => 4.8 * 10 / 48 = 1.0 => 1 staff
        $this->assertEquals(1, CapacityPlanner::calculateMinimumStaff(4.8, 10.0));

        // 5.0 orders/hr, 10 min SLA => 50 / 48 = 1.0416 => 2 staff
        $this->assertEquals(2, CapacityPlanner::calculateMinimumStaff(5.0, 10.0));

        // 12.0 orders/hr, 12 min SLA => 144 / 48 = 3.0 => 3 staff
        $this->assertEquals(3, CapacityPlanner::calculateMinimumStaff(12.0, 12.0));

        // 0 orders/hr => min 1 staff
        $this->assertEquals(1, CapacityPlanner::calculateMinimumStaff(0.0, 10.0));

        // Null cases
        $this->assertNull(CapacityPlanner::calculateMinimumStaff(null, 10.0));
        $this->assertNull(CapacityPlanner::calculateMinimumStaff(5.0, null));
    }

    /**
     * Helper membuat order pengujian yang valid
     */
    private function createTestOrder(array $attributes = []): Order
    {
        return Order::create(array_merge([
            'order_code' => 'ORD-' . uniqid(),
            'customer_id' => $this->customer->id,
            'customer_name' => 'Penumpang Juanda',
            'tenant_id' => $this->tenantA->id,
            'status' => 'selesai',
            'is_paid' => true,
            'payment_method' => 'qris',
            'pickup_method' => 'ambil_sendiri',
            'total_amount' => 50000,
            'auto_cancel_at' => Carbon::parse('2026-10-01 12:30:00'),
            'ordered_at' => Carbon::parse('2026-10-01 12:00:00'),
            'ready_at' => Carbon::parse('2026-10-01 12:10:00'),
        ], $attributes));
    }

    /**
     * Uji Hitungan Benar pada Data Contoh: lambda0, S0, mu, utilisasi sebelum & sesudah.
     */
    public function test_capacity_planner_calculation_on_sample_data(): void
    {
        $baseDate = Carbon::parse('2026-10-01 10:00:00');

        // Buat 12 pesanan valid untuk Tenant A pada jam 12:00 (jam puncak)
        // Waktu saji: selisih 10 menit (ordered 12:00, ready 12:10)
        for ($i = 0; $i < 12; $i++) {
            $this->createTestOrder([
                'order_code' => "ORD-SMPL-{$i}",
                'tenant_id' => $this->tenantA->id,
                'status' => 'selesai',
                'ordered_at' => $baseDate->copy()->addDays($i)->setTime(12, 0, 0),
                'ready_at' => $baseDate->copy()->addDays($i)->setTime(12, 10, 0), // 10 menit
            ]);
        }

        $planner = new CapacityPlanner();
        $startDate = '2026-10-01';
        $endDate = '2026-10-12'; // 12 hari

        // Simulasi: kenaikan +25%, staf = 2, waktu saji default (null -> 10 mnt)
        $result = $planner->calculate($startDate, $endDate, $this->tenantA->id, 25.0, 2, null);

        $this->assertArrayHasKey('summary', $result);
        $this->assertArrayHasKey('tenants', $result);

        $tenantResult = collect($result['tenants'])->firstWhere('tenant_id', $this->tenantA->id);
        $this->assertNotNull($tenantResult);
        $this->assertTrue($tenantResult['has_sufficient_data']);

        // 12 pesanan pada jam 12:00 dalam 12 hari => lambda0 = 12 / 12 = 1.0 order/jam
        $this->assertEquals(1.0, $tenantResult['lambda_0']);

        // Rata-rata waktu saji S0 = 10.0 menit
        $this->assertEquals(10.0, $tenantResult['service_minutes_0']);

        // Kapasitas sebelum = 1 staf * 60 / 10 = 6.0 order/jam
        $this->assertEquals(6.0, $tenantResult['capacity_before']);

        // Utilisasi sebelum = (1.0 / 6.0) * 100 = 16.7%
        $this->assertEquals(16.7, $tenantResult['utilization_before']);

        // Proyeksi kenaikan +25% => lambda_projected = 1.0 * 1.25 = 1.25 order/jam
        $this->assertEquals(1.25, $tenantResult['lambda_projected']);

        // Kapasitas baru dengan 2 staf => 2 * 60 / 10 = 12.0 order/jam
        $this->assertEquals(12.0, $tenantResult['capacity_new']);

        // Utilisasi baru = (1.25 / 12.0) * 100 = 10.4%
        $this->assertEquals(10.4, $tenantResult['utilization_new']);

        // Status: 10.4% < 70% => AMAN
        $this->assertEquals('AMAN', $tenantResult['status']);

        // Staf minimum: ceil(1.25 * 10 / 48) = ceil(0.26) = 1 staf
        $this->assertEquals(1, $tenantResult['min_staff_needed']);
    }

    /**
     * Kasus Data Tidak Cukup: Pesanan kurang dari 10.
     */
    public function test_insufficient_data_case_when_orders_less_than_10(): void
    {
        $baseDate = Carbon::parse('2026-10-01 10:00:00');

        // Hanya 5 pesanan (< 10)
        for ($i = 0; $i < 5; $i++) {
            $this->createTestOrder([
                'order_code' => "ORD-LOW-{$i}",
                'tenant_id' => $this->tenantB->id,
                'status' => 'selesai',
                'ordered_at' => $baseDate->copy()->addDays($i)->setTime(11, 0, 0),
                'ready_at' => $baseDate->copy()->addDays($i)->setTime(11, 8, 0),
            ]);
        }

        $planner = new CapacityPlanner();
        $result = $planner->calculate('2026-10-01', '2026-10-10', $this->tenantB->id);

        $tenantResult = collect($result['tenants'])->firstWhere('tenant_id', $this->tenantB->id);
        $this->assertNotNull($tenantResult);
        $this->assertFalse($tenantResult['has_sufficient_data']);
        $this->assertEquals('Data belum cukup', $tenantResult['status']);

        // JANGAN mengisi angka karangan
        $this->assertNull($tenantResult['lambda_0']);
        $this->assertNull($tenantResult['service_minutes_0']);
        $this->assertNull($tenantResult['utilization_before']);
        $this->assertNull($tenantResult['utilization_new']);
        $this->assertNull($tenantResult['min_staff_needed']);
    }

    /**
     * Kasus Data Tidak Cukup: Pesanan >= 10 tetapi tidak ada yang memiliki ready_at valid.
     */
    public function test_insufficient_data_case_when_no_valid_sla(): void
    {
        $baseDate = Carbon::parse('2026-10-01 10:00:00');

        for ($i = 0; $i < 12; $i++) {
            $this->createTestOrder([
                'order_code' => "ORD-NOSLA-{$i}",
                'tenant_id' => $this->tenantB->id,
                'status' => 'diproses',
                'ordered_at' => $baseDate->copy()->addDays($i)->setTime(11, 0, 0),
                'ready_at' => null, // Tidak ada SLA
            ]);
        }

        $planner = new CapacityPlanner();
        $result = $planner->calculate('2026-10-01', '2026-10-12', $this->tenantB->id);

        $tenantResult = collect($result['tenants'])->firstWhere('tenant_id', $this->tenantB->id);
        $this->assertNotNull($tenantResult);
        $this->assertFalse($tenantResult['has_sufficient_data']);
        $this->assertEquals('Data belum cukup', $tenantResult['status']);
    }

    /**
     * Kasus Utilisasi di Atas 100 Persen (OVERLOAD).
     */
    public function test_overload_utilization_above_100_percent(): void
    {
        $baseDate = Carbon::parse('2026-10-01 12:00:00');

        // Buat 15 pesanan dalam 1 hari yang semuanya terkonsentrasi di jam 12:00
        // Waktu saji 15 menit => kapasitas 1 staf = 60 / 15 = 4 order/jam
        // Beban puncak = 15 order/jam => Utilisasi = 15 / 4 * 100 = 375% (> 100%)
        for ($i = 0; $i < 15; $i++) {
            $this->createTestOrder([
                'order_code' => "ORD-OVL-{$i}",
                'tenant_id' => $this->tenantA->id,
                'status' => 'selesai',
                'ordered_at' => $baseDate->copy()->setTime(12, $i, 0),
                'ready_at' => $baseDate->copy()->setTime(12, $i + 15, 0), // 15 menit
            ]);
        }

        $planner = new CapacityPlanner();
        $result = $planner->calculate('2026-10-01', '2026-10-01', $this->tenantA->id, 0.0, 1, null);

        $tenantResult = collect($result['tenants'])->firstWhere('tenant_id', $this->tenantA->id);
        $this->assertNotNull($tenantResult);
        $this->assertTrue($tenantResult['has_sufficient_data']);

        // Utilisasi harus > 100% dan status OVERLOAD
        $this->assertGreaterThan(100.0, $tenantResult['utilization_new']);
        $this->assertEquals('OVERLOAD', $tenantResult['status']);

        // Sisa kapasitas harus negatif
        $this->assertLessThan(0.0, $tenantResult['capacity_remaining']);

        // Staf minimum agar <= 80%: ceil(15 * 15 / 48) = ceil(4.6875) = 5 staf
        $this->assertEquals(5, $tenantResult['min_staff_needed']);
    }

    /**
     * Uji Keamanan: HTTP 403 untuk Non-Admin (tenant_staff dan unauthenticated).
     */
    public function test_endpoint_returns_403_for_non_admin_and_guests(): void
    {
        // 1. Guest diarahkan ke login
        $guestResponse = $this->postJson(route('admin.analytics.whatif.calculate'), [
            'order_growth_pct' => 10,
        ]);
        $guestResponse->assertUnauthorized();

        // 2. Tenant Staff mendapat HTTP 403 Forbidden
        $staffResponse = $this->actingAs($this->tenantStaff)->postJson(route('admin.analytics.whatif.calculate'), [
            'order_growth_pct' => 10,
        ]);
        $staffResponse->assertForbidden();

        // 3. Admin Ops berhasil mendapat HTTP 200 OK
        $adminResponse = $this->actingAs($this->adminOps)->postJson(route('admin.analytics.whatif.calculate'), [
            'order_growth_pct' => 20,
            'staff_count' => 2,
        ]);
        $adminResponse->assertOk();
        $adminResponse->assertJsonStructure([
            'period' => ['start_date', 'end_date', 'days'],
            'inputs' => ['tenant_id', 'order_growth_pct', 'staff_count', 'target_sla_minutes'],
            'summary' => [
                'count_total_tenants',
                'count_sufficient',
                'avg_utilization_before',
                'avg_utilization_after',
                'total_capacity_new',
                'status_distribution',
                'overall_status',
                'min_staff_recommended',
            ],
            'top_10_chart' => ['labels', 'utilization_before', 'utilization_after'],
            'tenants',
        ]);
    }

    /**
     * Halaman View What-If dapat diakses Admin Ops dan ditolak untuk Tenant Staff.
     */
    public function test_whatif_page_view_accessible_by_admin_ops_and_forbidden_for_tenant_staff(): void
    {
        $staffResponse = $this->actingAs($this->tenantStaff)->get(route('admin.analytics.whatif'));
        $staffResponse->assertForbidden();

        $adminResponse = $this->actingAs($this->adminOps)->get(route('admin.analytics.whatif'));
        $adminResponse->assertOk();
        $adminResponse->assertSee('Perencanaan Kapasitas');
        $adminResponse->assertSee('Panel Kontrol Simulasi');
        $adminResponse->assertSee('capacityTop10Chart');
        $adminResponse->assertSee('Matriks Kapasitas Seluruh Mitra Gerai Tenant');
        $adminResponse->assertSee('TARGET PLANNING');
    }
}
