<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Tenant;
use App\Models\Product;
use App\Models\Customer;
use App\Models\DeliveryLocation;
use App\Models\User;

class DummyOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Guard Production
        if (app()->environment('production')) {
            $this->command?->error('ERROR: DummyOrderSeeder tidak diizinkan berjalan di environment production!');
            return;
        }

        $this->command?->info('Membersihkan data dummy lama (idempotent)...');
        $cleanStats = self::clean();
        $this->command?->line(sprintf(
            "Data lama terhapus -> Pesanan: %d, Item: %d, Komplain: %d, Customer Uji: %d",
            $cleanStats['deletedOrders'],
            $cleanStats['deletedItems'],
            $cleanStats['deletedComplaints'],
            $cleanStats['deletedCustomers']
        ));

        // 2. Siapkan 30 Customer Uji
        $this->command?->info('Membuat 30 profil customer uji...');
        $testCustomerNames = [
            'Aditya Pratama', 'Bima Sakti', 'Citra Lestari', 'Dedi Kurniawan', 'Eka Putri',
            'Fajar Ramadhan', 'Gita Permata', 'Hadi Wijaya', 'Indah Kusuma', 'Joko Susilo',
            'Kartika Sari', 'Lukman Hakim', 'Maya Anggraini', 'Nanda Saputra', 'Olivia Tan',
            'Panji Wibowo', 'Qori Amelia', 'Rian Hidayat', 'Siti Rahma', 'Taufik Ismail',
            'Utami Dewi', 'Vicky Prasetyo', 'Wahyu Setiawan', 'Xavier Putra', 'Yuliana Nur',
            'Zainal Abidin', 'Aris Munandar', 'Bela Safitri', 'Cahyo Utomo', 'Dian Anggraini'
        ];

        $dummyCustomerIds = [];
        foreach ($testCustomerNames as $idx => $name) {
            $phone = sprintf('089999000%02d', $idx + 1);
            $customerId = DB::table('customers')->insertGetId([
                'name' => $name,
                'phone_number' => $phone,
                'first_order_at' => Carbon::parse('2026-09-14 07:00:00'),
                'last_order_at' => Carbon::parse('2026-10-15 19:00:00'),
                'total_orders' => 0,
                'is_blocked' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $dummyCustomerIds[] = $customerId;
        }

        // Gabungkan seluruh customer ID yang ada (data asli + customer uji)
        $allCustomerIds = DB::table('customers')->pluck('id')->all();

        // 3. Ambil data master tenant dan produk yang valid
        $tenantsWithProducts = Tenant::where('is_active', true)
            ->whereHas('products', function ($q) {
                $q->where('is_available', true);
            })
            ->with(['products' => function ($q) {
                $q->where('is_available', true);
            }])
            ->get()
            ->keyBy('id');

        if ($tenantsWithProducts->isEmpty()) {
            $this->command?->error('Tidak ditemukan tenant aktif yang memiliki produk.');
            return;
        }

        // Tentukan 4 tenant "paling ramai" untuk variasi utilisasi kapasitas
        $availableTenantIds = $tenantsWithProducts->keys()->all();
        $preferredBusy = [2, 8, 1, 23]; // Starbucks, Solaria, A&W, Marugame Udon
        $busyTenantIds = array_values(array_intersect($preferredBusy, $availableTenantIds));
        if (count($busyTenantIds) < 3) {
            $busyTenantIds = array_slice($availableTenantIds, 0, 4);
        }
        $regularTenantIds = array_values(array_diff($availableTenantIds, $busyTenantIds));

        $this->command?->info(sprintf(
            "Mengalokasikan tenant ramai (%d tenant: ID %s) dan regular (%d tenant)...",
            count($busyTenantIds),
            implode(', ', $busyTenantIds),
            count($regularTenantIds)
        ));

        $deliveryLocations = DeliveryLocation::where('is_active', true)->get();
        $adminOpsUser = User::where('role', 'admin_ops')->first();

        // 4. Buat 200 Pesanan Terdistribusi (14 Sept - 15 Okt 2026)
        $this->command?->info('Menyisipkan 200 pesanan reguler...');

        $startDate = Carbon::parse('2026-09-14', 'Asia/Jakarta')->startOfDay();
        $endDate = Carbon::parse('2026-10-15', 'Asia/Jakarta')->endOfDay();

        // Siapkan distribusi pesanan: 200 pesanan dibagi ke 32 hari (rata-rata 6-7 order/hari)
        $targetRegularOrders = 200;
        $orderIndex = 1;
        $selesaiOrderIds = [];

        // Buat array tanggal berurutan
        $dateList = [];
        $cur = $startDate->copy();
        while ($cur->lte($endDate)) {
            $dateList[] = $cur->format('Y-m-d');
            $cur->addDay();
        }

        $ordersPerDate = [];
        $remaining = $targetRegularOrders;
        foreach ($dateList as $i => $d) {
            $isLast = ($i === count($dateList) - 1);
            if ($isLast) {
                $ordersPerDate[$d] = $remaining;
            } else {
                $count = rand(5, 8);
                $ordersPerDate[$d] = min($count, $remaining);
                $remaining -= $ordersPerDate[$d];
            }
        }

        // Komposisi 200 pesanan reguler:
        // Selesai: 156 (78%), Dibatalkan: 20 (10%), Menunggu: 8 (4%), Diproses: 8 (4%), Siap: 8 (4%)
        // Pesanan aktif (menunggu, diproses, siap) ditempatkan pada tanggal-tanggal terkini (13-15 Okt 2026)
        $recentActivePool = [
            'menunggu', 'menunggu', 'menunggu', 'menunggu', 'menunggu', 'menunggu', 'menunggu', 'menunggu',
            'diproses', 'diproses', 'diproses', 'diproses', 'diproses', 'diproses', 'diproses', 'diproses',
            'siap', 'siap', 'siap', 'siap', 'siap', 'siap', 'siap', 'siap',
        ];
        shuffle($recentActivePool);

        $historicalPool = array_merge(
            array_fill(0, 156, 'selesai'),
            array_fill(0, 20, 'dibatalkan')
        );
        shuffle($historicalPool);

        $flights = ['GA-312', 'GA-320', 'JT-690', 'JT-714', 'QG-445', 'QG-882', 'ID-721', 'SJ-182', 'QZ-804', 'IU-512'];
        $gates = ['Gate 1', 'Gate 2', 'Gate 3', 'Gate 4', 'Gate 5', 'Gate 6', 'Gate 7', 'Gate 8', 'Gate 9', 'Gate 11', 'Gate 12'];

        foreach ($dateList as $dateStr) {
            $dayOrderCount = $ordersPerDate[$dateStr] ?? 6;

            for ($k = 0; $k < $dayOrderCount; $k++) {
                // Untuk 3 hari terakhir (13-15 Okt), ambil dari pool aktif jika masih ada
                if ($dateStr >= '2026-10-13' && !empty($recentActivePool)) {
                    $status = array_pop($recentActivePool);
                } else {
                    $status = array_pop($historicalPool) ?? 'selesai';
                }

                // 50% kemungkinan jatuh ke tenant ramai, 50% ke tenant regular
                if (rand(1, 100) <= 50 && !empty($busyTenantIds)) {
                    $tenantId = $busyTenantIds[array_rand($busyTenantIds)];
                } else {
                    $tenantId = !empty($regularTenantIds) 
                        ? $regularTenantIds[array_rand($regularTenantIds)]
                        : $availableTenantIds[array_rand($availableTenantIds)];
                }

                $tenant = $tenantsWithProducts[$tenantId];
                $tenantProducts = $tenant->products;

                // Tentukan jam pesanan sesuai profil jam sibuk bandara:
                // 40% di jam makan siang 11:00-12:59
                // 25% di pagi hari 06:00-10:59
                // 25% di sore/malam 13:00-19:59
                // 10% di luar jam sibuk (05:00-05:59 atau 20:00-22:59)
                $hourRand = rand(1, 100);
                if ($hourRand <= 40) {
                    $hour = rand(11, 12);
                } elseif ($hourRand <= 65) {
                    $hour = rand(6, 10);
                } elseif ($hourRand <= 90) {
                    $hour = rand(13, 19);
                } else {
                    $hour = rand(20, 22);
                }
                $minute = rand(0, 59);
                $second = rand(0, 59);

                $orderedAt = Carbon::parse("{$dateStr} {$hour}:{$minute}:{$second}", 'Asia/Jakarta');

                // Customer
                $customerId = $allCustomerIds[array_rand($allCustomerIds)];
                $customer = DB::table('customers')->find($customerId);
                $customerName = $customer->name ?? 'Penumpang Juanda';

                // Flight info (70% penumpang, 30% pengunjung)
                $isPassenger = (rand(1, 100) <= 70);
                if ($isPassenger) {
                    $flightNumber = $flights[array_rand($flights)];
                    $gate = $gates[array_rand($gates)];
                    $boardingTime = (clone $orderedAt)->addMinutes(rand(35, 120));
                    $autoCancelAt = (clone $boardingTime)->subMinutes(15);
                    if ($autoCancelAt->lte($orderedAt)) {
                        $autoCancelAt = (clone $orderedAt)->addMinutes(15);
                    }
                } else {
                    $flightNumber = null;
                    $gate = null;
                    $boardingTime = null;
                    $autoCancelAt = (clone $orderedAt)->addMinutes(15);
                }

                // Pickup Method (60% ambil_sendiri, 40% diantar)
                $isDelivery = (rand(1, 100) <= 40 && $deliveryLocations->isNotEmpty());
                $pickupMethod = $isDelivery ? 'diantar' : 'ambil_sendiri';
                $deliveryLocationId = null;
                $deliveryFee = null;

                if ($isDelivery) {
                    $deliveryLoc = $deliveryLocations->random();
                    $deliveryLocationId = $deliveryLoc->id;
                    $deliveryFee = $tenant->delivery_fee ?? 10000.00;
                }

                // SLA & Status Details:
                // ready_at diisi jika status siap atau selesai
                // Selisih 5 sampai 22 menit (~80% <= 15 min, ~20% 16-22 min)
                $readyAt = null;
                $completedAt = null;

                if (in_array($status, ['siap', 'selesai'])) {
                    $isPeak = in_array($hour, [11, 12]);
                    $slaLateProb = $isPeak ? 30 : 15; // sedikit lebih lambat saat jam sibuk
                    
                    if (rand(1, 100) <= $slaLateProb) {
                        $slaMinutes = rand(16, 22); // Lewat SLA (>15m)
                    } else {
                        $slaMinutes = rand(6, 15);  // Patuh SLA (<=15m)
                    }
                    $readyAt = (clone $orderedAt)->addMinutes($slaMinutes);

                    if ($status === 'selesai') {
                        $completedAt = (clone $readyAt)->addMinutes(rand(2, 10));
                    }
                }

                // is_paid: true untuk yang bukan dibatalkan
                $isPaid = ($status !== 'dibatalkan');
                $paymentMethod = $isPaid ? (rand(1, 100) <= 80 ? 'qris' : 'transfer') : 'qris';

                $orderCode = sprintf('DUMMY-ORD-%04d', $orderIndex++);

                $orderId = DB::table('orders')->insertGetId([
                    'order_code' => $orderCode,
                    'tenant_id' => $tenantId,
                    'customer_id' => $customerId,
                    'customer_name' => $customerName,
                    'flight_number' => $flightNumber,
                    'gate' => $gate,
                    'boarding_time' => $boardingTime,
                    'status' => $status,
                    'payment_method' => $paymentMethod,
                    'is_paid' => $isPaid,
                    'pickup_method' => $pickupMethod,
                    'heading_to_tenant_at' => in_array($status, ['diproses', 'siap', 'selesai']) ? (clone $orderedAt)->addMinutes(rand(2, 5)) : null,
                    'ready_at' => $readyAt,
                    'auto_cancel_at' => $autoCancelAt,
                    'total_amount' => 0.00, // di-update setelah items
                    'ordered_at' => $orderedAt,
                    'completed_at' => $completedAt,
                    'delivery_location_id' => $deliveryLocationId,
                    'delivery_fee' => $deliveryFee,
                    'created_at' => $orderedAt,
                    'updated_at' => $completedAt ?? $readyAt ?? $orderedAt,
                ]);

                if ($status === 'selesai') {
                    $selesaiOrderIds[] = [
                        'order_id' => $orderId,
                        'customer_name' => $customerName,
                        'phone_number' => $customer->phone_number ?? '081234567890',
                        'ordered_at' => $orderedAt,
                    ];
                }

                // Order Items (1-4 item)
                $itemCount = min(rand(1, 4), $tenantProducts->count());
                $selectedProducts = $tenantProducts->random($itemCount);
                if ($selectedProducts instanceof Product) {
                    $selectedProducts = collect([$selectedProducts]);
                }

                $totalAmount = 0.00;
                foreach ($selectedProducts as $prod) {
                    $qty = rand(1, 3);
                    $subtotal = round($prod->price * $qty, 2);
                    $totalAmount += $subtotal;

                    DB::table('order_items')->insert([
                        'order_id' => $orderId,
                        'product_id' => $prod->id,
                        'product_name_snapshot' => $prod->name,
                        'quantity' => $qty,
                        'unit_price' => $prod->price,
                        'subtotal' => $subtotal,
                        'created_at' => $orderedAt,
                    ]);
                }

                if ($deliveryFee) {
                    $totalAmount += $deliveryFee;
                }

                DB::table('orders')->where('id', $orderId)->update(['total_amount' => $totalAmount]);
            }
        }

        // 5. Tambahkan 3 Pesanan Anomali (DUMMY-ANOM)
        $this->command?->info('Menyisipkan 3 pesanan anomali (DUMMY-ANOM)...');

        // Anomali 1: tenant_id null (ETL cleaning -> Dibuang)
        $cid = $allCustomerIds[0];
        $c1 = DB::table('customers')->find($cid);
        $anomDate1 = Carbon::parse('2026-09-20 11:30:00', 'Asia/Jakarta');
        DB::table('orders')->insert([
            'order_code' => 'DUMMY-ANOM-001',
            'tenant_id' => null,
            'customer_id' => $cid,
            'customer_name' => $c1->name ?? 'Anonim Anomali 1',
            'status' => 'selesai',
            'is_paid' => true,
            'pickup_method' => 'ambil_sendiri',
            'payment_method' => 'qris',
            'auto_cancel_at' => (clone $anomDate1)->addMinutes(15),
            'total_amount' => 35000.00,
            'ordered_at' => $anomDate1,
            'ready_at' => (clone $anomDate1)->addMinutes(10),
            'completed_at' => (clone $anomDate1)->addMinutes(15),
            'created_at' => $anomDate1,
            'updated_at' => $anomDate1,
        ]);

        // Anomali 2: tenant_id null (ETL cleaning -> Dibuang)
        $anomDate2 = Carbon::parse('2026-10-05 12:15:00', 'Asia/Jakarta');
        DB::table('orders')->insert([
            'order_code' => 'DUMMY-ANOM-002',
            'tenant_id' => null,
            'customer_id' => $cid,
            'customer_name' => $c1->name ?? 'Anonim Anomali 2',
            'status' => 'selesai',
            'is_paid' => true,
            'pickup_method' => 'ambil_sendiri',
            'payment_method' => 'qris',
            'auto_cancel_at' => (clone $anomDate2)->addMinutes(15),
            'total_amount' => 45000.00,
            'ordered_at' => $anomDate2,
            'ready_at' => (clone $anomDate2)->addMinutes(12),
            'completed_at' => (clone $anomDate2)->addMinutes(18),
            'created_at' => $anomDate2,
            'updated_at' => $anomDate2,
        ]);

        // Anomali 3: ready_at lebih awal dari ordered_at (ETL SLA Guard -> diabaikan dari SLA)
        $validTid = $availableTenantIds[0];
        $anomDate3 = Carbon::parse('2026-10-10 14:00:00', 'Asia/Jakarta');
        $anom3Id = DB::table('orders')->insertGetId([
            'order_code' => 'DUMMY-ANOM-003',
            'tenant_id' => $validTid,
            'customer_id' => $cid,
            'customer_name' => $c1->name ?? 'Pelanggan Anomali 3',
            'status' => 'selesai',
            'is_paid' => true,
            'pickup_method' => 'ambil_sendiri',
            'payment_method' => 'qris',
            'auto_cancel_at' => (clone $anomDate3)->addMinutes(15),
            'total_amount' => 28000.00,
            'ordered_at' => $anomDate3,
            'ready_at' => (clone $anomDate3)->subMinutes(10), // ANOMALI: ready_at 10 menit sebelum ordered_at
            'completed_at' => (clone $anomDate3)->addMinutes(5),
            'created_at' => $anomDate3,
            'updated_at' => $anomDate3,
        ]);

        // Tambahkan item untuk Anomali 3 agar konsisten
        $prodAnom = $tenantsWithProducts[$validTid]->products->first();
        if ($prodAnom) {
            DB::table('order_items')->insert([
                'order_id' => $anom3Id,
                'product_id' => $prodAnom->id,
                'product_name_snapshot' => $prodAnom->name,
                'quantity' => 1,
                'unit_price' => $prodAnom->price,
                'subtotal' => $prodAnom->price,
                'created_at' => $anomDate3,
            ]);
            DB::table('orders')->where('id', $anom3Id)->update(['total_amount' => $prodAnom->price]);
        }

        // 6. Buat 10 Komplain Uji pada Pesanan Selesai
        $this->command?->info('Menyisipkan 10 komplain uji...');
        $complaintTemplates = [
            ['cat' => 'pesanan_salah', 'desc' => 'Minuman tertukar: memesan Caffe Latte panas tetapi yang diterima es kopi manis.', 'status' => 'resolved'],
            ['cat' => 'pesanan_salah', 'desc' => 'Jumlah pesanan kurang 1 porsi roti canai dari struk yang tertera.', 'status' => 'closed'],
            ['cat' => 'status_tidak_update', 'desc' => 'Status di tracking masih menunjukkan Sedang Dimasak padahal makanan sudah diambil di konter.', 'status' => 'resolved'],
            ['cat' => 'status_tidak_update', 'desc' => 'Notifikasi pesanan siap terlambat muncul 15 menit setelah pesanan diserahkan.', 'status' => 'in_progress'],
            ['cat' => 'lainnya', 'desc' => 'Kemasan take-away rusak/bocor kuahnya saat serah terima di boarding lounge.', 'status' => 'open'],
            ['cat' => 'pesanan_salah', 'desc' => 'Tingkat kepedasan sambal tidak sesuai dengan catatan pesanan (minta tidak pedas).', 'status' => 'in_progress'],
            ['cat' => 'lainnya', 'desc' => 'Sendok dan tisu tidak disertakan dalam kantong makanan.', 'status' => 'closed'],
            ['cat' => 'status_tidak_update', 'desc' => 'Layar pelacakan sempat error saat refresh di Gate 3 Terminal 1.', 'status' => 'open'],
            ['cat' => 'lainnya', 'desc' => 'Waktu antar oleh porter melebihi estimasi awal karena kendala antrean di security gate.', 'status' => 'resolved'],
            ['cat' => 'pesanan_salah', 'desc' => 'Ukuran cup minuman yang diberikan reguler, padahal di pesanan memilih ukuran besar.', 'status' => 'open'],
        ];

        shuffle($selesaiOrderIds);
        foreach ($complaintTemplates as $i => $tmpl) {
            $refOrder = $selesaiOrderIds[$i] ?? null;
            $code = sprintf('DUMMY-CMP-%03d', $i + 1);

            $resolvedAt = null;
            if (in_array($tmpl['status'], ['resolved', 'closed'])) {
                $baseTime = $refOrder['ordered_at'] ?? now();
                $resolvedAt = (clone $baseTime)->addHours(rand(1, 4));
            }

            DB::table('complaints')->insert([
                'complaint_code' => $code,
                'order_id' => $refOrder['order_id'] ?? null,
                'reporter_name' => $refOrder['customer_name'] ?? 'Pelanggan Juanda',
                'reporter_contact' => $refOrder['phone_number'] ?? '081234567890',
                'category' => $tmpl['cat'],
                'description' => $tmpl['desc'],
                'status' => $tmpl['status'],
                'handled_by_user_id' => in_array($tmpl['status'], ['in_progress', 'resolved', 'closed']) ? $adminOpsUser?->id : null,
                'resolved_at' => $resolvedAt,
                'created_at' => $refOrder['ordered_at'] ?? now(),
                'updated_at' => $resolvedAt ?? now(),
            ]);
        }

        $this->command?->info('DummyOrderSeeder selesai dengan sukses! 203 pesanan & 10 komplain telah disiapkan.');
    }

    /**
     * Helper pembersih data dummy yang dapat dipanggil kembali kapan saja.
     */
    public static function clean(): array
    {
        $dummyOrderIds = DB::table('orders')->where('order_code', 'like', 'DUMMY-%')->pluck('id');
        $deletedOrders = 0;
        $deletedItems = 0;
        $deletedComplaints = 0;
        $deletedCustomers = 0;

        if ($dummyOrderIds->isNotEmpty()) {
            $deletedComplaints += DB::table('complaints')->whereIn('order_id', $dummyOrderIds)->delete();
            $deletedItems += DB::table('order_items')->whereIn('order_id', $dummyOrderIds)->delete();
            $deletedOrders += DB::table('orders')->whereIn('id', $dummyOrderIds)->delete();
        }

        // Hapus komplain dummy yang kodenya DUMMY- jika masih ada
        $deletedComplaints += DB::table('complaints')->where('complaint_code', 'like', 'DUMMY-%')->delete();

        // Hapus 30 customer uji
        $deletedCustomers += DB::table('customers')->where('phone_number', 'like', '089999%')->delete();

        return compact('deletedOrders', 'deletedItems', 'deletedComplaints', 'deletedCustomers');
    }
}
