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

    public function test_cancellation_is_recorded_in_fraud_system_and_blocks_on_fifth_attempt(): void
    {
        $tenant = Tenant::create([
            'name' => 'Juanda Fast Food',
            'tenant_code' => 'T1-FF01',
            'terminal' => 'T1',
            'zone' => 'Landside',
            'floor_location' => 'Lantai 1',
            'category' => 'Fast Food',
            'is_active' => true,
        ]);

        $customer = Customer::create([
            'name' => 'Fraud Test User',
            'phone_number' => '081234567899',
            'is_blocked' => false,
        ]);

        // 1st cancellation: recorded in activity_logs, is_blocked remains false (TC-ORD-08)
        $order1 = Order::create([
            'order_code' => 'ORD-TEST-01',
            'tenant_id' => $tenant->id,
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone_number,
            'total_amount' => 50000,
            'is_paid' => false,
            'status' => 'menunggu',
            'ordered_at' => now(),
            'auto_cancel_at' => now()->addMinutes(30),
        ]);

        $response1 = $this->post(route('customer.tracking.cancel', ['order' => $order1->order_code]));
        $response1->assertRedirect();

        $this->assertEquals('dibatalkan', $order1->fresh()->status);
        $this->assertFalse((bool) $customer->fresh()->is_blocked);
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'ORDER_CANCELLED',
            'table_name' => 'orders',
            'record_id' => $order1->id,
        ]);

        // Create 3 more cancelled orders (total 4 cancellations in 30 mins) -> still not blocked (TC-BVA-07)
        for ($i = 2; $i <= 4; $i++) {
            $order = Order::create([
                'order_code' => 'ORD-TEST-0' . $i,
                'tenant_id' => $tenant->id,
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_phone' => $customer->phone_number,
                'total_amount' => 50000,
                'is_paid' => false,
                'status' => 'menunggu',
                'ordered_at' => now(),
                'auto_cancel_at' => now()->addMinutes(30),
            ]);
            $this->post(route('customer.tracking.cancel', ['order' => $order->order_code]));
        }
        $this->assertFalse((bool) $customer->fresh()->is_blocked);

        // 5th cancellation in 30 minutes -> automatically blocked (TC-BVA-08)
        $order5 = Order::create([
            'order_code' => 'ORD-TEST-05',
            'tenant_id' => $tenant->id,
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone_number,
            'total_amount' => 50000,
            'is_paid' => false,
            'status' => 'menunggu',
            'ordered_at' => now(),
            'auto_cancel_at' => now()->addMinutes(30),
        ]);

        $response5 = $this->post(route('customer.tracking.cancel', ['order' => $order5->order_code]));
        $response5->assertRedirect();

        $freshCustomer = $customer->fresh();
        $this->assertTrue((bool) $freshCustomer->is_blocked);
        $this->assertStringContainsString('5', $freshCustomer->blocked_reason);
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'CUSTOMER_BLOCKED',
            'table_name' => 'customers',
            'record_id' => $customer->id,
        ]);

        // Verify admin can see both the blocked customer and the activity log
        $admin = User::factory()->create(['role' => 'admin_ops']);
        $adminResponse = $this->actingAs($admin)->get(route('admin.fraud'));
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('Fraud Test User');
        $adminResponse->assertSee('Audit Trail dan Log Aktivitas Deteksi Fraud Terkini');
        $adminResponse->assertSee('Batal Pesanan');
        $adminResponse->assertSee('Akun Diblokir');
    }
}
