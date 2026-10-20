<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Tenant;

class AnalyticsPagesTest extends TestCase
{
    use RefreshDatabase;

    private User $adminOps;
    private User $tenantStaff;
    private Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'tenant_code' => 'T-TEST-01',
            'name' => 'KFC Terminal 1',
            'floor_location' => 'Lantai 1 Gate 3',
            'is_active' => true,
        ]);

        $this->adminOps = User::create([
            'name' => 'Admin Ops Testing',
            'email' => 'admin_ops@flydine.com',
            'password' => bcrypt('password'),
            'role' => 'admin_ops',
            'is_active' => true,
        ]);

        $this->tenantStaff = User::create([
            'name' => 'Tenant Staff Testing',
            'email' => 'tenant_staff@flydine.com',
            'password' => bcrypt('password'),
            'role' => 'tenant_staff',
            'tenant_id' => $this->tenant->id,
            'is_active' => true,
        ]);
    }

    /**
     * Tamu yang belum login diarahkan ke halaman login dari keempat halaman.
     */
    public function test_guests_are_redirected_to_login_from_all_four_pages(): void
    {
        $routes = [
            route('admin.executive-dashboard'),
            route('admin.analytics.drilldown'),
            route('admin.analytics.whatif'),
            route('admin.analytics.customers'),
        ];

        foreach ($routes as $url) {
            $response = $this->get($url);
            $response->assertRedirect('/login');
        }
    }

    /**
     * User dengan role tenant_staff mendapat response 403 Forbidden di keempat halaman.
     */
    public function test_tenant_staff_receives_403_on_all_four_pages(): void
    {
        $routes = [
            route('admin.executive-dashboard'),
            route('admin.analytics.drilldown'),
            route('admin.analytics.whatif'),
            route('admin.analytics.customers'),
        ];

        foreach ($routes as $url) {
            $response = $this->actingAs($this->tenantStaff)->get($url);
            $response->assertForbidden();
        }
    }

    /**
     * User dengan role admin_ops mendapat response 200 OK di keempat halaman.
     */
    public function test_admin_ops_receives_200_on_all_four_pages(): void
    {
        $pages = [
            'admin.executive-dashboard' => route('admin.executive-dashboard'),
            'admin.analytics.drilldown' => route('admin.analytics.drilldown'),
            'admin.analytics.whatif' => route('admin.analytics.whatif'),
            'admin.analytics.customers' => route('admin.analytics.customers'),
        ];

        foreach ($pages as $name => $url) {
            $response = $this->actingAs($this->adminOps)->get($url);
            $response->assertOk();
        }
    }

    /**
     * Parameter filter (start_date, end_date, tenant_id) selalu terbawa ke submenu link dan kartu tautan.
     */
    public function test_filters_are_carried_over_to_submenu_links_and_navigation_cards(): void
    {
        $params = [
            'start_date' => '2026-09-15',
            'end_date' => '2026-10-15',
            'tenant_id' => $this->tenant->id,
        ];

        // 1. Kunjungi Dashboard Eksekutif dengan query parameters
        $response = $this->actingAs($this->adminOps)->get(route('admin.executive-dashboard', $params));
        $response->assertOk();

        // Cek bahwa submenu links di sidebar membawa query parameters
        $response->assertSee(route('admin.executive-dashboard', $params));
        $response->assertSee(route('admin.analytics.drilldown', $params));
        $response->assertSee(route('admin.analytics.whatif', $params));
        $response->assertSee(route('admin.analytics.customers', $params));



        // 2. Kunjungi Drilldown dengan query parameters dan pastikan filter tetap terbawa di submenu
        $drilldownResponse = $this->actingAs($this->adminOps)->get(route('admin.analytics.drilldown', $params));
        $drilldownResponse->assertOk();
        $drilldownResponse->assertSee(route('admin.executive-dashboard', $params));
        $drilldownResponse->assertSee(route('admin.analytics.whatif', $params));

        // 3. Kunjungi What-If dengan query parameters dan pastikan filter tetap terbawa di submenu
        $whatifResponse = $this->actingAs($this->adminOps)->get(route('admin.analytics.whatif', $params));
        $whatifResponse->assertOk();
        $whatifResponse->assertSee(route('admin.executive-dashboard', $params));
        $whatifResponse->assertSee(route('admin.analytics.drilldown', $params));

        // 4. Kunjungi Segmentasi Pelanggan dengan query parameters
        $customersResponse = $this->actingAs($this->adminOps)->get(route('admin.analytics.customers', $params));
        $customersResponse->assertOk();
        $customersResponse->assertSee(route('admin.executive-dashboard', $params));
        $customersResponse->assertSee(route('admin.analytics.customers', $params));
    }

    /**
     * Dashboard eksekutif tetap 200 OK dan menampilkan elemen ringkasan eksekutif secara lengkap.
     */
    public function test_executive_dashboard_contains_required_components_and_removes_old_heavy_blocks(): void
    {
        $response = $this->actingAs($this->adminOps)->get(route('admin.executive-dashboard'));
        $response->assertOk();

        // Harus ada panel filter bersama
        $response->assertSee('Dari Tanggal');
        $response->assertSee('Sampai Tanggal');
        $response->assertSee('Terapkan Rentang');
        $response->assertSee('Filter Mitra Tenant');

        // Harus ada 6 kartu KPI
        $response->assertSee('Volume Pesanan');
        $response->assertSee('Transaksi Puncak');
        $response->assertSee('Okupansi Tenant');
        $response->assertSee('Rata-rata SLA');
        $response->assertSee('Cancel Rate');
        $response->assertSee('Komplain Kasus');

        // Harus ada canvas grafik
        $response->assertSee('volumeChart');
        $response->assertSee('slaChart');
        $response->assertSee('statusChart');
        $response->assertSee('tenantChart');
        $response->assertSee('productsChart');
        $response->assertSee('hourlyChart');

        // Navigasi analitik tersedia
        $response->assertSee('Drill-Down Pesanan');
        $response->assertSee('Simulasi What-If');
        $response->assertSee('Segmentasi Pelanggan');

        // Blok berat yang dipindah TIDAK boleh ada lagi di halaman ringkasan eksekutif
        $response->assertDontSee('drillDownInspector()');
        $response->assertDontSee('targetPlanningSimulator()');
        $response->assertDontSee('Enterprise CRM - Segmentasi Pelanggan');
    }

    /**
     * Memastikan Export PDF eksekutif berfungsi.
     */
    public function test_export_pdf_returns_success(): void
    {
        $response = $this->actingAs($this->adminOps)->get(route('admin.executive-dashboard.export'));
        $response->assertOk();
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }

    /**
     * Memastikan Export Excel eksekutif berfungsi.
     */
    public function test_export_excel_returns_success(): void
    {
        $response = $this->actingAs($this->adminOps)->get(route('admin.executive-dashboard.export-excel'));
        $response->assertOk();
        $this->assertStringContainsString('application/vnd.ms-excel', $response->headers->get('Content-Type'));
    }

    /**
     * Memastikan Export Simulasi PDF berfungsi.
     */
    public function test_export_simulation_pdf_returns_success(): void
    {
        $response = $this->actingAs($this->adminOps)->get(route('admin.executive-dashboard.export-simulation-pdf'));
        $response->assertOk();
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }
}
