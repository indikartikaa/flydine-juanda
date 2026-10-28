<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantRotioMenuDescriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_rotio_products_have_customer_descriptions_and_kitchen_notes(): void
    {
        $tenant = Tenant::create([
            'tenant_code' => 'FB-T1-01-03',
            'name' => 'ROTI\'O',
            'terminal' => 'T1',
            'floor_location' => 'Lantai 1',
            'opening_time' => '05:00',
            'closing_time' => '23:00',
            'is_active' => true,
        ]);

        $coffeeBun = Product::create([
            'tenant_id' => $tenant->id,
            'name' => 'Roti\'O Original Coffee Bun',
            'price' => 18000,
            'category' => 'Bakery & Pastry',
            'description' => 'Roti bun hangat berlapis topping krim kopi aromatik yang renyah di luar dengan isian butter gurih meleleh di dalam.',
            'note' => 'Sajikan fresh dari warmer box. Kemas rapat dalam kantong anti minyak untuk penumpang take-away.',
            'stock' => 50,
            'is_available' => true,
        ]);

        $this->assertNotEmpty($coffeeBun->description);
        $this->assertStringContainsString('kopi', strtolower($coffeeBun->description));
        $this->assertNotEmpty($coffeeBun->note);

        // Cek halaman menu tenant pelanggan untuk memastikan deskripsi tampil
        $response = $this->get(route('customer.tenant.show', $tenant->id));
        $response->assertStatus(200);
        $response->assertSee('Roti bun hangat berlapis topping krim kopi aromatik');
    }
}
