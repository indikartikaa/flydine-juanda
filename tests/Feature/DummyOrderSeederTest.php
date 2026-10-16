<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Tenant;
use App\Models\Product;
use App\Models\Customer;
use App\Models\DeliveryLocation;
use App\Models\Order;
use App\Models\Complaint;
use Database\Seeders\DummyOrderSeeder;
use Illuminate\Support\Facades\DB;

class DummyOrderSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Siapkan tenant dan produk untuk tes in-memory SQLite
        $tenant1 = Tenant::create([
            'tenant_code' => 'FB-01-01',
            'name' => 'A&W Restaurant',
            'floor_location' => 'Lantai 1 Gate 3',
            'is_active' => true,
            'delivery_fee' => 10000,
        ]);

        Product::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Aroma Chicken',
            'price' => 30000,
            'is_available' => true,
        ]);

        $tenant2 = Tenant::create([
            'tenant_code' => 'FB-02-05',
            'name' => 'Starbucks Coffee',
            'floor_location' => 'Lantai 1 Gate 5',
            'is_active' => true,
            'delivery_fee' => 15000,
        ]);

        Product::create([
            'tenant_id' => $tenant2->id,
            'name' => 'Caramel Macchiato',
            'price' => 55000,
            'is_available' => true,
        ]);

        DeliveryLocation::create([
            'name' => 'Gate 3 T1',
            'terminal' => '1',
            'zone' => 'Airside',
            'is_active' => true,
        ]);

        Customer::create([
            'name' => 'Pelanggan Asli',
            'phone_number' => '081234567890',
            'total_orders' => 0,
        ]);
    }

    public function test_dummy_order_seeder_runs_successfully_and_is_idempotent()
    {
        $seeder = new DummyOrderSeeder();
        $seeder->run();

        // 1. Pastikan pesanan dummy disisipkan
        $dummyOrdersCount = Order::where('order_code', 'like', 'DUMMY-%')->count();
        $this->assertGreaterThanOrEqual(200, $dummyOrdersCount);

        // 2. Pastikan 3 anomali terbentuk
        $this->assertDatabaseHas('orders', ['order_code' => 'DUMMY-ANOM-001', 'tenant_id' => null]);
        $this->assertDatabaseHas('orders', ['order_code' => 'DUMMY-ANOM-002', 'tenant_id' => null]);
        $this->assertDatabaseHas('orders', ['order_code' => 'DUMMY-ANOM-003']);

        // 3. Pastikan komplain dummy disisipkan
        $dummyComplaintsCount = Complaint::where('complaint_code', 'like', 'DUMMY-%')->count();
        $this->assertEquals(10, $dummyComplaintsCount);

        // 4. Pastikan 30 customer uji terbentuk
        $dummyCustomersCount = Customer::where('phone_number', 'like', '089999%')->count();
        $this->assertEquals(30, $dummyCustomersCount);

        // 5. Uji Idempotensi: Jalankan sekali lagi tanpa error duplicate
        $seeder->run();
        $this->assertEquals($dummyOrdersCount, Order::where('order_code', 'like', 'DUMMY-%')->count());

        // 6. Uji Helper pembersih clean()
        $cleanStats = DummyOrderSeeder::clean();
        $this->assertGreaterThan(0, $cleanStats['deletedOrders']);
        $this->assertEquals(0, Order::where('order_code', 'like', 'DUMMY-%')->count());
        $this->assertEquals(0, Complaint::where('complaint_code', 'like', 'DUMMY-%')->count());
        $this->assertEquals(0, Customer::where('phone_number', 'like', '089999%')->count());
    }

    public function test_dummy_order_seeder_refuses_to_run_in_production()
    {
        app()->detectEnvironment(fn() => 'production');

        $seeder = new DummyOrderSeeder();
        $seeder->run();

        $this->assertEquals(0, Order::where('order_code', 'like', 'DUMMY-%')->count());
    }
}
