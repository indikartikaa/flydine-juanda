<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Tenant;
use App\Models\Product;

class CustomerCartAjaxTest extends TestCase
{
    use RefreshDatabase;

    private Tenant $tenant;
    private Product $productA;
    private Product $productB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'tenant_code' => 'T-TEST-01',
            'name' => 'Restoran Juanda Test',
            'floor_location' => 'Lantai 1 Gate 2',
            'is_active' => true,
            'opening_time' => '00:00',
            'closing_time' => '23:59',
            'delivery_fee' => 5000,
        ]);

        $this->productA = Product::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Nasi Goreng Spesial',
            'category' => 'Makanan',
            'description' => 'Nasi goreng lezat dengan telur dan ayam',
            'price' => 35000,
            'stock' => 50,
            'preparation_time' => 15,
            'is_available' => true,
        ]);

        $this->productB = Product::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Es Teh Manis',
            'category' => 'Minuman',
            'description' => 'Es teh segar',
            'price' => 10000,
            'stock' => 100,
            'preparation_time' => 5,
            'is_available' => true,
        ]);
    }

    public function test_add_to_cart_returns_json_when_requested()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('customer.cart.add'), [
            'product_id' => $this->productA->id,
            'tenant_id' => $this->tenant->id,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'cart_count',
            'item' => [
                'id',
                'name',
                'price',
                'quantity',
                'tenant_id',
            ],
            'jumlah',
            'subtotal',
            'total',
            'cart',
        ]);

        $data = $response->json();
        $this->assertTrue($data['success']);
        $this->assertEquals(1, $data['jumlah']);
        $this->assertEquals(35000, $data['subtotal']);
        $this->assertEquals(35000, $data['total']);
        $this->assertEquals(1, $data['cart_count']);
        $this->assertEquals($this->productA->id, $data['item']['id']);
    }

    public function test_add_to_cart_increments_existing_item_quantity_in_json()
    {
        // Add first time
        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route('customer.cart.add'), [
                'product_id' => $this->productA->id,
                'tenant_id' => $this->tenant->id,
            ]);

        // Add second time
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('customer.cart.add'), [
            'product_id' => $this->productA->id,
            'tenant_id' => $this->tenant->id,
        ]);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals(2, $data['jumlah']);
        $this->assertEquals(70000, $data['subtotal']);
        $this->assertEquals(70000, $data['total']);
    }

    public function test_add_to_cart_still_redirects_for_non_json_requests()
    {
        $response = $this->from('/menu/' . $this->tenant->id)->post(route('customer.cart.add'), [
            'product_id' => $this->productA->id,
            'tenant_id' => $this->tenant->id,
        ]);

        $response->assertRedirect('/menu/' . $this->tenant->id);
        $response->assertSessionHas('success');
    }

    public function test_update_cart_increases_quantity_and_returns_json()
    {
        // Seed session cart
        $cart = [
            $this->productA->id => [
                'id' => $this->productA->id,
                'name' => $this->productA->name,
                'price' => 35000,
                'quantity' => 1,
                'tenant_id' => $this->tenant->id,
            ],
        ];

        $response = $this->withSession(['cart' => $cart])
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('customer.cart.update'), [
                'product_id' => $this->productA->id,
                'action' => 'increase',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'jumlah' => 2,
            'subtotal' => 70000,
            'total' => 70000,
            'cart_count' => 1,
        ]);
        $this->assertEquals(2, $response->json('item.quantity'));
    }

    public function test_update_cart_decreases_quantity_and_returns_json()
    {
        // Seed session cart with quantity = 2
        $cart = [
            $this->productA->id => [
                'id' => $this->productA->id,
                'name' => $this->productA->name,
                'price' => 35000,
                'quantity' => 2,
                'tenant_id' => $this->tenant->id,
            ],
        ];

        $response = $this->withSession(['cart' => $cart])
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('customer.cart.update'), [
                'product_id' => $this->productA->id,
                'action' => 'decrease',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'jumlah' => 1,
            'subtotal' => 35000,
            'total' => 35000,
            'cart_count' => 1,
        ]);
        $this->assertEquals(1, $response->json('item.quantity'));
    }

    public function test_update_cart_removes_item_when_quantity_reaches_zero()
    {
        // Seed session cart with quantity = 1
        $cart = [
            $this->productA->id => [
                'id' => $this->productA->id,
                'name' => $this->productA->name,
                'price' => 35000,
                'quantity' => 1,
                'tenant_id' => $this->tenant->id,
            ],
        ];

        $response = $this->withSession(['cart' => $cart])
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('customer.cart.update'), [
                'product_id' => $this->productA->id,
                'action' => 'decrease',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'item' => null,
            'jumlah' => 0,
            'subtotal' => 0,
            'total' => 0,
            'cart_count' => 0,
        ]);

        $this->assertEmpty(session('cart'));
    }

    public function test_update_cart_still_redirects_for_non_json_requests()
    {
        $cart = [
            $this->productA->id => [
                'id' => $this->productA->id,
                'name' => $this->productA->name,
                'price' => 35000,
                'quantity' => 1,
                'tenant_id' => $this->tenant->id,
            ],
        ];

        $response = $this->withSession(['cart' => $cart])
            ->post(route('customer.cart.update'), [
                'product_id' => $this->productA->id,
                'action' => 'increase',
            ]);

        $response->assertRedirect(route('customer.cart'));
    }

    public function test_clear_cart_returns_json_and_empties_cart()
    {
        $cart = [
            $this->productA->id => [
                'id' => $this->productA->id,
                'name' => $this->productA->name,
                'price' => 35000,
                'quantity' => 1,
                'tenant_id' => $this->tenant->id,
            ],
            $this->productB->id => [
                'id' => $this->productB->id,
                'name' => $this->productB->name,
                'price' => 10000,
                'quantity' => 2,
                'tenant_id' => $this->tenant->id,
            ],
        ];

        $response = $this->withSession(['cart' => $cart])
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('customer.cart.clear'));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'item' => null,
            'jumlah' => 0,
            'subtotal' => 0,
            'total' => 0,
            'cart_count' => 0,
            'cart' => [],
        ]);

        $this->assertNull(session('cart'));
    }

    public function test_clear_cart_still_redirects_for_non_json_requests()
    {
        $cart = [
            $this->productA->id => [
                'id' => $this->productA->id,
                'name' => $this->productA->name,
                'price' => 35000,
                'quantity' => 1,
                'tenant_id' => $this->tenant->id,
            ],
        ];

        $response = $this->withSession(['cart' => $cart])
            ->from(route('customer.cart'))
            ->post(route('customer.cart.clear'));

        $response->assertRedirect(route('customer.cart'));
    }

    public function test_checkout_creates_order_successfully()
    {
        $cart = [
            $this->productA->id => [
                'id' => $this->productA->id,
                'name' => $this->productA->name,
                'price' => 35000,
                'quantity' => 1,
                'tenant_id' => $this->tenant->id,
            ],
        ];

        $response = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Budi Passenger',
                'phone_number' => '081234567890',
                'customer_type' => 'penumpang',
                'flight_number' => 'GA-123',
                'boarding_time' => '18:30',
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Budi Passenger',
            'flight_number' => 'GA-123',
            'total_amount' => 35000,
            'status' => 'menunggu',
        ]);
        $this->assertDatabaseHas('order_items', [
            'product_id' => $this->productA->id,
            'quantity' => 1,
            'subtotal' => 35000,
        ]);
        $this->assertNull(session('cart'));
    }

    public function test_simulate_payment_confirms_payment_successfully()
    {
        $customer = \App\Models\Customer::create([
            'phone_number' => '081234567890',
            'name' => 'Budi Passenger',
            'total_orders' => 1,
        ]);

        $order = \App\Models\Order::create([
            'order_code' => 'ORD-PAYTEST',
            'tenant_id' => $this->tenant->id,
            'customer_id' => $customer->id,
            'customer_name' => 'Budi Passenger',
            'total_amount' => 35000,
            'status' => 'menunggu',
            'pickup_method' => 'ambil_sendiri',
            'payment_method' => 'qris',
            'is_paid' => false,
            'auto_cancel_at' => now()->addMinutes(15),
            'ordered_at' => now(),
        ]);

        $response = $this->post(route('customer.simulate_payment'), [
            'order_code' => 'ORD-PAYTEST',
        ]);

        $response->assertRedirect(route('customer.tracking', ['order' => 'ORD-PAYTEST']));
        $this->assertTrue($order->fresh()->is_paid);
    }
}
