<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TenantManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_ops_can_view_tenants_management(): void
    {
        $admin = User::factory()->create(['role' => 'admin_ops']);
        Tenant::create([
            'name' => 'A&W Restaurant',
            'tenant_code' => 'FB-T1-01-01',
            'terminal' => 'T1',
            'zone' => 'Landside',
            'floor_location' => 'Lantai 1 - Kedatangan 1A',
            'category' => 'Fast Food & Restaurant',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.tenants.index'));
        $response->assertStatus(200);
        $response->assertSee('Manajemen Mitra Tenant');
        $response->assertSee('A&W Restaurant');
        $response->assertSee('FB-T1-01-01');
    }

    public function test_admin_ops_can_create_new_tenant(): void
    {
        $admin = User::factory()->create(['role' => 'admin_ops']);

        $response = $this->actingAs($admin)->post(route('admin.tenants.store'), [
            'name' => 'Kopi Kenangan T1',
            'company_name' => 'PT Bumi Berkah Boga',
            'tenant_code' => 'T1-KENANGAN',
            'email' => 'kenangan@flydine.com',
            'category' => 'Beverages & Coffee',
            'terminal' => 'T1',
            'zone' => 'Airside',
            'floor_location' => 'Lantai 2 Gate 3',
            'contract_start' => '2026-01-01',
            'contract_end' => '2027-01-01',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tenants', [
            'tenant_code' => 'T1-KENANGAN',
            'name' => 'Kopi Kenangan T1',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'kenangan@flydine.com',
            'role' => 'tenant_staff',
        ]);
    }

    public function test_admin_ops_can_update_tenant(): void
    {
        $admin = User::factory()->create(['role' => 'admin_ops']);
        $tenant = Tenant::create([
            'name' => 'Old Name',
            'tenant_code' => 'T1-OLD',
            'terminal' => 'T1',
            'zone' => 'Landside',
            'floor_location' => 'Lantai 1',
            'category' => 'Beverages & Coffee',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.tenants.update', $tenant->id), [
            'name' => 'New Name Coffee',
            'tenant_code' => 'T1-OLD',
            'category' => 'Beverages & Coffee',
            'terminal' => 'T1',
            'zone' => 'Landside',
            'floor_location' => 'Lantai 1 Baru',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tenants', [
            'id' => $tenant->id,
            'name' => 'New Name Coffee',
            'floor_location' => 'Lantai 1 Baru',
        ]);
    }

    public function test_admin_ops_can_toggle_tenant_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin_ops']);
        $tenant = Tenant::create([
            'name' => 'Toggle Tenant',
            'tenant_code' => 'T1-TOGGLE',
            'terminal' => 'T1',
            'zone' => 'Landside',
            'floor_location' => 'Lantai 1',
            'category' => 'Beverages & Coffee',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.tenants.toggle-status', $tenant->id));
        $response->assertRedirect();
        $this->assertDatabaseHas('tenants', [
            'id' => $tenant->id,
            'is_active' => false,
        ]);
    }
}
