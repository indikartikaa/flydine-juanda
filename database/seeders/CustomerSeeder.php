<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as FakerFactory;

/**
 * Dummy: 20-40 profil pelanggan berbasis nomor HP (PRD 17.1.3).
 * first_order_at/last_order_at/total_orders akan disinkronkan ulang oleh
 * OrderSeeder setelah data orders terbentuk.
 */
class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $faker = FakerFactory::create('id_ID');
        $count = rand(20, 40);
        $usedPhones = [];

        for ($i = 0; $i < $count; $i++) {
            do {
                $phone = '08' . $faker->numerify('##########');
            } while (in_array($phone, $usedPhones));
            $usedPhones[] = $phone;

            DB::table('customers')->insert([
                'phone_number' => $phone,
                'name' => $faker->name(),
                'first_order_at' => null,
                'last_order_at' => null,
                'total_orders' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
