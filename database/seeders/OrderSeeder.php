<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as FakerFactory;
use Carbon\Carbon;

/**
 * Dummy: 40-80 pesanan (PRD 17.1.3) beserta order_items-nya, dibuat sekaligus
 * dalam satu seeder agar rincian item selalu konsisten dengan tenant pemilik
 * pesanan (satu order hanya boleh berisi produk dari satu tenant — Bagian 11.1).
 *
 * Auto-cancel dua jalur (Bagian 6.2 / 11.2):
 *  - jika flight_number diisi -> auto_cancel_at mengikuti boarding_time.
 *  - jika tidak -> auto_cancel_at = ordered_at + timer tetap 45-60 menit.
 */
class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $faker = FakerFactory::create('id_ID');
        $tenantIds = DB::table('tenants')->pluck('id')->all();
        $customerIds = DB::table('customers')->pluck('id')->all();
        $statuses = ['menunggu', 'diproses', 'siap', 'selesai', 'dibatalkan'];
        $orderCount = rand(40, 80);

        // orderId => customerId, untuk update rekap customers di akhir
        $customerOrderLog = [];

        for ($i = 1; $i <= $orderCount; $i++) {
            $tenantId = $faker->randomElement($tenantIds);
            $customerId = $faker->randomElement($customerIds);
            $customer = DB::table('customers')->find($customerId);

            $products = DB::table('products')->where('tenant_id', $tenantId)
                ->where('is_available', true)->get();
            if ($products->isEmpty()) {
                continue;
            }

            $ordered_at = Carbon::now()->subDays(rand(0, 21))->subMinutes(rand(0, 720));
            $hasFlight = $faker->boolean(55);

            if ($hasFlight) {
                $boarding_time = (clone $ordered_at)->addMinutes(rand(35, 150));
                $auto_cancel_at = (clone $boarding_time)->subMinutes(20);
                $flight_number = 'QG-' . $faker->numberBetween(100, 999);
                $gate = $faker->randomElement(['A1', 'A2', 'B3', 'B4', 'C1', 'C2', 'D5']);
            } else {
                $boarding_time = null;
                $auto_cancel_at = (clone $ordered_at)->addMinutes($faker->randomElement([45, 50, 60]));
                $flight_number = null;
                $gate = null;
            }

            $status = $faker->randomElement($statuses);
            $heading_to_tenant_at = in_array($status, ['diproses', 'siap', 'selesai'])
                ? (clone $ordered_at)->addMinutes(rand(2, 15)) : null;
            $completed_at = $status === 'selesai'
                ? (clone $ordered_at)->addMinutes(rand(15, 40)) : null;

            $orderCode = 'FD-' . $ordered_at->format('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT);

            $orderId = DB::table('orders')->insertGetId([
                'order_code' => $orderCode,
                'tenant_id' => $tenantId,
                'customer_id' => $customerId,
                'customer_name' => $customer->name ?? $faker->name(),
                'flight_number' => $flight_number,
                'gate' => $gate,
                'boarding_time' => $boarding_time,
                'status' => $status,
                'heading_to_tenant_at' => $heading_to_tenant_at,
                'auto_cancel_at' => $auto_cancel_at,
                'total_amount' => 0, // diisi ulang setelah order_items dibuat
                'ordered_at' => $ordered_at,
                'completed_at' => $completed_at,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $itemCount = rand(1, 4);
            $chosenProducts = $products->random(min($itemCount, $products->count()));
            $chosenProducts = $chosenProducts instanceof \Illuminate\Support\Collection
                ? $chosenProducts : collect([$chosenProducts]);

            $total = 0;
            foreach ($chosenProducts as $product) {
                $qty = rand(1, 3);
                $subtotal = $qty * $product->price;
                $total += $subtotal;

                DB::table('order_items')->insert([
                    'order_id' => $orderId,
                    'product_id' => $product->id,
                    'product_name_snapshot' => $product->name,
                    'quantity' => $qty,
                    'unit_price' => $product->price,
                    'subtotal' => $subtotal,
                    'created_at' => now(),
                ]);
            }

            DB::table('orders')->where('id', $orderId)->update(['total_amount' => $total]);

            $customerOrderLog[$customerId][] = $ordered_at;
        }

        // Sinkronkan rekap pada customers (first_order_at, last_order_at, total_orders)
        foreach ($customerOrderLog as $customerId => $dates) {
            DB::table('customers')->where('id', $customerId)->update([
                'first_order_at' => min($dates),
                'last_order_at' => max($dates),
                'total_orders' => count($dates),
                'updated_at' => now(),
            ]);
        }
    }
}
