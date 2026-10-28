<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use App\Models\Tenant;
use App\Models\Product;

class CustomerCrossMidnightBoardingTest extends TestCase
{
    use RefreshDatabase;

    private Tenant $tenant;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'tenant_code' => 'T-MIDNIGHT',
            'name' => 'Restoran 24 Jam Juanda',
            'floor_location' => 'Lantai 1 Gate 3',
            'is_active' => true,
            'opening_time' => '00:00',
            'closing_time' => '23:59',
            'delivery_fee' => 5000,
        ]);

        $this->product = Product::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Kopi Tubruk Juanda',
            'category' => 'Minuman',
            'description' => 'Kopi hangat malam',
            'price' => 25000,
            'stock' => 50,
            'preparation_time' => 5,
            'is_available' => true,
        ]);
    }

    public function test_cross_midnight_flight_is_accepted_when_boarding_next_day(): void
    {
        // Simulasi waktu saat ini: Pukul 23:40 malam tanggal 27 Oktober
        Carbon::setTestNow(Carbon::parse('2026-10-27 23:40:00'));

        $cart = [
            $this->product->id => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'quantity' => 1,
                'tenant_id' => $this->tenant->id,
            ],
        ];

        // Penumpang memesan jam 23:40 untuk penerbangan dini hari pukul 01:15 (selisih 95 menit ke depan)
        $response = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Dimas Anggara',
                'phone_number' => '081234567890',
                'customer_type' => 'penumpang',
                'flight_number' => 'GA-888',
                'boarding_time' => '01:15',
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Dimas Anggara',
            'flight_number' => 'GA-888',
            'boarding_time' => '2026-10-28 01:15:00', // Tercatat tanggal esok hari
        ]);

        Carbon::setTestNow(); // Reset time
    }

    public function test_cross_midnight_flight_under_15_minutes_is_rejected(): void
    {
        // Simulasi waktu saat ini: Pukul 23:55 malam (5 menit sebelum tengah malam)
        Carbon::setTestNow(Carbon::parse('2026-10-27 23:55:00'));

        $cart = [
            $this->product->id => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'quantity' => 1,
                'tenant_id' => $this->tenant->id,
            ],
        ];

        // Penumpang memasukkan boarding 00:05 dini hari (hanya selisih 10 menit dari 23:55)
        $response = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Dimas Anggara',
                'phone_number' => '081234567890',
                'customer_type' => 'penumpang',
                'flight_number' => 'GA-888',
                'boarding_time' => '00:05',
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);

        $response->assertSessionHasErrors(['boarding_time']);
        $this->assertStringContainsString('Waktu boarding terlalu dekat', session('errors')->first('boarding_time'));

        Carbon::setTestNow();
    }

    public function test_past_boarding_time_is_rejected(): void
    {
        // Simulasi siang hari pukul 14:00
        Carbon::setTestNow(Carbon::parse('2026-10-27 14:00:00'));

        $cart = [
            $this->product->id => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'quantity' => 1,
                'tenant_id' => $this->tenant->id,
            ],
        ];

        // Penumpang memasukkan boarding yang sudah terlewat 30 menit lalu (13:30)
        $response = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Dimas Anggara',
                'phone_number' => '081234567890',
                'customer_type' => 'penumpang',
                'flight_number' => 'GA-888',
                'boarding_time' => '13:30',
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);

        $response->assertSessionHasErrors(['boarding_time']);
        $this->assertStringContainsString('sudah terlewat', session('errors')->first('boarding_time'));

        // Penumpang memasukkan boarding 6 jam lalu (08:00 pagi) tidak boleh dianggap besok pagi
        $responseMorning = $this->withSession(['cart' => $cart])
            ->post(route('customer.checkout'), [
                'customer_name' => 'Dimas Anggara',
                'phone_number' => '081234567890',
                'customer_type' => 'penumpang',
                'flight_number' => 'GA-888',
                'boarding_time' => '08:00',
                'pickup_method' => 'ambil_sendiri',
                'payment_method' => 'qris',
            ]);

        $responseMorning->assertSessionHasErrors(['boarding_time']);
        $this->assertStringContainsString('sudah terlewat', session('errors')->first('boarding_time'));

        Carbon::setTestNow();
    }
}
