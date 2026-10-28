<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Tenant;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Customer;
use App\Models\DeliveryLocation;

class OrderLifecycleEndToEndTest extends TestCase
{
    use RefreshDatabase;

    private Tenant $tenant;
    private Product $product;
    private User $tenantStaff;
    private DeliveryLocation $deliveryLocation;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup Tenant & Staff
        $this->tenant = Tenant::create([
            'tenant_code' => 'T-E2E-01',
            'name' => 'Juanda Delicious Cafe',
            'floor_location' => 'Lantai 1 - Gate 3',
            'terminal' => 'T1',
            'is_active' => true,
            'opening_time' => '05:00',
            'closing_time' => '23:00',
            'delivery_fee' => 8000,
        ]);

        $this->tenantStaff = User::factory()->create([
            'role' => 'tenant_staff',
            'tenant_id' => $this->tenant->id,
            'is_active' => true,
        ]);

        // 2. Setup Produk dengan Stok
        $this->product = Product::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Signature Croissant Vanilla',
            'category' => 'Bakery & Pastry',
            'description' => 'Croissant renyah dengan isian vanilla lembut',
            'price' => 25000,
            'stock' => 20,
            'preparation_time' => 10,
            'is_available' => true,
        ]);

        // 3. Setup Lokasi Antar
        $this->deliveryLocation = DeliveryLocation::create([
            'terminal' => '1',
            'name' => 'Gate 3 Keberangkatan',
            'fee' => 8000,
            'is_active' => true,
        ]);
    }

    /**
     * Skenario 1: Alur Lengkap Penumpang Pesawat (Order -> Bayar -> Tenant Masak -> Siap -> Selesai -> Struk -> Riwayat)
     */
    public function test_complete_passenger_order_flow_from_checkout_to_completion()
    {
        // A. Cart Session
        $cart = [
            $this->product->id => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'quantity' => 2,
                'tenant_id' => $this->tenant->id,
            ]
        ];

        // B. Checkout Penumpang
        $checkoutResponse = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Andi Penumpang',
                'phone_number' => '081298765432',
                'customer_type' => 'penumpang',
                'flight_number' => 'GA-305',
                'boarding_time' => now()->addHours(2)->format('H:i'),
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);

        $checkoutResponse->assertSessionHasNoErrors();
        
        $order = Order::where('customer_name', 'Andi Penumpang')->first();
        $this->assertNotNull($order);
        $this->assertEquals(50000, $order->total_amount);
        $this->assertEquals('menunggu', $order->status);
        $this->assertFalse((bool) $order->is_paid);
        $this->assertEquals('GA-305', $order->flight_number);
        
        // Verifikasi stok terpotong (20 - 2 = 18)
        $this->assertEquals(18, $this->product->fresh()->stock);

        // C. Simulasi Pembayaran
        $payResponse = $this->post(route('customer.simulate_payment'), [
            'order_code' => $order->order_code,
        ]);
        $payResponse->assertRedirect(route('customer.tracking', ['order' => $order->order_code]));
        $this->assertTrue((bool) $order->fresh()->is_paid);

        // D. Tenant Melihat Pesanan Masuk
        $tenantOrdersResponse = $this->actingAs($this->tenantStaff)->get(route('tenant.orders'));
        $tenantOrdersResponse->assertStatus(200);
        $tenantOrdersResponse->assertSee($order->order_code);

        // E. Tenant Mulai Memasak (diproses)
        $processResponse = $this->actingAs($this->tenantStaff)
            ->postJson(route('tenant.orders.status', $order->id), [
                'status' => 'diproses',
            ]);
        $processResponse->assertStatus(200);
        $this->assertEquals('diproses', $order->fresh()->status);

        // F. Tenant Menyatakan Pesanan Siap (siap)
        $readyResponse = $this->actingAs($this->tenantStaff)
            ->postJson(route('tenant.orders.status', $order->id), [
                'status' => 'siap',
            ]);
        $readyResponse->assertStatus(200);
        $freshOrder = $order->fresh();
        $this->assertEquals('siap', $freshOrder->status);
        $this->assertNotNull($freshOrder->ready_at);

        // G. Tenant Menyelesaikan Pesanan (selesai)
        $completeResponse = $this->actingAs($this->tenantStaff)
            ->postJson(route('tenant.orders.status', $order->id), [
                'status' => 'selesai',
            ]);
        $completeResponse->assertStatus(200);
        $completedOrder = $order->fresh();
        $this->assertEquals('selesai', $completedOrder->status);
        $this->assertNotNull($completedOrder->completed_at);

        // H. Verifikasi Struk Digital Resmi (receipt)
        $receiptResponse = $this->get(route('customer.receipt', ['order' => $order->order_code]));
        $receiptResponse->assertStatus(200);
        $receiptResponse->assertSee($order->order_code);
        $receiptResponse->assertSee('LUNAS');
        $receiptResponse->assertSee('Selesai');
        $receiptResponse->assertSee('Signature Croissant Vanilla');

        // I. Verifikasi Riwayat Pesanan Customer (history)
        $historyResponse = $this->get(route('customer.history', ['phone_number' => '081298765432']));
        $historyResponse->assertStatus(200);
        $historyResponse->assertSee($order->order_code);
        $historyResponse->assertSee('LUNAS');
    }

    /**
     * Skenario 2: Pengunjung / Staff Bandara dengan Layanan Antar Porter
     */
    public function test_visitor_order_flow_with_porter_delivery()
    {
        $cart = [
            $this->product->id => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'quantity' => 1,
                'tenant_id' => $this->tenant->id,
            ]
        ];

        // Checkout sebagai pengunjung (tanpa flight number & tanpa boarding time)
        $response = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Bambang Staff Bandara',
                'phone_number' => '085712345678',
                'customer_type' => 'pengunjung',
                'pickup_method' => 'diantar',
                'delivery_location_id' => $this->deliveryLocation->id,
                'payment_method' => 'transfer',
            ]);

        $response->assertSessionHasNoErrors();
        
        $order = Order::where('customer_name', 'Bambang Staff Bandara')->first();
        $this->assertNotNull($order);
        // Total = 25.000 (menu) + 8.000 (ongkir antar) = 33.000
        $this->assertEquals(33000, $order->total_amount);
        $this->assertEquals('diantar', $order->pickup_method);
        $this->assertEquals($this->deliveryLocation->id, $order->delivery_location_id);
        $this->assertNull($order->flight_number);

        // Halaman pelacakan tidak boleh error dan menampilkan informasi antar
        $trackingResponse = $this->get(route('customer.tracking', ['order' => $order->order_code]));
        $trackingResponse->assertStatus(200);
        $trackingResponse->assertSee('Bambang Staff Bandara');
    }

    /**
     * Skenario 3: Pembatalan Pesanan oleh Customer Sebelum Bayar Mengembalikan Stok
     */
    public function test_customer_cancelling_order_restores_product_stock()
    {
        $this->assertEquals(20, $this->product->stock);

        $cart = [
            $this->product->id => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'quantity' => 4,
                'tenant_id' => $this->tenant->id,
            ]
        ];

        $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Citra Pengunjung',
                'phone_number' => '087812349999',
                'customer_type' => 'pengunjung',
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);

        // Stok awal berkurang 4 (20 - 4 = 16)
        $this->assertEquals(16, $this->product->fresh()->stock);

        $order = Order::where('customer_name', 'Citra Pengunjung')->first();
        $this->assertFalse((bool) $order->is_paid);

        // Customer membatalkan pesanan
        $cancelResponse = $this->post(route('customer.tracking.cancel', ['order' => $order->order_code]));
        $cancelResponse->assertRedirect(route('customer.tracking', ['order' => $order->order_code]));

        $this->assertEquals('dibatalkan', $order->fresh()->status);
        // Stok harus kembali utuh menjadi 20
        $this->assertEquals(20, $this->product->fresh()->stock);
    }

    /**
     * Skenario 4: Penolakan Pesanan oleh Tenant Mengembalikan Stok
     */
    public function test_tenant_rejecting_order_restores_product_stock()
    {
        $cart = [
            $this->product->id => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'quantity' => 3,
                'tenant_id' => $this->tenant->id,
            ]
        ];

        $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Dewi Penumpang',
                'phone_number' => '081399887766',
                'customer_type' => 'penumpang',
                'flight_number' => 'JT-990',
                'boarding_time' => now()->addHours(3)->format('H:i'),
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);

        $this->assertEquals(17, $this->product->fresh()->stock);

        $order = Order::where('customer_name', 'Dewi Penumpang')->first();
        // Simulasi bayar
        $this->post(route('customer.simulate_payment'), ['order_code' => $order->order_code]);

        // Tenant menolak pesanan
        $rejectResponse = $this->actingAs($this->tenantStaff)
            ->postJson(route('tenant.orders.status', $order->id), [
                'status' => 'ditolak',
            ]);

        $rejectResponse->assertStatus(200);
        $this->assertEquals('dibatalkan', $order->fresh()->status);
        // Stok harus otomatis dikembalikan ke 20
        $this->assertEquals(20, $this->product->fresh()->stock);
    }

    /**
     * Skenario 5: Validasi Pengantaran Lintas Terminal (Ditolak jika Tenant T1 diantar ke Gate T2)
     */
    public function test_cross_terminal_delivery_is_strictly_rejected()
    {
        // Buat lokasi antar di Terminal 2 (Internasional)
        $t2Location = DeliveryLocation::create([
            'terminal' => '2',
            'name' => 'Gate 2 Internasional T2',
            'is_active' => true,
        ]);

        $cart = [
            $this->product->id => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'quantity' => 1,
                'tenant_id' => $this->tenant->id, // Tenant berada di Terminal 1
            ]
        ];

        // Coba checkout pesanan tenant T1 dengan titik antar di T2
        $response = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Eko Lintas Terminal',
                'phone_number' => '081233445566',
                'customer_type' => 'pengunjung',
                'pickup_method' => 'diantar',
                'delivery_location_id' => $t2Location->id, // Lokasi di T2!
                'payment_method' => 'qris',
            ]);

        // Harus gagal validasi dan ditolak
        $response->assertSessionHasErrors('delivery_location_id');
        $this->assertDatabaseMissing('orders', [
            'customer_name' => 'Eko Lintas Terminal',
        ]);
    }

    /**
     * Skenario 6: Validasi Restoran Tutup Saat Checkout (Ditolak dengan Pesan Jelas)
     */
    public function test_closed_tenant_checkout_is_rejected()
    {
        // Nonaktifkan tenant
        $this->tenant->update(['is_active' => false]);

        $cart = [
            $this->product->id => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'quantity' => 1,
                'tenant_id' => $this->tenant->id,
            ]
        ];

        $response = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Fajar Tamu',
                'phone_number' => '081288990011',
                'customer_type' => 'pengunjung',
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);

        $response->assertRedirect(route('customer.cart'));
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('orders', [
            'customer_name' => 'Fajar Tamu',
        ]);

        // Uji coba berdasarkan Jam Operasional (Tenant aktif, tapi jam sekarang di luar jam buka)
        $nowH = (int) now('Asia/Jakarta')->format('H');
        // Set jam buka di masa lampau (misal 3 jam lalu s.d. 2 jam lalu)
        $pastOpen = sprintf('%02d:00', ($nowH - 3 + 24) % 24);
        $pastClose = sprintf('%02d:00', ($nowH - 2 + 24) % 24);

        $this->tenant->update([
            'is_active' => true,
            'opening_time' => $pastOpen,
            'closing_time' => $pastClose,
        ]);
        $this->assertFalse($this->tenant->isOpen());

        $response2 = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Fajar Jam Tutup',
                'phone_number' => '081288990022',
                'customer_type' => 'pengunjung',
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);

        $response2->assertRedirect(route('customer.cart'));
        $response2->assertSessionHas('error');
        $this->assertDatabaseMissing('orders', [
            'customer_name' => 'Fajar Jam Tutup',
        ]);
    }

    /**
     * Skenario 7: Validasi Waktu Boarding Terlalu Mepet (< 15 Menit) Ditolak
     */
    public function test_boarding_time_under_15_minutes_is_rejected()
    {
        $cart = [
            $this->product->id => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'quantity' => 1,
                'tenant_id' => $this->tenant->id,
            ]
        ];

        // Boarding time hanya 5 menit dari sekarang (mepet sekali)
        $tightBoardingTime = now()->addMinutes(5)->format('H:i');

        $response = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Gilang Terburu',
                'phone_number' => '081277665544',
                'customer_type' => 'penumpang',
                'flight_number' => 'QG-880',
                'boarding_time' => $tightBoardingTime,
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);

        $response->assertSessionHasErrors('boarding_time');
        $this->assertDatabaseMissing('orders', [
            'customer_name' => 'Gilang Terburu',
        ]);
    }

    /**
     * Skenario 8: Checkout dengan Virtual Account Bank Spesifik (Mandiri) Berhasil
     */
    public function test_checkout_with_bank_specific_virtual_account_succeeds()
    {
        $cart = [
            $this->product->id => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'quantity' => 2,
                'tenant_id' => $this->tenant->id,
            ]
        ];

        $response = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Maya Mandiri',
                'phone_number' => '081299887766',
                'customer_type' => 'pengunjung',
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'transfer_mandiri',
            ]);

        $order = Order::where('customer_name', 'Maya Mandiri')->first();
        $this->assertNotNull($order);
        $this->assertEquals('transfer_mandiri', $order->payment_method);
        $this->assertEquals('mandiri', $order->selected_bank);
        $this->assertEquals('Mandiri Virtual Account', $order->payment_method_label);

        $response->assertRedirect(route('customer.tracking', ['order' => $order->order_code]));

        $trackingResponse = $this->get(route('customer.tracking', ['order' => $order->order_code]));
        $trackingResponse->assertOk();
        $trackingResponse->assertSee('Mandiri Virtual Account');
    }
}



