<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CancelExpiredOrdersTest extends TestCase
{
    use RefreshDatabase;

    private Tenant $tenant;
    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'tenant_code' => 'T-TEST-AUTO',
            'name' => 'Kedai Juanda Test',
            'floor_location' => 'Lantai 1 Gate 2',
            'terminal' => '1',
            'is_active' => true,
        ]);

        $this->customer = Customer::create([
            'name' => 'Pelanggan Uji Otomasi',
            'phone_number' => '081234567899',
            'email' => 'auto-test@example.com',
        ]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    /**
     * Kasus a: Belum lunas, auto_cancel_at sudah lewat -> status menjadi dibatalkan.
     * Menguji TC-AUTO-01.
     */
    public function test_tc_auto_01_belum_lunas_auto_cancel_at_sudah_lewat_status_dibatalkan(): void
    {
        $testNow = Carbon::parse('2026-11-04 10:00:00');
        Carbon::setTestNow($testNow);

        $order = Order::create([
            'order_code' => 'ORD-AUTO-EXPIRED-01',
            'tenant_id' => $this->tenant->id,
            'customer_id' => $this->customer->id,
            'customer_name' => $this->customer->name,
            'status' => 'menunggu',
            'is_paid' => false,
            'auto_cancel_at' => $testNow->copy()->subMinutes(5), // Lewat 5 menit
            'ordered_at' => $testNow->copy()->subMinutes(20),
            'total_amount' => 45000,
        ]);

        $this->artisan('orders:cancel-expired')
            ->expectsOutput('Berhasil membatalkan 1 pesanan kedaluwarsa.')
            ->assertExitCode(0);

        $this->assertEquals('dibatalkan', $order->fresh()->status);
    }

    /**
     * Kasus b: Sudah lunas, auto_cancel_at sudah lewat -> status tetap menunggu.
     * Menguji TC-AUTO-02 (Verifikasi regresi defect DEF-001).
     */
    public function test_tc_auto_02_sudah_lunas_auto_cancel_at_sudah_lewat_status_tetap_menunggu(): void
    {
        $testNow = Carbon::parse('2026-11-04 10:00:00');
        Carbon::setTestNow($testNow);

        $order = Order::create([
            'order_code' => 'ORD-AUTO-PAID-01',
            'tenant_id' => $this->tenant->id,
            'customer_id' => $this->customer->id,
            'customer_name' => $this->customer->name,
            'status' => 'menunggu',
            'is_paid' => true, // Lunas
            'payment_method' => 'qris',
            'auto_cancel_at' => $testNow->copy()->subMinutes(10), // Lewat batas waktu
            'ordered_at' => $testNow->copy()->subMinutes(25),
            'total_amount' => 50000,
        ]);

        $this->artisan('orders:cancel-expired')
            ->assertExitCode(0);

        $this->assertEquals('menunggu', $order->fresh()->status);
    }

    /**
     * Kasus c: Belum lunas, auto_cancel_at belum lewat -> status tetap menunggu.
     * Menguji TC-AUTO-01-C.
     */
    public function test_tc_auto_01_c_belum_lunas_auto_cancel_at_belum_lewat_status_tetap_menunggu(): void
    {
        $testNow = Carbon::parse('2026-11-04 10:00:00');
        Carbon::setTestNow($testNow);

        $order = Order::create([
            'order_code' => 'ORD-AUTO-ACTIVE-01',
            'tenant_id' => $this->tenant->id,
            'customer_id' => $this->customer->id,
            'customer_name' => $this->customer->name,
            'status' => 'menunggu',
            'is_paid' => false,
            'auto_cancel_at' => $testNow->copy()->addMinutes(10), // Masih sisa 10 menit
            'ordered_at' => $testNow->copy()->subMinutes(5),
            'total_amount' => 30000,
        ]);

        $this->artisan('orders:cancel-expired')
            ->assertExitCode(0);

        $this->assertEquals('menunggu', $order->fresh()->status);
    }

    /**
     * Kasus d: Belum lunas, auto_cancel_at tepat sama dengan sekarang -> status menjadi dibatalkan.
     * Menguji TC-AUTO-01-D / boundary value analysis (BVA: auto_cancel_at <= now()).
     */
    public function test_tc_auto_01_d_belum_lunas_auto_cancel_at_tepat_sama_sekarang_status_dibatalkan(): void
    {
        $testNow = Carbon::parse('2026-11-04 10:00:00');
        Carbon::setTestNow($testNow);

        $order = Order::create([
            'order_code' => 'ORD-AUTO-BOUNDARY-01',
            'tenant_id' => $this->tenant->id,
            'customer_id' => $this->customer->id,
            'customer_name' => $this->customer->name,
            'status' => 'menunggu',
            'is_paid' => false,
            'auto_cancel_at' => $testNow, // Tepat di batas waktu sama dengan sekarang
            'ordered_at' => $testNow->copy()->subMinutes(15),
            'total_amount' => 25000,
        ]);

        $this->artisan('orders:cancel-expired')
            ->expectsOutput('Berhasil membatalkan 1 pesanan kedaluwarsa.')
            ->assertExitCode(0);

        $this->assertEquals('dibatalkan', $order->fresh()->status);
    }

    /**
     * Integrasi Batch: Menguji keempat kasus secara bersamaan dan memastikan pesanan non-menunggu aman.
     */
    public function test_tc_auto_batch_eksekusi_empat_kasus_dan_pesanan_diproses(): void
    {
        $testNow = Carbon::parse('2026-11-04 12:00:00');
        Carbon::setTestNow($testNow);

        // a. Belum lunas, lewat batas -> Dibatalkan
        $orderA = Order::create([
            'order_code' => 'ORD-BATCH-A',
            'tenant_id' => $this->tenant->id,
            'customer_id' => $this->customer->id,
            'customer_name' => 'Kasus A',
            'status' => 'menunggu',
            'is_paid' => false,
            'auto_cancel_at' => $testNow->copy()->subMinute(),
            'ordered_at' => $testNow->copy()->subMinutes(16),
            'total_amount' => 20000,
        ]);

        // b. Sudah lunas, lewat batas -> Tetap menunggu
        $orderB = Order::create([
            'order_code' => 'ORD-BATCH-B',
            'tenant_id' => $this->tenant->id,
            'customer_id' => $this->customer->id,
            'customer_name' => 'Kasus B',
            'status' => 'menunggu',
            'is_paid' => true,
            'payment_method' => 'qris',
            'auto_cancel_at' => $testNow->copy()->subMinutes(5),
            'ordered_at' => $testNow->copy()->subMinutes(20),
            'total_amount' => 35000,
        ]);

        // c. Belum lunas, belum lewat batas -> Tetap menunggu
        $orderC = Order::create([
            'order_code' => 'ORD-BATCH-C',
            'tenant_id' => $this->tenant->id,
            'customer_id' => $this->customer->id,
            'customer_name' => 'Kasus C',
            'status' => 'menunggu',
            'is_paid' => false,
            'auto_cancel_at' => $testNow->copy()->addMinutes(10),
            'ordered_at' => $testNow->copy()->subMinutes(5),
            'total_amount' => 40000,
        ]);

        // d. Belum lunas, tepat sama dengan sekarang -> Dibatalkan
        $orderD = Order::create([
            'order_code' => 'ORD-BATCH-D',
            'tenant_id' => $this->tenant->id,
            'customer_id' => $this->customer->id,
            'customer_name' => 'Kasus D',
            'status' => 'menunggu',
            'is_paid' => false,
            'auto_cancel_at' => $testNow,
            'ordered_at' => $testNow->copy()->subMinutes(15),
            'total_amount' => 15000,
        ]);

        // Pesanan sudah diproses tenant walau waktu auto_cancel_at lewat -> Tidak boleh disentuh
        $orderProcessing = Order::create([
            'order_code' => 'ORD-BATCH-PROC',
            'tenant_id' => $this->tenant->id,
            'customer_id' => $this->customer->id,
            'customer_name' => 'Kasus Sedang Diproses',
            'status' => 'diproses',
            'is_paid' => true,
            'payment_method' => 'qris',
            'auto_cancel_at' => $testNow->copy()->subMinutes(30),
            'ordered_at' => $testNow->copy()->subMinutes(45),
            'total_amount' => 60000,
        ]);

        $this->artisan('orders:cancel-expired')
            ->expectsOutput('Berhasil membatalkan 2 pesanan kedaluwarsa.')
            ->assertExitCode(0);

        $this->assertEquals('dibatalkan', $orderA->fresh()->status);
        $this->assertEquals('menunggu', $orderB->fresh()->status);
        $this->assertEquals('menunggu', $orderC->fresh()->status);
        $this->assertEquals('dibatalkan', $orderD->fresh()->status);
        $this->assertEquals('diproses', $orderProcessing->fresh()->status);
    }
}
