<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantTerminal1MenuDescriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_terminal_1_menu_descriptions_and_kitchen_notes_are_seeded_and_displayed(): void
    {
        $tenant = Tenant::create([
            'tenant_code'    => 'FB-T1-01-01',
            'name'           => 'A&W',
            'terminal'       => 'T1',
            'floor_location' => 'Lantai 1 - Kedatangan 1A',
            'category'       => 'Fast Food & Restaurant',
            'opening_time'   => '05:00:00',
            'closing_time'   => '23:00:00',
            'is_active'      => true,
        ]);

        $product = Product::create([
            'tenant_id'        => $tenant->id,
            'name'             => 'NEW! Curry Aroma Chicken',
            'price'            => 31500,
            'category'         => 'Fast Food & Restaurant',
            'stock'            => 50,
            'is_available'     => true,
            'preparation_time' => 15,
        ]);

        // Jalankan logika migrasi seeder deskripsi T1 untuk produk yang baru dibuat
        $migration = require database_path('migrations/2026_10_29_163000_seed_terminal1_menu_descriptions.php');
        $migration->up();

        $product->refresh();

        $this->assertNotEmpty($product->description);
        $this->assertStringContainsString('khas A&W', $product->description);
        $this->assertNotEmpty($product->note);
        $this->assertStringContainsString('kantong kertas anti-minyak', $product->note);

        // 1. Verifikasi tampilan pelanggan di /menu/{tenant}
        $response = $this->get(route('customer.tenant.show', $tenant->id));
        $response->assertStatus(200);
        $response->assertSee('Potongan ayam goreng khas A&W');
        $response->assertSee('product-detail-modal', false);
        $response->assertSee('openProductDetailModal', false);
        $response->assertSee('Lihat detail &amp; deskripsi lengkap', false);

        // 2. Verifikasi tampilan staf tenant di dashboard produk
        $staff = User::factory()->create([
            'role'      => 'tenant_staff',
            'tenant_id' => $tenant->id,
            'is_active' => true,
        ]);

        $staffResponse = $this->actingAs($staff)->get(route('tenant.products'));
        $staffResponse->assertStatus(200);
        $staffResponse->assertSee('Potongan ayam goreng khas A&W');
        $staffResponse->assertSee('kantong kertas anti-minyak');
    }
}
