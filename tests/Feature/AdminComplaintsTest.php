<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Complaint;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminComplaintsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_complaints(): void
    {
        $response = $this->get(route('admin.complaints'));
        $response->assertRedirect(route('login'));
    }

    public function test_tenant_staff_gets_403_on_complaints(): void
    {
        $tenantUser = User::factory()->create(['role' => 'tenant_staff']);
        $response = $this->actingAs($tenantUser)->get(route('admin.complaints'));
        $response->assertStatus(403);
    }

    public function test_admin_ops_can_view_complaints_with_pagination_and_metrics(): void
    {
        $admin = User::factory()->create(['role' => 'admin_ops']);

        $tenant = Tenant::create([
            'name' => 'KFC Juanda T1',
            'tenant_code' => 'T1-KFC',
            'terminal' => 'T1',
            'zone' => 'Landside',
            'floor_location' => 'Lantai 1 Kedatangan',
            'category' => 'Fast Food & Restaurant',
            'is_active' => true,
        ]);

        $customer = Customer::create([
            'name' => 'Danuja Hidayat',
            'phone_number' => '086519898020',
            'email' => 'danuja@example.com',
        ]);

        $order = Order::create([
            'order_code' => 'ORD-TEST-999',
            'tenant_id' => $tenant->id,
            'customer_id' => $customer->id,
            'customer_name' => 'Danuja Hidayat',
            'customer_phone' => '086519898020',
            'total_amount' => 75000,
            'is_paid' => true,
            'status' => 'completed',
            'ordered_at' => now(),
            'auto_cancel_at' => now()->addMinutes(30),
        ]);

        Complaint::create([
            'complaint_code' => 'CMP-2026-001',
            'order_id' => $order->id,
            'reporter_name' => 'Danuja Hidayat',
            'reporter_contact' => '086519898020',
            'category' => 'pesanan_salah',
            'description' => 'Tingkat kepedasan sambal tidak sesuai dengan catatan pesanan',
            'status' => 'open',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.complaints'));
        $response->assertStatus(200);
        $response->assertSee('Manajemen Komplain Pelanggan');
        $response->assertSee('CMP-2026-001');
        $response->assertSee('Danuja Hidayat');
        $response->assertSee('Tingkat kepedasan sambal');
        $response->assertSee('Menunggu Respon');
    }

    public function test_complaints_search_filter(): void
    {
        $admin = User::factory()->create(['role' => 'admin_ops']);

        Complaint::create([
            'complaint_code' => 'CMP-ALPHA',
            'reporter_name' => 'Andi Wijaya',
            'reporter_contact' => '081234567890',
            'category' => 'pesanan_salah',
            'description' => 'Minuman tumpah saat diantar',
            'status' => 'open',
        ]);

        Complaint::create([
            'complaint_code' => 'CMP-BETA',
            'reporter_name' => 'Siti Rahma',
            'reporter_contact' => '089988776655',
            'category' => 'status_tidak_update',
            'description' => 'Status pesanan tidak berubah',
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.complaints', ['search' => 'Andi']));
        $response->assertStatus(200);
        $response->assertSee('CMP-ALPHA');
        $response->assertDontSee('CMP-BETA');
    }

    public function test_complaints_status_filter(): void
    {
        $admin = User::factory()->create(['role' => 'admin_ops']);

        Complaint::create([
            'complaint_code' => 'CMP-OPEN-1',
            'reporter_name' => 'Budi Santoso',
            'reporter_contact' => '081122334455',
            'category' => 'pesanan_salah',
            'description' => 'Makanan belum sampai',
            'status' => 'open',
        ]);

        Complaint::create([
            'complaint_code' => 'CMP-RESOLVED-1',
            'reporter_name' => 'Cahya Kamila',
            'reporter_contact' => '085566778899',
            'category' => 'lainnya',
            'description' => 'Sudah diberikan voucher pengganti',
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.complaints', ['status' => 'open']));
        $response->assertStatus(200);
        $response->assertSee('CMP-OPEN-1');
        $response->assertDontSee('CMP-RESOLVED-1');
    }

    public function test_admin_ops_can_update_complaint_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin_ops']);

        $complaint = Complaint::create([
            'complaint_code' => 'CMP-UPDATE-1',
            'reporter_name' => 'Gita Gutawa',
            'reporter_contact' => '081299887766',
            'category' => 'pesanan_salah',
            'description' => 'Salah varian rasa',
            'status' => 'open',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.complaints.status', $complaint->id), [
            'status' => 'resolved'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('complaints', [
            'id' => $complaint->id,
            'status' => 'resolved',
            'handled_by_user_id' => $admin->id,
        ]);
        $this->assertNotNull($complaint->fresh()->resolved_at);
    }
}
