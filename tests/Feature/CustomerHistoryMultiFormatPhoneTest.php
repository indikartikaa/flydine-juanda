<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Tenant;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;

class CustomerHistoryMultiFormatPhoneTest extends TestCase
{
    use RefreshDatabase;

    private Tenant $tenant;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'tenant_code' => 'T-TEST-HIST',
            'name' => 'Restoran Juanda Test',
            'floor_location' => 'Lantai 1 Gate 2',
            'is_active' => true,
            'opening_time' => '00:00',
            'closing_time' => '23:59',
            'delivery_fee' => 5000,
        ]);

        $this->product = Product::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Nasi Rawon Juanda',
            'category' => 'Makanan',
            'description' => 'Rawon daging lezat',
            'price' => 45000,
            'stock' => 50,
            'preparation_time' => 15,
            'is_available' => true,
        ]);
    }

    public function test_history_search_finds_order_using_different_phone_formats(): void
    {
        // 1. Customer registered with local format 081234567890
        $customer = Customer::create([
            'phone_number' => '081234567890',
            'name' => 'Budi Santoso',
            'first_order_at' => now(),
            'last_order_at' => now(),
            'total_orders' => 1,
        ]);

        $order = Order::create([
            'order_code' => 'ORD-LOCAL-001',
            'tenant_id' => $this->tenant->id,
            'customer_id' => $customer->id,
            'customer_name' => 'Budi Santoso',
            'flight_number' => 'GA-123',
            'boarding_time' => now()->addHours(2),
            'pickup_method' => 'ambil_sendiri',
            'status' => 'selesai',
            'payment_method' => 'qris',
            'is_paid' => true,
            'auto_cancel_at' => now()->addMinutes(15),
            'total_amount' => 45000,
            'ordered_at' => now(),
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'product_name_snapshot' => $this->product->name,
            'quantity' => 1,
            'unit_price' => 45000,
            'subtotal' => 45000,
        ]);

        // Skenario A: Cari dengan format lokal yang sama persis (081234567890)
        $responseLocal = $this->get(route('customer.history', ['phone_number' => '081234567890']));
        $responseLocal->assertStatus(200);
        $responseLocal->assertSee('ORD-LOCAL-001');
        $responseLocal->assertSee('Budi Santoso');
        $responseLocal->assertSee('Nasi Rawon Juanda');

        // Skenario B: Cari dengan format internasional dengan plus (+6281234567890)
        $responsePlus62 = $this->get(route('customer.history', ['phone_number' => '+6281234567890']));
        $responsePlus62->assertStatus(200);
        $responsePlus62->assertSee('ORD-LOCAL-001');
        $responsePlus62->assertSee('Budi Santoso');

        // Skenario C: Cari dengan format internasional tanpa plus (6281234567890)
        $response62 = $this->get(route('customer.history', ['phone_number' => '6281234567890']));
        $response62->assertStatus(200);
        $response62->assertSee('ORD-LOCAL-001');

        // Skenario D: Cari dengan format spasi dan strip (0812-3456-7890 atau +62 812-3456-7890)
        $responseFormatted = $this->get(route('customer.history', ['phone_number' => '0812-3456-7890']));
        $responseFormatted->assertStatus(200);
        $responseFormatted->assertSee('ORD-LOCAL-001');

        $responseFormattedPlus = $this->get(route('customer.history', ['phone_number' => '+62 812-3456-7890']));
        $responseFormattedPlus->assertStatus(200);
        $responseFormattedPlus->assertSee('ORD-LOCAL-001');
    }

    public function test_history_search_finds_order_when_customer_saved_with_plus_62(): void
    {
        // Customer registered with international format +6281987654321
        $customer = Customer::create([
            'phone_number' => '+6281987654321',
            'name' => 'Siti Rahma',
            'first_order_at' => now(),
            'last_order_at' => now(),
            'total_orders' => 1,
        ]);

        $order = Order::create([
            'order_code' => 'ORD-INTL-002',
            'tenant_id' => $this->tenant->id,
            'customer_id' => $customer->id,
            'customer_name' => 'Siti Rahma',
            'flight_number' => 'JT-456',
            'boarding_time' => now()->addHours(3),
            'pickup_method' => 'ambil_sendiri',
            'status' => 'diproses',
            'payment_method' => 'transfer_bca',
            'is_paid' => true,
            'auto_cancel_at' => now()->addMinutes(15),
            'total_amount' => 45000,
            'ordered_at' => now(),
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'product_name_snapshot' => $this->product->name,
            'quantity' => 1,
            'unit_price' => 45000,
            'subtotal' => 45000,
        ]);

        // Customer mencari dengan format lokal 081987654321
        $response = $this->get(route('customer.history', ['phone_number' => '081987654321']));
        $response->assertStatus(200);
        $response->assertSee('ORD-INTL-002');
        $response->assertSee('Siti Rahma');
    }
}
