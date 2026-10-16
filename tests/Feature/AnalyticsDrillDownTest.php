<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;

class AnalyticsDrillDownTest extends TestCase
{
    use RefreshDatabase;

    private User $adminOps;
    private User $tenantStaff;
    private Tenant $tenantA;
    private Tenant $tenantB;
    private Customer $customer;
    private Product $productA;
    private Product $productB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenantA = Tenant::create([
            'tenant_code' => 'T-KFC-01',
            'name' => 'KFC Terminal 1',
            'category' => 'Fast Food & Restaurant',
            'floor_location' => 'Lantai 1 Gate 3',
            'zone' => 'Zona Keberangkatan',
            'is_active' => true,
        ]);

        $this->tenantB = Tenant::create([
            'tenant_code' => 'T-SBX-01',
            'name' => 'Starbucks Terminal 1',
            'category' => 'Beverages & Coffee',
            'floor_location' => 'Lantai 2 Gate 5',
            'zone' => 'Zona Transit',
            'is_active' => true,
        ]);

        $this->customer = Customer::create([
            'phone_number' => '081234567890',
            'name' => 'Budi Santoso',
            'total_orders' => 1,
            'is_blocked' => false,
        ]);

        $this->productA = Product::create([
            'tenant_id' => $this->tenantA->id,
            'name' => 'Ayam Krispi Original',
            'category' => 'Makanan',
            'price' => 35000,
            'stock' => 100,
            'is_available' => true,
        ]);

        $this->productB = Product::create([
            'tenant_id' => $this->tenantB->id,
            'name' => 'Caramel Macchiato',
            'category' => 'Minuman',
            'price' => 55000,
            'stock' => 50,
            'is_available' => true,
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

        // Seed 4 test orders with varied attributes
        // Order 1: Selesai, Compliant SLA (10 min), Pickup: diantar, Paid, Hour: 11
        $o1 = Order::create([
            'order_code' => 'ORD-TEST-001',
            'tenant_id' => $this->tenantA->id,
            'customer_id' => $this->customer->id,
            'customer_name' => 'Budi Santoso',
            'flight_number' => 'GA-312',
            'gate' => 'Gate 3',
            'status' => 'selesai',
            'is_paid' => true,
            'payment_method' => 'qris',
            'pickup_method' => 'diantar',
            'total_amount' => 35000,
            'auto_cancel_at' => Carbon::parse('2026-10-15 11:20:00'),
            'ordered_at' => Carbon::parse('2026-10-15 11:00:00'),
            'ready_at' => Carbon::parse('2026-10-15 11:10:00'),
            'completed_at' => Carbon::parse('2026-10-15 11:18:00'),
        ]);
        OrderItem::create([
            'order_id' => $o1->id,
            'product_id' => $this->productA->id,
            'product_name_snapshot' => $this->productA->name,
            'quantity' => 1,
            'unit_price' => 35000,
            'subtotal' => 35000,
        ]);

        // Order 2: Siap, Breached SLA (25 min), Pickup: ambil_sendiri, Paid, Hour: 14
        $o2 = Order::create([
            'order_code' => 'ORD-TEST-002',
            'tenant_id' => $this->tenantB->id,
            'customer_id' => $this->customer->id,
            'customer_name' => 'Siti Rahma',
            'flight_number' => 'JT-604',
            'gate' => 'Gate 5',
            'status' => 'siap',
            'is_paid' => true,
            'payment_method' => 'debit',
            'pickup_method' => 'ambil_sendiri',
            'total_amount' => 110000,
            'auto_cancel_at' => Carbon::parse('2026-10-15 14:30:00'),
            'ordered_at' => Carbon::parse('2026-10-15 14:00:00'),
            'ready_at' => Carbon::parse('2026-10-15 14:25:00'),
        ]);
        OrderItem::create([
            'order_id' => $o2->id,
            'product_id' => $this->productB->id,
            'product_name_snapshot' => $this->productB->name,
            'quantity' => 2,
            'unit_price' => 55000,
            'subtotal' => 110000,
        ]);

        // Order 3: Menunggu, Not Paid, Hour: 18
        $o3 = Order::create([
            'order_code' => 'ORD-TEST-003',
            'tenant_id' => $this->tenantA->id,
            'customer_id' => $this->customer->id,
            'customer_name' => 'Agus Prayitno',
            'flight_number' => 'QG-802',
            'gate' => 'Gate 2',
            'status' => 'menunggu',
            'is_paid' => false,
            'pickup_method' => 'ambil_sendiri',
            'total_amount' => 70000,
            'auto_cancel_at' => Carbon::parse('2026-10-15 18:20:00'),
            'ordered_at' => Carbon::parse('2026-10-15 18:00:00'),
        ]);
        OrderItem::create([
            'order_id' => $o3->id,
            'product_id' => $this->productA->id,
            'product_name_snapshot' => $this->productA->name,
            'quantity' => 2,
            'unit_price' => 35000,
            'subtotal' => 70000,
        ]);

        // Order 4: Dibatalkan, Date: 2026-10-14, Hour: 09
        $o4 = Order::create([
            'order_code' => 'ORD-TEST-004',
            'tenant_id' => $this->tenantA->id,
            'customer_id' => $this->customer->id,
            'customer_name' => 'Dewi Lestari',
            'flight_number' => 'ID-6511',
            'gate' => 'Gate 1',
            'status' => 'dibatalkan',
            'is_paid' => false,
            'pickup_method' => 'ambil_sendiri',
            'total_amount' => 35000,
            'auto_cancel_at' => Carbon::parse('2026-10-14 09:20:00'),
            'ordered_at' => Carbon::parse('2026-10-14 09:00:00'),
        ]);
        OrderItem::create([
            'order_id' => $o4->id,
            'product_id' => $this->productA->id,
            'product_name_snapshot' => $this->productA->name,
            'quantity' => 1,
            'unit_price' => 35000,
            'subtotal' => 35000,
        ]);
    }

    /**
     * Halaman drilldown dapat diakses oleh admin_ops dan 403 untuk selain admin_ops.
     */
    public function test_drilldown_view_authorization(): void
    {
        // Tamu diarahkan ke login
        $this->get(route('admin.analytics.drilldown'))->assertRedirect('/login');

        // Tenant staff dilarang (403)
        $this->actingAs($this->tenantStaff)->get(route('admin.analytics.drilldown'))->assertForbidden();

        // Admin ops berhasil (200)
        $this->actingAs($this->adminOps)->get(route('admin.analytics.drilldown'))->assertOk();
    }

    /**
     * Endpoint JSON GET /admin/analytics/drilldown/orders mengembalikan ringkasan dan paginasi.
     */
    public function test_drilldown_orders_endpoint_structure(): void
    {
        $response = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.orders', [
            'start_date' => '2026-10-14',
            'end_date' => '2026-10-15',
        ]));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'order_code',
                        'tenant_id',
                        'tenant_name',
                        'customer_name',
                        'flight_number',
                        'gate',
                        'pickup_method',
                        'status',
                        'is_paid',
                        'total_amount',
                        'sla_minutes',
                        'sla_compliant',
                        'items_summary',
                    ]
                ],
                'pagination' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total',
                ],
                'summary' => [
                    'total_orders',
                    'total_revenue',
                    'total_revenue_formatted',
                    'avg_sla_minutes',
                    'sla_compliant_percentage',
                    'ready_orders_count',
                ]
            ]);

        $summary = $response->json('summary');
        $this->assertEquals(4, $summary['total_orders']);
        // Hanya yang is_paid: 35000 (o1) + 110000 (o2) = 145000
        $this->assertEquals(145000, $summary['total_revenue']);
        $this->assertEquals(2, $summary['ready_orders_count']);
        // 1 compliant out of 2 ready = 50%
        $this->assertEquals(50.0, $summary['sla_compliant_percentage']);
    }

    /**
     * Filter status pesanan bekerja dengan tepat.
     */
    public function test_drilldown_orders_filters_by_status(): void
    {
        // Filter status 'selesai' -> harus mengembalikan hanya ORD-TEST-001
        $res = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.orders', [
            'start_date' => '2026-10-14',
            'end_date' => '2026-10-15',
            'status' => 'selesai',
        ]));

        $res->assertOk();
        $data = $res->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('ORD-TEST-001', $data[0]['order_code']);

        // Filter status 'dibatalkan' -> ORD-TEST-004
        $resCancel = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.orders', [
            'start_date' => '2026-10-14',
            'end_date' => '2026-10-15',
            'status' => 'dibatalkan',
        ]));
        $this->assertCount(1, $resCancel->json('data'));
        $this->assertEquals('ORD-TEST-004', $resCancel->json('data.0.order_code'));
    }

    /**
     * Filter kepatuhan SLA (compliant <= 15m vs breached > 15m) bekerja dengan tepat.
     */
    public function test_drilldown_orders_filters_by_sla_compliance(): void
    {
        // Compliant (ORD-TEST-001: 10 menit)
        $resCompliant = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.orders', [
            'start_date' => '2026-10-14',
            'end_date' => '2026-10-15',
            'sla' => 'compliant',
        ]));
        $resCompliant->assertOk();
        $this->assertCount(1, $resCompliant->json('data'));
        $this->assertEquals('ORD-TEST-001', $resCompliant->json('data.0.order_code'));

        // Breached (ORD-TEST-002: 25 menit)
        $resBreached = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.orders', [
            'start_date' => '2026-10-14',
            'end_date' => '2026-10-15',
            'sla' => 'breached',
        ]));
        $resBreached->assertOk();
        $this->assertCount(1, $resBreached->json('data'));
        $this->assertEquals('ORD-TEST-002', $resBreached->json('data.0.order_code'));
    }

    /**
     * Filter jam operasional (0-23) bekerja dengan tepat.
     */
    public function test_drilldown_orders_filters_by_hour(): void
    {
        // Hour 11 -> ORD-TEST-001
        $res = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.orders', [
            'start_date' => '2026-10-14',
            'end_date' => '2026-10-15',
            'hour' => 11,
        ]));
        $res->assertOk();
        $this->assertCount(1, $res->json('data'));
        $this->assertEquals('ORD-TEST-001', $res->json('data.0.order_code'));

        // Hour 14 -> ORD-TEST-002
        $res14 = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.orders', [
            'start_date' => '2026-10-14',
            'end_date' => '2026-10-15',
            'hour' => 14,
        ]));
        $this->assertCount(1, $res14->json('data'));
        $this->assertEquals('ORD-TEST-002', $res14->json('data.0.order_code'));
    }

    /**
     * Filter metode ambil (ambil_sendiri vs diantar) bekerja dengan tepat.
     */
    public function test_drilldown_orders_filters_by_pickup_method(): void
    {
        $res = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.orders', [
            'start_date' => '2026-10-14',
            'end_date' => '2026-10-15',
            'pickup_method' => 'diantar',
        ]));
        $res->assertOk();
        $this->assertCount(1, $res->json('data'));
        $this->assertEquals('ORD-TEST-001', $res->json('data.0.order_code'));
    }

    /**
     * Pencarian bebas bekerja di multi-kriteria (kode order, penumpang, flight, gate, menu).
     */
    public function test_drilldown_orders_filters_by_search_query(): void
    {
        // Cari by flight number 'GA-312'
        $resFlight = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.orders', [
            'search' => 'GA-312',
        ]));
        $this->assertCount(1, $resFlight->json('data'));
        $this->assertEquals('ORD-TEST-001', $resFlight->json('data.0.order_code'));

        // Cari by menu snapshot 'Caramel Macchiato'
        $resMenu = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.orders', [
            'search' => 'Macchiato',
        ]));
        $this->assertCount(1, $resMenu->json('data'));
        $this->assertEquals('ORD-TEST-002', $resMenu->json('data.0.order_code'));
    }

    /**
     * Paginasi dan pengurutan server (sort whitelist, per_page 10/25/50).
     */
    public function test_drilldown_orders_pagination_and_sorting(): void
    {
        // Sort total_amount desc
        $res = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.orders', [
            'sort_by' => 'total_amount',
            'sort_dir' => 'desc',
            'per_page' => 10,
        ]));
        $res->assertOk();
        $data = $res->json('data');
        // ORD-TEST-002 total_amount 110000 harus pertama
        $this->assertEquals('ORD-TEST-002', $data[0]['order_code']);

        // Test pagination page 2 dengan per_page=2
        // Note: per_page only accepts 10, 25, 50 per validation rule
        $resPage = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.orders', [
            'per_page' => 10,
            'page' => 1,
        ]));
        $this->assertEquals(1, $resPage->json('pagination.current_page'));
    }

    /**
     * Endpoint GET /admin/analytics/drilldown/charts mengembalikan kelima grafik.
     */
    public function test_drilldown_charts_endpoint_returns_five_charts(): void
    {
        $res = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.charts', [
            'start_date' => '2026-10-14',
            'end_date' => '2026-10-15',
        ]));

        $res->assertOk()
            ->assertJsonStructure([
                'orders_per_day' => ['labels', 'raw_dates', 'values'],
                'orders_per_hour' => ['labels', 'raw_hours', 'values'],
                'status_distribution' => ['labels', 'raw_statuses', 'values', 'colors'],
                'top_tenants' => ['labels', 'tenant_ids', 'values'],
                'top_menus' => ['labels', 'menu_names', 'values'],
            ]);

        // Cek data harian memuat 2026-10-14 dan 2026-10-15
        $dailyDates = $res->json('orders_per_day.raw_dates');
        $this->assertContains('2026-10-14', $dailyDates);
        $this->assertContains('2026-10-15', $dailyDates);

        // Cek 24 jam terisi
        $this->assertCount(24, $res->json('orders_per_hour.labels'));
    }

    /**
     * Endpoint GET /admin/analytics/drilldown/orders/{order} mengembalikan data detail & linimasa.
     */
    public function test_drilldown_order_detail_endpoint(): void
    {
        $order = Order::where('order_code', 'ORD-TEST-001')->firstOrFail();

        $res = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.order-detail', $order));

        $res->assertOk()
            ->assertJsonStructure([
                'id',
                'order_code',
                'status',
                'ordered_at_formatted',
                'is_paid',
                'payment_method',
                'total_amount_formatted',
                'pickup_method',
                'pickup_method_label',
                'delivery_target',
                'tenant' => ['id', 'name', 'location', 'zone'],
                'customer' => ['name', 'flight_number', 'gate', 'phone'],
                'sla' => ['minutes', 'compliant', 'badge'],
                'items' => [
                    '*' => ['id', 'name', 'quantity', 'unit_price_formatted', 'subtotal_formatted']
                ],
                'timeline' => [
                    '*' => ['stage', 'time', 'desc', 'done']
                ]
            ]);

        $this->assertEquals('ORD-TEST-001', $res->json('order_code'));
        $this->assertTrue($res->json('sla.compliant'));
        $this->assertEquals(10.0, $res->json('sla.minutes'));
    }

    /**
     * Validasi 403 Forbidden untuk non-admin di semua endpoint drill-down.
     */
    public function test_drilldown_endpoints_return_403_for_non_admin(): void
    {
        $order = Order::first();

        // 1. Orders list JSON
        $this->actingAs($this->tenantStaff)->getJson(route('admin.analytics.drilldown.orders'))->assertForbidden();

        // 2. Charts JSON
        $this->actingAs($this->tenantStaff)->getJson(route('admin.analytics.drilldown.charts'))->assertForbidden();

        // 3. Order detail JSON
        $this->actingAs($this->tenantStaff)->getJson(route('admin.analytics.drilldown.order-detail', $order))->assertForbidden();
    }

    /**
     * Validasi 422 Unprocessable Entity saat parameter tidak valid dikirimkan.
     */
    public function test_drilldown_endpoints_return_422_for_invalid_parameters(): void
    {
        // 1. Status tidak valid
        $resStatus = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.orders', [
            'status' => 'status_ilegal',
        ]));
        $resStatus->assertStatus(422)->assertJsonValidationErrors(['status']);

        // 2. SLA tidak valid
        $resSla = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.orders', [
            'sla' => 'sla_aneh',
        ]));
        $resSla->assertStatus(422)->assertJsonValidationErrors(['sla']);

        // 3. Hour di luar 0-23
        $resHour = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.orders', [
            'hour' => 25,
        ]));
        $resHour->assertStatus(422)->assertJsonValidationErrors(['hour']);

        // 4. Per page di luar 10, 25, 50
        $resPerPage = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.orders', [
            'per_page' => 999,
        ]));
        $resPerPage->assertStatus(422)->assertJsonValidationErrors(['per_page']);

        // 5. Sort by di luar whitelist
        $resSort = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.orders', [
            'sort_by' => 'password_rahasia',
        ]));
        $resSort->assertStatus(422)->assertJsonValidationErrors(['sort_by']);

        // 6. Category di luar whitelist resmi
        $resCatOrders = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.orders', [
            'category' => 'Kategori_Palsu_Ngawur',
        ]));
        $resCatOrders->assertStatus(422)->assertJsonValidationErrors(['category']);

        $resCatCharts = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.charts', [
            'category' => 'Kategori_Palsu_Ngawur',
        ]));
        $resCatCharts->assertStatus(422)->assertJsonValidationErrors(['category']);
    }

    /**
     * Filter kategori mengembalikan tenant yang tepat (cross-filter & isolasi data).
     */
    public function test_filter_category_returns_correct_tenant_orders(): void
    {
        // Tenant A adalah "Fast Food & Restaurant", Tenant B adalah "Beverages & Coffee"
        $res = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.orders', [
            'category' => 'Fast Food & Restaurant',
        ]));

        $res->assertOk();
        $orders = $res->json('data');
        $this->assertNotEmpty($orders);
        foreach ($orders as $order) {
            $this->assertEquals($this->tenantA->id, $order['tenant_id']);
            $this->assertEquals('KFC Terminal 1', $order['tenant_name']);
        }

        // Cek juga di endpoint charts
        $resCharts = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.charts', [
            'category' => 'Fast Food & Restaurant',
        ]));

        $resCharts->assertOk()
            ->assertJsonStructure([
                'category_distribution' => ['labels', 'categories', 'order_counts', 'revenues']
            ]);

        $this->assertContains('Fast Food & Restaurant', $resCharts->json('category_distribution.categories'));
    }

    /**
     * Kategori tanpa pesanan menampilkan state kosong (empty state).
     */
    public function test_filter_category_without_orders_returns_empty_state(): void
    {
        // Kategori "Lounge" valid namun tidak memiliki pesanan
        $res = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.orders', [
            'category' => 'Lounge',
        ]));

        $res->assertOk();
        $this->assertEmpty($res->json('data'));
        $this->assertEquals(0, $res->json('pagination.total'));
        $this->assertEquals(0, $res->json('summary.total_orders'));
        $this->assertEquals(0, $res->json('summary.total_revenue'));

        // Di charts juga mengembalikan kategori kosong
        $resCharts = $this->actingAs($this->adminOps)->getJson(route('admin.analytics.drilldown.charts', [
            'category' => 'Lounge',
        ]));

        $resCharts->assertOk();
        $this->assertEmpty($resCharts->json('category_distribution.labels'));
    }
}
