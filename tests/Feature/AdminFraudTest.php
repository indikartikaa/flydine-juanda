<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminFraudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_fraud_monitoring(): void
    {
        $response = $this->get(route('admin.fraud'));
        $response->assertRedirect(route('login'));
    }

    public function test_tenant_staff_gets_403_on_fraud_monitoring(): void
    {
        $tenantUser = User::factory()->create(['role' => 'tenant_staff']);
        $response = $this->actingAs($tenantUser)->get(route('admin.fraud'));
        $response->assertStatus(403);
    }

    public function test_admin_ops_can_view_fraud_monitoring_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin_ops']);

        $response = $this->actingAs($admin)->get(route('admin.fraud'));
        $response->assertStatus(200);
        $response->assertSee('Pemantauan Fraud &amp; Spam', false);
        $response->assertSee('Akun Dibatasi (Blocked)');
        $response->assertSee('Tidak ada akun penumpang yang diblokir');
    }

    public function test_admin_ops_can_view_blocked_customers_with_metrics_and_pagination(): void
    {
        $admin = User::factory()->create(['role' => 'admin_ops']);

        $tenant = Tenant::create([
            'name' => 'Juanda Cafe',
            'tenant_code' => 'T1-CAFE',
            'terminal' => 'T1',
            'zone' => 'Landside',
            'floor_location' => 'Lantai 1',
            'category' => 'Beverages & Coffee',
            'is_active' => true,
        ]);

        $blockedCustomer = Customer::create([
            'name' => 'Suspect User',
            'phone_number' => '081299998888',
            'email' => 'suspect@example.com',
            'is_blocked' => true,
            'blocked_reason' => 'Terdeteksi 5 order dibatalkan dalam 30 menit',
            'blocked_at' => now(),
        ]);

        // Create 5 cancelled orders for this customer
        for ($i = 0; $i < 5; $i++) {
            Order::create([
                'order_code' => 'ORD-CANCEL-' . $i,
                'tenant_id' => $tenant->id,
                'customer_id' => $blockedCustomer->id,
                'customer_name' => $blockedCustomer->name,
                'customer_phone' => $blockedCustomer->phone_number,
                'total_amount' => 50000,
                'is_paid' => false,
                'status' => 'dibatalkan',
                'ordered_at' => now(),
                'auto_cancel_at' => now()->addMinutes(30),
            ]);
        }

        $response = $this->actingAs($admin)->get(route('admin.fraud'));
        $response->assertStatus(200);
        $response->assertSee('Suspect User');
        $response->assertSee('081299998888');
        $response->assertSee('Terdeteksi 5 order dibatalkan dalam 30 menit');
        $response->assertSee('Buka Blokir (Unblock)');
    }

    public function test_fraud_monitoring_search_filter(): void
    {
        $admin = User::factory()->create(['role' => 'admin_ops']);

        Customer::create([
            'name' => 'Target Spammer',
            'phone_number' => '089900112233',
            'is_blocked' => true,
            'blocked_reason' => 'Pola bot spam',
            'blocked_at' => now(),
        ]);

        Customer::create([
            'name' => 'Normal Blocked',
            'phone_number' => '081122334455',
            'is_blocked' => true,
            'blocked_reason' => 'Batal berulang',
            'blocked_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.fraud', ['search' => 'Spammer']));
        $response->assertStatus(200);
        $response->assertSee('Target Spammer');
        $response->assertDontSee('Normal Blocked');
    }

    public function test_admin_ops_can_unblock_customer(): void
    {
        $admin = User::factory()->create(['role' => 'admin_ops']);

        $customer = Customer::create([
            'name' => 'Mistaken User',
            'phone_number' => '087788990011',
            'is_blocked' => true,
            'blocked_reason' => 'Terdeteksi 5 order dibatalkan dalam 30 menit',
            'blocked_at' => now(),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.fraud.unblock', $customer->id));
        $response->assertRedirect();

        $fresh = $customer->fresh();
        $this->assertFalse($fresh->is_blocked);
        $this->assertNull($fresh->blocked_reason);
        $this->assertNull($fresh->blocked_at);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'CUSTOMER_UNBLOCKED',
            'table_name' => 'customers',
            'record_id' => $customer->id,
        ]);
    }
}
