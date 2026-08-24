<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as FakerFactory;

/**
 * Dummy: 3-6 produk per tenant, mengikuti arahan dosen wali/pembimbing bahwa
 * katalog masih boleh dummy sampai data resmi tersedia (PRD Bagian 17.1.3).
 * Nama produk memakai daftar menu F&B generik (bukan hasil scraping brand asli).
 */
class ProductSeeder extends Seeder
{
    private array $menuPool = [
        'Nasi Goreng Spesial', 'Mie Goreng Jawa', 'Ayam Geprek', 'Roti Bakar Coklat',
        'Kopi Susu Gula Aren', 'Es Teh Manis', 'Sandwich Ayam', 'Donat Klasik',
        'Croissant Butter', 'Salad Buah', 'Jus Alpukat', 'Bakso Kuah',
        'Soto Ayam', 'Burger Daging', 'Kentang Goreng', 'Cappuccino',
        'Es Kopi Susu', 'Roti Isi Sosis', 'Puding Coklat', 'Nasi Uduk',
    ];

    public function run(): void
    {
        $faker = FakerFactory::create('id_ID');
        $tenants = DB::table('tenants')->pluck('id');

        foreach ($tenants as $tenantId) {
            $count = rand(3, 6);
            $menus = (array) array_rand(array_flip($this->menuPool), $count);

            foreach ($menus as $menuName) {
                DB::table('products')->insert([
                    'tenant_id' => $tenantId,
                    'name' => $menuName,
                    'price' => $faker->numberBetween(15, 60) * 1000,
                    'is_available' => $faker->boolean(90),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
