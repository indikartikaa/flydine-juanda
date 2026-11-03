<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerSplashScreenTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_catalog_returns_200_and_contains_splash_markup(): void
    {
        $response = $this->get(route('customer.menu'));

        $response->assertStatus(200);
        $response->assertSee('id="flydine-splash"', false);
        $response->assertSee('images/logo-flydine.png');
        $response->assertSee('aria-hidden="true"', false);
        $response->assertSee('flydine-splash-center');
        $response->assertSee('flydine-splash-logo');
    }

    public function test_customer_subpages_contain_splash_markup(): void
    {
        $cartResponse = $this->get(route('customer.cart'));
        $cartResponse->assertStatus(200);
        $cartResponse->assertSee('id="flydine-splash"', false);

        $historyResponse = $this->get(route('customer.history'));
        $historyResponse->assertStatus(200);
        $historyResponse->assertSee('id="flydine-splash"', false);

        $faqResponse = $this->get(route('page.faq'));
        $faqResponse->assertStatus(200);
        $faqResponse->assertSee('id="flydine-splash"', false);
    }

    public function test_admin_pages_do_not_contain_splash_markup(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_ops',
        ]);

        $dashboardResponse = $this->actingAs($admin)->get(route('admin.dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertDontSee('id="flydine-splash"', false);
        $dashboardResponse->assertDontSee('flydine-splash');

        $tenantsResponse = $this->actingAs($admin)->get(route('admin.tenants.index'));
        $tenantsResponse->assertStatus(200);
        $tenantsResponse->assertDontSee('id="flydine-splash"', false);
    }

    public function test_tenant_pages_do_not_contain_splash_markup(): void
    {
        $tenant = Tenant::create([
            'name' => 'Kopi Kenangan T1',
            'tenant_code' => 'KK-T1-01',
            'terminal' => 'T1',
            'zone' => 'Airside',
            'floor_location' => 'Lantai 2 Gate 3',
            'category' => 'Beverages & Coffee',
            'is_active' => true,
            'opening_time' => '00:00',
            'closing_time' => '23:59',
        ]);

        $tenantUser = User::factory()->create([
            'role' => 'tenant_staff',
            'tenant_id' => $tenant->id,
        ]);

        $productsResponse = $this->actingAs($tenantUser)->get(route('tenant.products'));
        $productsResponse->assertStatus(200);
        $productsResponse->assertDontSee('id="flydine-splash"', false);
        $productsResponse->assertDontSee('flydine-splash');

        $ordersResponse = $this->actingAs($tenantUser)->get(route('tenant.orders'));
        $ordersResponse->assertStatus(200);
        $ordersResponse->assertDontSee('id="flydine-splash"', false);
    }
}

