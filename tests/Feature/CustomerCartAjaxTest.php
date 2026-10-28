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

    public function test_update_cart_sets_direct_quantity_successfully()
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
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('customer.cart.update'), [
                'product_id' => $this->productA->id,
                'action' => 'set',
                'quantity' => 10,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'jumlah' => 10,
            'subtotal' => 350000,
            'total' => 350000,
            'cart_count' => 1,
        ]);
        $this->assertEquals(10, $response->json('item.quantity'));
        $this->assertEquals(10, session('cart')[$this->productA->id]['quantity']);
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
                'boarding_time' => now()->addHours(2)->format('H:i'),
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

    public function test_checkout_rejects_invalid_phone_number()
    {
        $cart = [
            $this->productA->id => [
                'id' => $this->productA->id,
                'name' => $this->productA->name,
                'price' => $this->productA->price,
                'quantity' => 1,
                'tenant_id' => $this->tenant->id,
            ],
        ];

        // Test with letters
        $responseLetters = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Budi Tester',
                'phone_number' => 'bukan_nomor',
                'customer_type' => 'pengunjung',
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);
        $responseLetters->assertSessionHasErrors('phone_number');

        // Test with too short number
        $responseShort = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Budi Tester',
                'phone_number' => '0812',
                'customer_type' => 'pengunjung',
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);
        $responseShort->assertSessionHasErrors('phone_number');
    }

    public function test_checkout_accepts_valid_formatted_phone_numbers()
    {
        $cart = [
            $this->productA->id => [
                'id' => $this->productA->id,
                'name' => $this->productA->name,
                'price' => $this->productA->price,
                'quantity' => 1,
                'tenant_id' => $this->tenant->id,
            ],
        ];

        $response = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Budi Valid Phone',
                'phone_number' => '+62 812-3456-7890',
                'customer_type' => 'pengunjung',
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('customers', [
            'phone_number' => '+6281234567890',
        ]);
    }

    public function test_passive_auto_cancel_triggers_instantly_on_tracking_and_status_poll()
    {
        $customer = \App\Models\Customer::create([
            'phone_number' => '081234567899',
            'name' => 'Expired Customer',
            'total_orders' => 1,
        ]);

        $order = \App\Models\Order::create([
            'order_code' => 'ORD-EXPTEST',
            'tenant_id' => $this->tenant->id,
            'customer_id' => $customer->id,
            'customer_name' => 'Expired Customer',
            'total_amount' => 35000,
            'status' => 'menunggu',
            'pickup_method' => 'ambil_sendiri',
            'payment_method' => 'qris',
            'is_paid' => false,
            'auto_cancel_at' => now()->subMinutes(1), // Already expired!
            'ordered_at' => now()->subMinutes(16),
        ]);

        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->productA->id,
            'product_name_snapshot' => $this->productA->name,
            'quantity' => 2,
            'unit_price' => 17500,
            'subtotal' => 35000,
        ]);

        // When polling status, it must auto-cancel instantly without cron!
        $statusResponse = $this->get(route('customer.tracking.status', ['order' => 'ORD-EXPTEST']));
        $statusResponse->assertOk();
        $statusResponse->assertJson([
            'status' => 'dibatalkan',
            'is_paid' => false,
        ]);

        $this->assertEquals('dibatalkan', $order->fresh()->status);

        // When visiting tracking page directly
        $trackingResponse = $this->get(route('customer.tracking', ['order' => 'ORD-EXPTEST']));
        $trackingResponse->assertOk();
        $trackingResponse->assertSee('DIBATALKAN');
    }

    public function test_checkout_validates_and_trims_customer_name()
    {
        $cart = [
            $this->productA->id => [
                'id' => $this->productA->id,
                'name' => $this->productA->name,
                'price' => $this->productA->price,
                'quantity' => 1,
                'tenant_id' => $this->tenant->id,
            ],
        ];

        // 1. Rejected if name is only whitespace
        $resWhitespace = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => '   ',
                'phone_number' => '081234567890',
                'customer_type' => 'pengunjung',
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);
        $resWhitespace->assertSessionHasErrors('customer_name');

        // 2. Rejected if name is single character
        $resSingle = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => ' A ',
                'phone_number' => '081234567890',
                'customer_type' => 'pengunjung',
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);
        $resSingle->assertSessionHasErrors('customer_name');

        // 3. Accepted and trimmed cleanly if valid
        $resValid = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => '   Agus   Pratama   ',
                'phone_number' => '081299112233',
                'customer_type' => 'pengunjung',
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);
        $resValid->assertSessionHasNoErrors();
        $this->assertDatabaseHas('customers', [
            'name' => 'Agus Pratama',
            'phone_number' => '081299112233',
        ]);
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Agus Pratama',
        ]);
    }

    public function test_checkout_normalizes_and_uppercases_flight_number(): void
    {
        $cart = [
            $this->productA->id => [
                'id' => $this->productA->id,
                'name' => $this->productA->name,
                'price' => $this->productA->price,
                'quantity' => 1,
                'tenant_id' => $this->tenant->id,
            ],
        ];

        // Penumpang mengetik nomor penerbangan dengan huruf kecil dan spasi berlebih
        $boardingTime = now()->addHours(2)->format('H:i');
        $response = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Budi Santoso',
                'phone_number' => '081234567890',
                'customer_type' => 'penumpang',
                'flight_number' => '  ga 320  ',
                'boarding_time' => $boardingTime,
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Budi Santoso',
            'flight_number' => 'GA 320',
        ]);
    }

    public function test_checkout_saves_order_notes_and_quick_chips(): void
    {
        $cart = [
            $this->productA->id => [
                'id' => $this->productA->id,
                'name' => $this->productA->name,
                'price' => $this->productA->price,
                'quantity' => 2,
                'tenant_id' => $this->tenant->id,
            ],
        ];

        $boardingTime = now()->addHours(2)->format('H:i');
        $notesInput = '  Kurangi Es, Sambal Dipisah, Bungkus Kabin  ';

        $response = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Rina Kartika',
                'phone_number' => '081234567890',
                'customer_type' => 'penumpang',
                'flight_number' => 'GA-188',
                'boarding_time' => $boardingTime,
                'notes' => $notesInput,
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Rina Kartika',
            'notes' => 'Kurangi Es, Sambal Dipisah, Bungkus Kabin',
        ]);

        $order = \App\Models\Order::where('customer_name', 'Rina Kartika')->latest()->first();

        // Verifikasi struk pelanggan menampilkan catatan
        $receiptResponse = $this->get(route('customer.receipt', $order->order_code));
        $receiptResponse->assertStatus(200);
        $receiptResponse->assertSee('Kurangi Es, Sambal Dipisah, Bungkus Kabin');

        // Verifikasi layar tracking pelanggan menampilkan catatan
        $trackingResponse = $this->get(route('customer.tracking', ['order' => $order->order_code]));
        $trackingResponse->assertStatus(200);
        $trackingResponse->assertSee('Kurangi Es, Sambal Dipisah, Bungkus Kabin');
    }

    public function test_checkout_rejects_notes_over_100_characters(): void
    {
        $cart = [
            $this->productA->id => [
                'id' => $this->productA->id,
                'name' => $this->productA->name,
                'price' => $this->productA->price,
                'quantity' => 1,
                'tenant_id' => $this->tenant->id,
            ],
        ];

        $boardingTime = now()->addHours(2)->format('H:i');
        $longNotes = str_repeat('Tolong sambalnya dipisah ya mas jangan pedas ', 3); // > 100 karakter

        $response = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Dewi Lestari',
                'phone_number' => '081234567890',
                'customer_type' => 'penumpang',
                'flight_number' => 'JT-102',
                'boarding_time' => $boardingTime,
                'notes' => $longNotes,
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);

        $response->assertSessionHasErrors(['notes']);
        $this->assertStringContainsString('maksimal 100 karakter', session('errors')->first('notes'));
    }
}

