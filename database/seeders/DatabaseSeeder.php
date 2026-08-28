<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Urutan WAJIB mengikuti dependensi foreign key:
     * tenants (nyata) -> users & products & customers (dummy) -> orders + order_items (dummy)
     * -> complaints (dummy).
     */
    public function run(): void
    {
        $this->call([
            TenantSeeder::class,     // data NYATA dari mitra usaha (F&B), lihat catatan di file ini
            UserSeeder::class,       // dummy
            ProductSeeder::class,    // dummy
            CustomerSeeder::class,   // dummy
            // OrderSeeder::class,      // dummy (order_items dibuat sekaligus di sini)
            ComplaintSeeder::class,  // dummy
        ]);
    }
}
