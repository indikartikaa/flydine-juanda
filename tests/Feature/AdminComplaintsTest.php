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

    public function test_tc_comp_03_admin_handles_complaint_and_updates_dashboard_and_customer_tracking(): void
    {
        $admin = User::factory()->create(['role' => 'admin_ops']);

        $tenant = Tenant::create([
            'name' => 'A&W Restaurant T1',
            'tenant_code' => 'T1-AW',
            'terminal' => 'T1',
            'zone' => 'Landside',
            'floor_location' => 'Lantai 1',
            'category' => 'Fast Food',
            'is_active' => true,
        ]);

        $customer = Customer::create([
            'name' => 'Budi Santoso',
            'phone_number' => '081234567890',
        ]);

        $order = Order::create([
            'order_code' => 'ORD-COMP-003',
            'tenant_id' => $tenant->id,
            'customer_id' => $customer->id,
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081234567890',
            'total_amount' => 55000,
            'is_paid' => true,
            'status' => 'selesai',
            'ordered_at' => now()->subHour(),
            'auto_cancel_at' => now()->subMinutes(30),
        ]);

        $complaint = Complaint::create([
            'complaint_code' => 'CMP-TEST-003',
            'order_id' => $order->id,
            'reporter_name' => 'Budi Santoso',
            'reporter_contact' => '081234567890',
            'category' => 'pesanan_salah',
            'description' => 'Ayam goreng kurang renyah dan saus tertinggal',
            'status' => 'open',
        ]);

        // 1. Awal: Dashboard admin mencatat 1 open complaint
        $dashResponse = $this->actingAs($admin)->get(route('admin.dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertViewHas('open_complaints', 1);
        $dashResponse->assertViewHas('in_progress_complaints', 0);

        // 2. Admin ubah status ke in_progress
        $progressResponse = $this->actingAs($admin)->post(route('admin.complaints.status', $complaint->id), [
            'status' => 'in_progress'
        ]);
        $progressResponse->assertRedirect();
        
        $complaint->refresh();
        $this->assertEquals('in_progress', $complaint->status);
        $this->assertEquals($admin->id, $complaint->handled_by_user_id);
        $this->assertNull($complaint->resolved_at);

        // Dashboard admin kini mencatat 0 open dan 1 in_progress
        $dashResponse2 = $this->actingAs($admin)->get(route('admin.dashboard'));
        $dashResponse2->assertViewHas('open_complaints', 0);
        $dashResponse2->assertViewHas('in_progress_complaints', 1);

        // 3. Admin ubah status ke resolved
        $resolvedResponse = $this->actingAs($admin)->post(route('admin.complaints.status', $complaint->id), [
            'status' => 'resolved'
        ]);
        $resolvedResponse->assertRedirect();

        $complaint->refresh();
        $this->assertEquals('resolved', $complaint->status);
        $this->assertEquals($admin->id, $complaint->handled_by_user_id);
        $this->assertNotNull($complaint->resolved_at);

        // Dashboard admin kini mencatat 0 open dan 0 in_progress
        $dashResponse3 = $this->actingAs($admin)->get(route('admin.dashboard'));
        $dashResponse3->assertViewHas('open_complaints', 0);
        $dashResponse3->assertViewHas('in_progress_complaints', 0);

        // 4. Verifikasi sisi pelanggan: API live tracking komplain
        $apiTrackResponse = $this->getJson(route('api.complaint.track', ['query' => 'CMP-TEST-003']));
        $apiTrackResponse->assertStatus(200);
        $apiTrackResponse->assertJson([
            'found' => true,
            'complaint_code' => 'CMP-TEST-003',
            'status' => 'resolved',
            'status_label' => 'Selesai Ditangani',
            'order_code' => 'ORD-COMP-003',
        ]);

        // Verifikasi sisi pelanggan: tracking komplain via nomor WhatsApp
        $apiPhoneResponse = $this->getJson(route('api.complaint.track', ['query' => '081234567890']));
        $apiPhoneResponse->assertStatus(200);
        $apiPhoneResponse->assertJson(['found' => true, 'complaint_code' => 'CMP-TEST-003']);

        // Verifikasi halaman FAQ dengan tiket
        $faqResponse = $this->get(route('page.faq', ['ticket' => 'CMP-TEST-003']));
        $faqResponse->assertStatus(200);
        $faqResponse->assertSee('CMP-TEST-003');

        // Verifikasi halaman order tracking menampilkan status komplain
        $trackOrderResponse = $this->get(route('customer.tracking', ['order' => 'ORD-COMP-003']));
        $trackOrderResponse->assertStatus(200);
        $trackOrderResponse->assertSee('CMP-TEST-003');
        $trackOrderResponse->assertSee('Selesai Ditangani');
    }
}
