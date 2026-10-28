<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Tenant;
use App\Models\Product;

class CustomerSearchRelevanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_prioritizes_restaurant_name_over_menu_name_matches(): void
    {
        // Tenant 1 (Created first, will have lower ID) has a product matching "Aroma", but restaurant name is A&W
        $tenantAw = Tenant::create([
            'tenant_code' => 'T-AW',
            'name' => 'A&W Restaurant',
            'floor_location' => 'T1 - Lantai 2 Gate 12',
            'is_active' => true,
            'opening_time' => '00:00',
            'closing_time' => '23:59',
            'delivery_fee' => 5000,
        ]);

        Product::create([
            'tenant_id' => $tenantAw->id,
            'name' => 'Curry Aroma Chicken',
            'category' => 'Fast Food',
            'description' => 'Ayam aroma kari lezat',
            'price' => 85000,
            'stock' => 50,
            'preparation_time' => 10,
            'is_available' => true,
        ]);

        // Tenant 2 (Created second, will have higher ID) has "Aroma" in restaurant name
        $tenantAromaPadang = Tenant::create([
            'tenant_code' => 'T-AROMA',
            'name' => 'Aroma Padang',
            'floor_location' => 'T1 - Lantai 2 Gate 8',
            'is_active' => true,
            'opening_time' => '00:00',
            'closing_time' => '23:59',
            'delivery_fee' => 5000,
        ]);

        Product::create([
            'tenant_id' => $tenantAromaPadang->id,
            'name' => 'Nasi Rendang Padang',
            'category' => 'Indonesian',
            'description' => 'Nasi rendang khas Minang',
            'price' => 75000,
            'stock' => 50,
            'preparation_time' => 10,
            'is_available' => true,
        ]);

        // Search for "AROMA"
        $response = $this->get(route('customer.menu', ['search' => 'AROMA']));

        $response->assertStatus(200);

        // Check the tenants collection passed to the view
        $viewTenants = $response->viewData('tenants');
        $this->assertCount(2, $viewTenants);

        // Aroma Padang must be ranked #1 because its name directly matches "AROMA"
        $this->assertEquals($tenantAromaPadang->id, $viewTenants->first()->id);
        $this->assertEquals('Aroma Padang', $viewTenants->first()->name);

        // A&W must be ranked #2 because only its menu item matched
        $this->assertEquals($tenantAw->id, $viewTenants->last()->id);
        $this->assertEquals('A&W Restaurant', $viewTenants->last()->name);
    }

    public function test_catalog_displays_search_label_and_menu_matched_badge(): void
    {
        $tenantAw = Tenant::create([
            'tenant_code' => 'T-AW',
            'name' => 'A&W Restaurant',
            'floor_location' => 'T1 - Lantai 2 Gate 12',
            'is_active' => true,
            'opening_time' => '00:00',
            'closing_time' => '23:59',
            'delivery_fee' => 5000,
        ]);

        Product::create([
            'tenant_id' => $tenantAw->id,
            'name' => 'Curry Aroma Chicken',
            'category' => 'Fast Food',
            'description' => 'Ayam aroma kari lezat',
            'price' => 85000,
            'stock' => 50,
            'preparation_time' => 10,
            'is_available' => true,
        ]);

        $response = $this->get(route('customer.menu', ['search' => 'AROMA']));

        $response->assertStatus(200);
        $response->assertSee('CARI RESTO / MENU');
        $response->assertSee('Menu cocok:');
        $response->assertSee('Curry Aroma Chicken');
    }
}
