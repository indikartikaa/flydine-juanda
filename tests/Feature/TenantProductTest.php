<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantProductTest extends TestCase
{
    use RefreshDatabase;

    private function createTenantStaff(): array
    {
        $tenant = Tenant::create([
            'name' => 'Killiney Kopitiam',
            'tenant_code' => 'FB-T1-01-99',
            'terminal' => 'T1',
            'zone' => 'Airside',
            'floor_location' => 'Lantai 2',
            'category' => 'Beverages & Coffee',
            'is_active' => true,
            'opening_time' => '00:00',
            'closing_time' => '23:59',
        ]);

        $user = User::factory()->create([
            'name' => 'Staff Killiney',
            'email' => 'killiney@flydine.com',
            'role' => 'tenant_staff',
            'tenant_id' => $tenant->id,
        ]);

        return [$tenant, $user];
    }

    public function test_tenant_staff_can_view_product_catalog(): void
    {
        [$tenant, $user] = $this->createTenantStaff();

        Product::create([
            'tenant_id' => $tenant->id,
            'name' => 'Kaya Butter Toast',
            'category' => 'Snack',
            'price' => 35000,
            'stock' => 15,
            'is_available' => true,
        ]);

        $response = $this->actingAs($user)->get(route('tenant.products'));
        $response->assertStatus(200);
        $response->assertSee('Katalog Menu & Produk');
        $response->assertSee('Total Menu');
        $response->assertSee('Menu Aktif');
        $response->assertSee('Kaya Butter Toast');
        $response->assertSee('Rp 35.000');
    }

    public function test_tenant_staff_can_create_new_product(): void
    {
        [$tenant, $user] = $this->createTenantStaff();

        $response = $this->actingAs($user)->post(route('tenant.products.store'), [
            'name' => 'Mie Laksa Special',
            'category' => 'Makanan Utama',
            'price' => 58000,
            'stock' => 50,
            'description' => 'Mie laksa kuah santan gurih kaya rempah khas Melayu.',
            'note' => 'Pisahkan sambal',
            'is_available' => '1',
        ]);

        $response->assertRedirect(route('tenant.products'));

        $this->assertDatabaseHas('products', [
            'tenant_id' => $tenant->id,
            'name' => 'Mie Laksa Special',
            'category' => 'Makanan Utama',
            'price' => 58000,
            'stock' => 50,
        ]);
    }

    public function test_tenant_staff_can_toggle_product_availability(): void
    {
        [$tenant, $user] = $this->createTenantStaff();

        $product = Product::create([
            'tenant_id' => $tenant->id,
            'name' => 'Kopi Tarik',
            'category' => 'Minuman',
            'price' => 25000,
            'stock' => 20,
            'is_available' => true,
        ]);

        $response = $this->actingAs($user)->patch(route('tenant.products.toggle', $product));
        $response->assertRedirect(route('tenant.products'));

        $product->refresh();
        $this->assertFalse((bool) $product->is_available);

        // Toggle back to active
        $response2 = $this->actingAs($user)->patch(route('tenant.products.toggle', $product));
        $response2->assertRedirect(route('tenant.products'));

        $product->refresh();
        $this->assertTrue((bool) $product->is_available);
    }

    public function test_customer_cannot_order_product_with_zero_stock(): void
    {
        [$tenant, $user] = $this->createTenantStaff();

        $emptyProduct = Product::create([
            'tenant_id' => $tenant->id,
            'name' => 'Kwetiaw Goreng Habis',
            'category' => 'Makanan Utama',
            'price' => 45000,
            'stock' => 0, // Habis
            'is_available' => true,
        ]);

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post(route('customer.cart.add'), [
                'product_id' => $emptyProduct->id,
                'tenant_id' => $tenant->id,
            ]);

        $response->assertStatus(400);
        $response->assertJson([
            'error' => 'unavailable',
            'message' => 'Mohon maaf, menu ini sedang habis atau tidak tersedia.',
        ]);
    }

    public function test_product_stock_automatically_decreases_on_checkout(): void
    {
        [$tenant, $user] = $this->createTenantStaff();

        $product = Product::create([
            'tenant_id' => $tenant->id,
            'name' => 'Nasi Lemak Autostock',
            'category' => 'Makanan Utama',
            'price' => 50000,
            'stock' => 10,
            'is_available' => true,
        ]);

        // Tambah ke keranjang
        $this->withSession([
            'cart' => [
                $product->id => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => 3,
                    'tenant_id' => $tenant->id,
                ]
            ]
        ]);

        // Lakukan Checkout
        $response = $this->post(route('customer.checkout'), [
            'customer_name' => 'John Passenger',
            'phone_number' => '081299998888',
            'customer_type' => 'pengunjung',
            'payment_method' => 'qris',
            'pickup_method' => 'ambil_sendiri',
        ]);

        $response->assertSessionHasNoErrors();

        // Stok berkurang otomatis dari 10 menjadi 7
        $product->refresh();
        $this->assertEquals(7, $product->stock);
    }
}
