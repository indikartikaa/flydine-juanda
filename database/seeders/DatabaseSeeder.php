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
            Terminal1Seeder::class,  // 60 Tenant & 672 Menu Resmi Terminal 1 (T1)
            Terminal2Seeder::class,  // 16 Tenant & 239 Menu Resmi Terminal 2 (T2)
            DeliveryLocationSeeder::class,
            TenantCategorySeeder::class,
            UserSeeder::class,       // Akun Admin Ops & Staff cadangan
            ProductSeeder::class,    // dummy
            CustomerSeeder::class,   // dummy
            // OrderSeeder::class,      // dummy (order_items dibuat sekaligus di sini)
            // ComplaintSeeder::class,  // dummy
        ]);
    }
}
