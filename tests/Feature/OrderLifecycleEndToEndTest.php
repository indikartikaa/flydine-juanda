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

    /**
     * Skenario 9: Validasi Zona Bandara Opsi A (Restoran Airside Khusus Penumpang & Porter Tidak Boleh Lintas Zona Landside <-> Airside)
     */
    public function test_visitor_is_restricted_from_airside_self_pickup_and_boarding_lounge_delivery()
    {
        $boardingLoungeLocation = DeliveryLocation::create([
            'terminal' => '1',
            'name' => 'Boarding Lounge Lantai 2 (Gate 4) T1',
            'is_active' => true,
        ]);

        // Ubah zona tenant menjadi Airside (Area Steril Ruang Tunggu Boarding)
        $this->tenant->update(['zone' => 'Airside']);

        $cart = [
            $this->product->id => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'quantity' => 1,
                'tenant_id' => $this->tenant->id,
            ]
        ];

        // 1. Pengunjung mencoba memesan di restoran Airside -> Harus ditolak pada customer_type
        $visitorOnAirsideResponse = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Rudi Pengunjung Umum',
                'phone_number' => '081255667788',
                'customer_type' => 'pengunjung',
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);

        $visitorOnAirsideResponse->assertSessionHasErrors('customer_type');
        $this->assertDatabaseMissing('orders', [
            'customer_name' => 'Rudi Pengunjung Umum',
        ]);

        // 2. Penumpang di restoran Airside mencoba Diantar Porter ke titik Landside -> Harus ditolak (Porter tidak lintas zona Airside -> Landside)
        $airsideToLandsideResponse = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Penumpang Lintas Zona',
                'phone_number' => '081255667788',
                'customer_type' => 'penumpang',
                'flight_number' => 'GA-301',
                'boarding_time' => now()->addHours(2)->format('H:i'),
                'pickup_method' => 'diantar',
                'delivery_location_id' => $this->deliveryLocation->id, // Lokasi Landside
                'payment_method' => 'qris',
            ]);

        $airsideToLandsideResponse->assertSessionHasErrors('delivery_location_id');

        // 3. Kembalikan tenant ke Landside, lalu coba Diantar Porter ke titik Boarding Lounge (Airside) -> Harus ditolak (Porter tidak lintas zona Landside -> Airside)
        $this->tenant->update(['zone' => 'Landside']);

        $landsideToAirsideResponse = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Rudi Lintas Ke Gate',
                'phone_number' => '081255667788',
                'customer_type' => 'penumpang',
                'flight_number' => 'GA-301',
                'boarding_time' => now()->addHours(2)->format('H:i'),
                'pickup_method' => 'diantar',
                'delivery_location_id' => $boardingLoungeLocation->id, // Lokasi Boarding Lounge (Airside)
                'payment_method' => 'qris',
            ]);

        $landsideToAirsideResponse->assertSessionHasErrors('delivery_location_id');

        // 4. Endpoint API delivery-locations memfilter sesuai zona
        $airsideLocs = collect($this->getJson('/api/delivery-locations?terminal=1&zone=Airside')->json())->pluck('name')->all();
        $this->assertContains('Boarding Lounge Lantai 2 (Gate 4) T1', $airsideLocs);
        $this->assertNotContains('Gate 3 Keberangkatan', $airsideLocs);

        $landsideLocs = collect($this->getJson('/api/delivery-locations?terminal=1&zone=Landside')->json())->pluck('name')->all();
        $this->assertContains('Gate 3 Keberangkatan', $landsideLocs);
        $this->assertNotContains('Boarding Lounge Lantai 2 (Gate 4) T1', $landsideLocs);
    }

    /**
     * Skenario 10: Validasi simulatePayment Ditolak Jika Pesanan Sudah Kedaluwarsa (Auto-Cancel) atau Dibatalkan
     */
    public function test_simulate_payment_is_rejected_on_expired_or_cancelled_order()
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

        $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Doni Telat Bayar',
                'phone_number' => '081266778899',
                'customer_type' => 'pengunjung',
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);

        $order = Order::where('customer_name', 'Doni Telat Bayar')->firstOrFail();

        // Stok terpotong dari 20 -> 18
        $this->product->refresh();
        $this->assertEquals(18, $this->product->stock);

        // Simulasikan waktu bayar 15 menit sudah habis (auto_cancel_at di masa lalu)
        $order->update(['auto_cancel_at' => now()->subMinutes(2)]);

        // Pelanggan menekan tombol "Konfirmasi Saya Sudah Bayar" di tab yang masih terbuka
        $response = $this->withSession(['order_code' => $order->order_code])
            ->post(route('customer.simulate_payment'), [
                'order_code' => $order->order_code,
            ]);

        $response->assertRedirect(route('customer.tracking', ['order' => $order->order_code]));
        $response->assertSessionHas('error');

        // Pastikan status berubah menjadi 'dibatalkan', is_paid tetap false, dan stok dikembalikan ke 20
        $order->refresh();
        $this->assertEquals('dibatalkan', $order->status);
        $this->assertFalse((bool) $order->is_paid);

        $this->product->refresh();
        $this->assertEquals(20, $this->product->stock);

        // Coba konfirmasi bayar sekali lagi pada pesanan yang sudah 'dibatalkan' -> Tetap ditolak & tidak memunculkan klaim Refund
        $secondAttempt = $this->post(route('customer.simulate_payment'), [
            'order_code' => $order->order_code,
        ]);

        $secondAttempt->assertRedirect(route('customer.tracking', ['order' => $order->order_code]));
        $secondAttempt->assertSessionHas('error');

        $order->refresh();
        $this->assertFalse((bool) $order->is_paid);

        // Pastikan halaman tracking tidak menampilkan kotak Refund 100%
        $trackingView = $this->get(route('customer.tracking', ['order' => $order->order_code]));
        $trackingView->assertOk();
        $trackingView->assertDontSee('Informasi Pengembalian Dana (Refund 100%)');
    }

    /**
     * Skenario 11: Validasi Tenant Dilarang Memasak Pesanan yang Belum Dibayar (is_paid = false) atau Sudah Dibatalkan
     */
    public function test_tenant_cannot_process_unpaid_or_cancelled_order()
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

        $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Eko Belum Bayar',
                'phone_number' => '081277889900',
                'customer_type' => 'pengunjung',
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);

        $order = Order::where('customer_name', 'Eko Belum Bayar')->firstOrFail();
        $this->assertFalse((bool) $order->is_paid);

        // 1. Tenant mencoba mengubah status ke 'diproses' saat is_paid = false -> Harus ditolak (422)
        $unpaidResponse = $this->actingAs($this->tenantStaff)
            ->postJson("/tenant/orders/{$order->id}/status", [
                'status' => 'diproses',
            ]);

        $unpaidResponse->assertStatus(422)
            ->assertJson(['success' => false]);

        $order->refresh();
        $this->assertEquals('menunggu', $order->status);

        // 2. Setelah pelanggan bayar (is_paid = true), barulah tenant diizinkan mengubah status ke 'diproses'
        $this->post(route('customer.simulate_payment'), [
            'order_code' => $order->order_code,
        ]);

        $paidResponse = $this->actingAs($this->tenantStaff)
            ->postJson("/tenant/orders/{$order->id}/status", [
                'status' => 'diproses',
            ]);

        $paidResponse->assertOk()
            ->assertJson(['success' => true]);

        $order->refresh();
        $this->assertEquals('diproses', $order->status);
    }

    /**
     * Skenario 12: Validasi OrderSecurityService::checkActiveOrderLimit Mencegah Kuras Stok (Stock Hoarding) Tanpa Bayar
     */
    public function test_active_order_limit_prevents_unpaid_stock_hoarding()
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

        // Buat 5 pesanan aktif berturut-turut dengan nomor HP yang sama
        for ($i = 1; $i <= 5; $i++) {
            $response = $this->withSession(['cart' => $cart])
                ->post(route('customer.checkout'), [
                    'customer_name' => 'Penimbun Stok',
                    'phone_number' => '081200001111',
                    'customer_type' => 'pengunjung',
                    'pickup_method' => 'ambil_sendiri',
                    'payment_method' => 'qris',
                ]);

            $response->assertRedirect();
        }

        // Stok berkurang dari 20 menjadi 15
        $this->product->refresh();
        $this->assertEquals(15, $this->product->stock);

        // Percobaan pesanan ke-6 harus ditolak sebelum memotong stok produk
        $sixthAttempt = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Penimbun Stok',
                'phone_number' => '081200001111',
                'customer_type' => 'pengunjung',
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);

        $sixthAttempt->assertRedirect(route('customer.cart'));
        $sixthAttempt->assertSessionHas('error');

        // Pastikan stok tetap 15 (tidak berkurang menjadi 14) dan log keamanan tercatat
        $this->product->refresh();
        $this->assertEquals(15, $this->product->stock);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'ACTIVE_ORDER_LIMIT_REACHED',
            'table_name' => 'orders',
        ]);
    }

    /**
     * Skenario 13: Validasi Form Laporan Kendala (storeComplaint) Wajib Sesuai Nomor HP Pemesan & Pesanan Sudah Lunas
     */
    public function test_store_complaint_requires_matching_customer_phone_and_paid_order()
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

        $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Sari Pelanggan Asli',
                'phone_number' => '081233445566',
                'customer_type' => 'pengunjung',
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);

        $order = Order::where('customer_name', 'Sari Pelanggan Asli')->firstOrFail();

        // 1. Pihak lain mencoba melapor dengan nomor HP berbeda -> Ditolak pada reporter_contact
        $wrongPhoneResponse = $this->post(route('customer.complaint'), [
            'order_code' => $order->order_code,
            'reporter_name' => 'Orang Iseng',
            'reporter_contact' => '089999999999',
            'category' => 'pesanan_salah',
            'description' => 'Laporan palsu dari orang lain',
        ]);

        $wrongPhoneResponse->assertSessionHasErrors('reporter_contact');
        $this->assertDatabaseMissing('complaints', [
            'order_id' => $order->id,
        ]);

        // 2. Pemilik asli melapor saat pesanan belum dibayar (is_paid = false) -> Ditolak pada order_code
        $unpaidComplaintResponse = $this->post(route('customer.complaint'), [
            'order_code' => $order->order_code,
            'reporter_name' => 'Sari Pelanggan Asli',
            'reporter_contact' => '081233445566',
            'category' => 'pesanan_salah',
            'description' => 'Mencoba komplain sebelum bayar',
        ]);

        $unpaidComplaintResponse->assertSessionHasErrors('order_code');
        $this->assertDatabaseMissing('complaints', [
            'order_id' => $order->id,
        ]);

        // 2b. Tanpa order_code tapi memilih 'pesanan_salah' saat belum ada pesanan lunas -> Ditolak
        $emptyCodeNoPaidResponse = $this->post(route('customer.complaint'), [
            'order_code' => '',
            'reporter_name' => 'Sari Pelanggan Asli',
            'reporter_contact' => '081233445566',
            'category' => 'pesanan_salah',
            'description' => 'Komplain pesanan tanpa order_code sebelum bayar',
        ]);

        $emptyCodeNoPaidResponse->assertSessionHasErrors('order_code');

        // 3. Setelah pesanan dibayar lunas (is_paid = true) dan menggunakan nomor HP yang cocok (termasuk variasi +628...) -> Berhasil
        $this->post(route('customer.simulate_payment'), [
            'order_code' => $order->order_code,
        ]);

        $validComplaintResponse = $this->post(route('customer.complaint'), [
            'order_code' => $order->order_code,
            'reporter_name' => 'Sari Pelanggan Asli',
            'reporter_contact' => '+6281233445566',
            'category' => 'pesanan_salah',
            'description' => 'Minuman kurang dingin',
        ]);

        $validComplaintResponse->assertRedirect(route('page.faq'));
        $validComplaintResponse->assertSessionHas('success');
        $this->assertDatabaseHas('complaints', [
            'order_id' => $order->id,
            'category' => 'pesanan_salah',
        ]);

        // 4. Tanpa mengisi order_code tapi sudah punya pesanan lunas -> Otomatis terhubung ke order lunas terbaru milik nomor HP tersebut
        $autoLinkedResponse = $this->post(route('customer.complaint'), [
            'order_code' => '',
            'reporter_name' => 'Sari Pelanggan Asli',
            'reporter_contact' => '081233445566',
            'category' => 'status_tidak_update',
            'description' => 'Lupa kode order tapi nomor HP sudah lunas',
        ]);

        $autoLinkedResponse->assertRedirect(route('page.faq'));
        $this->assertDatabaseHas('complaints', [
            'order_id' => $order->id,
            'category' => 'status_tidak_update',
            'description' => 'Lupa kode order tapi nomor HP sudah lunas',
        ]);
    }

    /**
     * Skenario 14: Validasi State Machine Status Pesanan Tenant (Menolak Lompat Status atau Mundur dari Selesai ke Ditolak)
     */
    public function test_tenant_order_status_state_machine_blocks_invalid_or_backward_transitions()
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

        $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Fajar Urutan Status',
                'phone_number' => '081244556677',
                'customer_type' => 'pengunjung',
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);

        $order = Order::where('customer_name', 'Fajar Urutan Status')->firstOrFail();
        $this->post(route('customer.simulate_payment'), ['order_code' => $order->order_code]);

        // Stok saat ini 18 (20 - 2)
        $this->assertEquals(18, $this->product->fresh()->stock);

        // 1. Dari 'menunggu' melompat langsung ke 'siap' atau 'selesai' -> Harus ditolak (422)
        $this->actingAs($this->tenantStaff)
            ->postJson("/tenant/orders/{$order->id}/status", ['status' => 'siap'])
            ->assertStatus(422);

        $this->actingAs($this->tenantStaff)
            ->postJson("/tenant/orders/{$order->id}/status", ['status' => 'selesai'])
            ->assertStatus(422);

        // 2. Transisi sah: 'menunggu' -> 'diproses'
        $this->actingAs($this->tenantStaff)
            ->postJson("/tenant/orders/{$order->id}/status", ['status' => 'diproses'])
            ->assertOk();

        // Dari 'diproses' melompat langsung ke 'selesai' atau 'ditolak' -> Harus ditolak (422)
        $this->actingAs($this->tenantStaff)
            ->postJson("/tenant/orders/{$order->id}/status", ['status' => 'selesai'])
            ->assertStatus(422);

        $this->actingAs($this->tenantStaff)
            ->postJson("/tenant/orders/{$order->id}/status", ['status' => 'ditolak'])
            ->assertStatus(422);

        // 3. Transisi sah: 'diproses' -> 'siap' -> 'selesai'
        $this->actingAs($this->tenantStaff)
            ->postJson("/tenant/orders/{$order->id}/status", ['status' => 'siap'])
            ->assertOk();

        $this->actingAs($this->tenantStaff)
            ->postJson("/tenant/orders/{$order->id}/status", ['status' => 'selesai'])
            ->assertOk();

        // 4. Dari 'selesai' mencoba mundur ke 'ditolak' atau 'diproses' -> Harus ditolak (422) & stok TIDAK bertambah kembali
        $this->actingAs($this->tenantStaff)
            ->postJson("/tenant/orders/{$order->id}/status", ['status' => 'ditolak'])
            ->assertStatus(422);

        $this->assertEquals('selesai', $order->fresh()->status);
        $this->assertEquals(18, $this->product->fresh()->stock);
    }

    /**
     * Skenario 15: Proteksi Role tenant_staff di TenantOrderController & Penanganan Error 419 Kembali ke Halaman Asal
     */
    public function test_non_tenant_staff_gets_403_on_tenant_order_routes_and_419_redirects_back()
    {
        $admin = User::factory()->create([
            'role' => 'admin_ops',
            'tenant_id' => null,
            'is_active' => true,
        ]);

        // 1. Admin mencoba mengakses route khusus tenant -> Harus 403 Forbidden
        $this->actingAs($admin)->get(route('tenant.dashboard'))->assertStatus(403);
        $this->actingAs($admin)->get(route('tenant.orders'))->assertStatus(403);
        $this->actingAs($admin)->get(route('tenant.orders.history'))->assertStatus(403);
        $this->actingAs($admin)->post(route('tenant.settings.profile'), ['phone' => '08123456789'])->assertStatus(403);

        // 2. Simulasi error 419 (HttpException 419) dari halaman keranjang pelanggan -> Redirect kembali ke /cart (bukan ke /tenant/dashboard)
        $this->get(route('customer.cart'));
        $exceptionHandler = app(\Illuminate\Contracts\Debug\ExceptionHandler::class);
        $request = app('request');

        $response = $exceptionHandler->render($request, new \Symfony\Component\HttpKernel\Exception\HttpException(419));
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(route('customer.cart'), $response->headers->get('Location'));

        // 3. Sinkronisasi status akun staf (users.is_active) saat Admin Ops men-suspend / mengaktifkan kembali mitra tenant
        $this->actingAs($admin)
            ->patch(route('admin.tenants.toggle-status', $this->tenant->id))
            ->assertRedirect();

        $this->assertFalse((bool) $this->tenant->fresh()->is_active);
        $this->assertFalse((bool) $this->tenantStaff->fresh()->is_active);

        // Aktifkan kembali mitra tenant -> akun staf ikut aktif kembali
        $this->actingAs($admin)
            ->patch(route('admin.tenants.toggle-status', $this->tenant->id))
            ->assertRedirect();

        $this->assertTrue((bool) $this->tenant->fresh()->is_active);
        $this->assertTrue((bool) $this->tenantStaff->fresh()->is_active);
    }

    /**
     * Skenario 16: Sinkronisasi Harga Terbaru Database saat Checkout & Status Dinamis Buka/Tutup Restoran di Halaman Keranjang
     */
    public function test_checkout_uses_latest_database_price_and_cart_displays_closed_tenant_status()
    {
        // 1. Produk di keranjang memiliki harga lama (Rp 10.000), sedangkan di database harga sudah naik menjadi Rp 32.000
        $this->product->update(['price' => 32000]);

        $staleCart = [
            $this->product->id => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => 10000, // Harga lama di session
                'quantity' => 2,
                'tenant_id' => $this->tenant->id,
            ]
        ];

        $this->withSession(['cart' => $staleCart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Gina Harga Baru',
                'phone_number' => '081255443322',
                'customer_type' => 'pengunjung',
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);

        $order = Order::where('customer_name', 'Gina Harga Baru')->firstOrFail();
        // Total harus menggunakan harga terbaru database: 2 x Rp 32.000 = Rp 64.000 (bukan Rp 20.000)
        $this->assertEquals(64000, $order->total_amount);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'unit_price' => 32000,
            'subtotal' => 64000,
        ]);

        // 2. Jika tenant tutup, halaman keranjang harus menampilkan "Restoran Sedang Tutup"
        $this->tenant->update(['is_active' => false]);

        $cartView = $this->withSession(['cart' => $staleCart])->get(route('customer.cart'));
        $cartView->assertOk();
        $cartView->assertSee('Restoran Sedang Tutup');
    }

    /**
     * Skenario 17: Validasi Dinamis Waktu Boarding Berdasarkan Waktu Persiapan Menu Terlama (preparation_time)
     */
    public function test_dynamic_boarding_time_respects_longest_product_preparation_time()
    {
        \Carbon\Carbon::setTestNow(\Carbon\Carbon::parse('2026-10-28 10:00:00'));

        // Produk membutuhkan preparation_time 20 menit
        $this->product->update(['preparation_time' => 20]);

        $cart = [
            $this->product->id => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'quantity' => 1,
                'tenant_id' => $this->tenant->id,
            ]
        ];

        // 1. Sisa waktu boarding 18 menit (10:18) -> Ditolak karena menu butuh 20 menit persiapan
        $rejectedResponse = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Hendra Menu 20 Menit',
                'phone_number' => '081266778899',
                'customer_type' => 'penumpang',
                'flight_number' => 'GA-310',
                'boarding_time' => '10:18',
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);

        $rejectedResponse->assertSessionHasErrors('boarding_time');
        $this->assertDatabaseMissing('orders', ['customer_name' => 'Hendra Menu 20 Menit']);

        // 2. Sisa waktu boarding 22 menit (10:22) -> Berhasil lolos (baik ambil_sendiri maupun diantar porter)
        $acceptedResponse = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Hendra Menu 20 Menit',
                'phone_number' => '081266778899',
                'customer_type' => 'penumpang',
                'flight_number' => 'GA-310',
                'boarding_time' => '10:22',
                'pickup_method' => 'diantar',
                'delivery_location_id' => $this->deliveryLocation->id,
                'payment_method' => 'qris',
            ]);

        $acceptedResponse->assertSessionDoesntHaveErrors();
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Hendra Menu 20 Menit',
            'pickup_method' => 'diantar',
        ]);

        \Carbon\Carbon::setTestNow();
    }

    /**
     * Skenario 18: Foto Produk Hasil Upload Tenant (Storage Public) Dikenali oleh Product::getImageAttribute & Tampil di Katalog
     */
    public function test_tenant_uploaded_product_image_is_recognized_by_accessor_and_displayed_in_views()
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $pngBytes = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
        $uploadedFile = \Illuminate\Http\UploadedFile::fake()->createWithContent('menu-spesial-juanda.png', $pngBytes);

        $response = $this->actingAs($this->tenantStaff)
            ->post(route('tenant.products.store'), [
                'name' => 'Nasi Goreng Spesial Bandara',
                'category' => 'Makanan Utama',
                'description' => 'Nasi goreng rempah khas Juanda',
                'price' => 45000,
                'stock' => 15,
                'is_available' => '1',
                'image' => $uploadedFile,
            ]);

        $response->assertRedirect(route('tenant.products'));

        $newProduct = Product::where('name', 'Nasi Goreng Spesial Bandara')->firstOrFail();

        // Pastikan accessor $newProduct->image tidak mengembalikan null dan mengenali file di Storage disk 'public'
        $this->assertNotNull($newProduct->image);
        $this->assertStringStartsWith('products/', $newProduct->image);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($newProduct->image);

        // Pastikan halaman katalog pelanggan dan manajemen produk tenant merender URL storage foto tersebut
        $customerMenuView = $this->get(route('customer.tenant.show', $this->tenant->id));
        $customerMenuView->assertOk();
        $customerMenuView->assertSee('storage/' . $newProduct->image);

        $tenantProductsView = $this->actingAs($this->tenantStaff)->get(route('tenant.products'));
        $tenantProductsView->assertOk();
        $tenantProductsView->assertSee($newProduct->name);
    }

    /**
     * Skenario 19: Tenant Mengatur Varian Mandiri & Kustom per Menu (variant_groups) & Pilihan Varian Pelanggan Tercatat di OrderItem
     */
    public function test_tenant_can_configure_product_customization_options_and_customer_variant_is_saved_to_order_item()
    {
        // 1. Tenant menambahkan menu dengan grup varian kustom (misal Potongan Ayam & Pilihan Saus)
        $this->actingAs($this->tenantStaff)
            ->post(route('tenant.products.store'), [
                'name' => 'Paket Ayam Crispy Bandara',
                'category' => 'Makanan Utama',
                'price' => 42000,
                'stock' => 25,
                'is_available' => '1',
                'variant_groups' => [
                    ['label' => 'Potongan Ayam', 'choices' => 'Dada, Paha Atas, Sayap'],
                    ['label' => 'Pilihan Saus', 'choices' => 'BBQ, Blackpepper, Cheese'],
                ],
            ])
            ->assertRedirect(route('tenant.products'));

        $paketAyam = Product::where('name', 'Paket Ayam Crispy Bandara')->firstOrFail();
        $normalized = $paketAyam->getNormalizedCustomizationGroups();
        $this->assertCount(2, $normalized);
        $this->assertEquals('Potongan Ayam', $normalized[0]['label']);
        $this->assertEquals(['Dada', 'Paha Atas', 'Sayap'], $normalized[0]['choices']);

        // 2. Pelanggan memesan menu tersebut dengan 2 pilihan varian BERBEDA (1x Paha Atas • Blackpepper & 1x Dada • Cheese)
        $this->postJson(route('customer.cart.add'), [
            'product_id' => $paketAyam->id,
            'tenant_id' => $this->tenant->id,
            'variants' => 'Paha Atas • Blackpepper',
        ])->assertOk();

        $this->postJson(route('customer.cart.add'), [
            'product_id' => $paketAyam->id,
            'tenant_id' => $this->tenant->id,
            'variants' => 'Dada • Cheese',
        ])->assertOk();

        // Jika memesan varian yang SAMA lagi, kuantitas pada varian tersebut bertambah menjadi 2
        $this->postJson(route('customer.cart.add'), [
            'product_id' => $paketAyam->id,
            'tenant_id' => $this->tenant->id,
            'variants' => 'Paha Atas • Blackpepper',
        ])->assertOk();

        // 3. Checkout dan pastikan kedua varian tercatat terpisah pada product_name_snapshot di tabel order_items
        $this->post(route('customer.checkout'), [
            'customer_name' => 'Rina Pecinta Kuliner',
            'phone_number' => '081277889900',
            'customer_type' => 'pengunjung',
            'pickup_method' => 'ambil_sendiri',
            'payment_method' => 'qris',
        ]);

        $order = Order::where('customer_name', 'Rina Pecinta Kuliner')->firstOrFail();
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $paketAyam->id,
            'product_name_snapshot' => 'Paket Ayam Crispy Bandara (Paha Atas • Blackpepper)',
            'quantity' => 2,
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $paketAyam->id,
            'product_name_snapshot' => 'Paket Ayam Crispy Bandara (Dada • Cheese)',
            'quantity' => 1,
        ]);
        // Stok berkurang total 3 porsi (25 - 3 = 22)
        $this->assertEquals(22, $paketAyam->fresh()->stock);

        // 4. Validasi Kepemilikan Produk terhadap Tenant: manipulasi payload product_id milik Tenant A dengan tenant_id milik Tenant B harus ditolak (422)
        $otherTenant = Tenant::create([
            'tenant_code' => 'T-E2E-02',
            'name' => 'Restoran Lain T2',
            'floor_location' => 'Lantai 2',
            'terminal' => 'T2',
            'is_active' => true,
            'opening_time' => '05:00',
            'closing_time' => '23:00',
        ]);

        $this->postJson(route('customer.cart.add'), [
            'product_id' => $paketAyam->id,
            'tenant_id' => $otherTenant->id,
            'force_replace' => '1',
        ])->assertStatus(422)
          ->assertJson(['error' => 'invalid_tenant_product']);

        // 5. Sinkronisasi Menu yang Tiba-Tiba Dinonaktifkan saat Pelanggan di Halaman /cart
        $this->postJson(route('customer.cart.add'), [
            'product_id' => $paketAyam->id,
            'tenant_id' => $this->tenant->id,
        ])->assertOk();

        // Tenant tiba-tiba menonaktifkan menu
        $paketAyam->update(['is_available' => false]);

        // Pelanggan menekan tombol + (increase) di halaman /cart -> item otomatis dikeluarkan dengan pesan warning
        $updateResp = $this->postJson(route('customer.cart.update'), [
            'product_id' => $paketAyam->id,
            'action' => 'increase',
        ]);
        $updateResp->assertOk()
            ->assertJson([
                'success' => true,
                'jumlah' => 0,
                'cart_count' => 0,
            ]);
        $this->assertNotEmpty($updateResp->json('warning'));
    }
}



