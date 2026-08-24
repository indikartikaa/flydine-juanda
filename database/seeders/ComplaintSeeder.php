<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as FakerFactory;

/**
 * Dummy: 5-15 tiket komplain (PRD 17.1.3), masing-masing terhubung ke sebuah
 * order acak dan (kadang) ditangani oleh salah satu akun admin_ops.
 */
class ComplaintSeeder extends Seeder
{
    private array $descriptions = [
        'pesanan_salah' => 'Produk yang diterima tidak sesuai dengan yang dipesan di aplikasi.',
        'status_tidak_update' => 'Status pesanan tidak diperbarui oleh tenant meski pemesan sudah menunggu lama.',
        'lainnya' => 'Pemesan mengalami kendala lain saat proses pengambilan pesanan di counter tenant.',
    ];

    public function run(): void
    {
        $faker = FakerFactory::create('id_ID');
        $orderIds = DB::table('orders')->pluck('id')->all();
        $adminIds = DB::table('users')->where('role', 'admin_ops')->pluck('id')->all();
        $count = rand(5, 15);
        $categories = array_keys($this->descriptions);
        $statuses = ['open', 'in_progress', 'resolved', 'closed'];

        for ($i = 1; $i <= $count; $i++) {
            $category = $faker->randomElement($categories);
            $status = $faker->randomElement($statuses);
            $handledBy = in_array($status, ['resolved', 'closed']) && !empty($adminIds)
                ? $faker->randomElement($adminIds) : null;
            $createdAt = now()->subDays(rand(0, 14));

            DB::table('complaints')->insert([
                'complaint_code' => 'CMP-' . $createdAt->format('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'order_id' => $faker->randomElement($orderIds),
                'category' => $category,
                'description' => $this->descriptions[$category],
                'status' => $status,
                'handled_by_user_id' => $handledBy,
                'resolved_at' => in_array($status, ['resolved', 'closed'])
                    ? (clone $createdAt)->addHours(rand(1, 48)) : null,
                'created_at' => $createdAt,
                'updated_at' => now(),
            ]);
        }
    }
}
