<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantTerminal2MenuDescriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_terminal_2_menu_descriptions_and_kitchen_notes_are_seeded_and_displayed(): void
    {
        $tenant = Tenant::create([
            'tenant_code'    => 'FB-T2-01-01',
            'name'           => 'BAKMI GOCIT',
            'terminal'       => 'T2',
            'floor_location' => 'Lantai 1 - Keberangkatan Internasional',
            'category'       => 'Indonesian Traditional',
            'opening_time'   => '05:00:00',
            'closing_time'   => '23:00:00',
            'is_active'      => true,
        ]);

        $product = Product::create([
            'tenant_id'        => $tenant->id,
            'name'             => 'Bakmi Rempah Spesial',
            'price'            => 54000,
            'category'         => 'Indonesian Traditional',
            'stock'            => 50,
            'is_available'     => true,
            'preparation_time' => 15,
        ]);

        // Jalankan logika migrasi seeder deskripsi T2 untuk produk yang baru dibuat
        $migration = require database_path('migrations/2026_10_29_160000_seed_terminal2_menu_descriptions.php');
        $migration->up();

        $product->refresh();

        $this->assertNotEmpty($product->description);
        $this->assertStringContainsString('khas Gocit', $product->description);
        $this->assertNotEmpty($product->note);
        $this->assertStringContainsString('Pisahkan kuah kaldu', $product->note);

        // 1. Verifikasi tampilan pelanggan di /menu/{tenant}
        $response = $this->get(route('customer.tenant.show', $tenant->id));
        $response->assertStatus(200);
        $response->assertSee('Mie kenyal khas Gocit dengan racikan rempah istimewa');

        // 2. Verifikasi tampilan staf tenant di dashboard produk
        $staff = User::factory()->create([
            'role'      => 'tenant_staff',
            'tenant_id' => $tenant->id,
            'is_active' => true,
        ]);

        $staffResponse = $this->actingAs($staff)->get(route('tenant.products'));
        $staffResponse->assertStatus(200);
        $staffResponse->assertSee('Mie kenyal khas Gocit dengan racikan rempah istimewa');
        $staffResponse->assertSee('Pisahkan kuah kaldu dalam mangkuk anti-bocor');
    }
}
