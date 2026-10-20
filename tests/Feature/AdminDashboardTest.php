<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Order;
use App\Models\Complaint;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_gets_403_on_admin_dashboard(): void
    {
        $tenantUser = User::factory()->create([
            'role' => 'tenant_staff',
        ]);

        $response = $this->actingAs($tenantUser)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_admin_ops_can_view_dashboard_with_all_components(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin_ops',
        ]);

        $tenant = Tenant::create([
            'name' => 'Roti O T1',
            'tenant_code' => 'ROTIO01',
            'terminal' => '1',
            'floor_location' => 'Lantai 2 Gate 8',
            'is_active' => true,
        ]);

        $customer = \App\Models\Customer::create([
            'name' => 'Budi Santoso',
            'phone_number' => '081234567890',
            'email' => 'budi@example.com',
        ]);

        $order = Order::create([
            'order_code' => 'ORD-TEST123',
            'tenant_id' => $tenant->id,
            'customer_id' => $customer->id,
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081234567890',
            'total_amount' => 50000,
            'is_paid' => true,
            'status' => 'completed',
            'ordered_at' => now(),
            'auto_cancel_at' => now()->addMinutes(30),
        ]);

        Complaint::create([
            'complaint_code' => 'CMP-TEST01',
            'order_id' => $order->id,
            'reporter_name' => 'Ani Wijaya',
            'reporter_contact' => '081234567891',
            'category' => 'Layanan',
            'description' => 'Waktu saji agak lama',
            'status' => 'open',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Ringkasan Sistem');
        $response->assertSee('Selamat Datang, Admin Ops!');
        $response->assertSee('Mitra Terdaftar');
        $response->assertSee('Mitra Beroperasi');
        $response->assertSee('Audit Trail & Aktivitas Sistem', false);
        $response->assertSee('Lihat Semua');
    }
}
