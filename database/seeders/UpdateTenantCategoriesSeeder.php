<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateTenantCategoriesSeeder extends Seeder
{
    /**
     * Pemetaan resmi tenant F&B ke kategori yang diizinkan form registrasi.
     * Idempotent dan HANYA menyentuh baris yang masih NULL.
     */
    public function run(): void
    {
        $mappings = [
            1  => 'Fast Food & Restaurant',   // A&W (FB-01-01)
            2  => 'Beverages & Coffee',       // Starbucks (FB-02-05)
            3  => 'Bakery & Pastry',          // Dunkin' Donuts (EP-01-03)
            4  => 'Fast Food & Restaurant',   // Subway (FB-02-08A)
            5  => 'Bakery & Pastry',          // Beard Papa's (EP-01-01)
            6  => 'Bakery & Pastry',          // Roti O (FB-01-04)
            7  => 'Bakery & Pastry',          // Roti Boy (FB-01-05)
            8  => 'Fast Food & Restaurant',   // Solaria (FB-01-08)
            9  => 'Beverages & Coffee',       // Killiney (FB-01-19)
            10 => 'Indonesian Traditional',  // Bakmi Gocit (FB-01-02)
            11 => 'Indonesian Traditional',  // Bakso Pak Djo (FB-02-02)
            12 => 'Indonesian Traditional',  // Sari Bundo (FB-01-13)
            13 => 'Beverages & Coffee',      // Java Café (FB-01-11)
            14 => 'Beverages & Coffee',      // Mm Juice (FB-01-09A)
            15 => 'Fast Food & Restaurant',  // Wingman (FB-01-12B)
            16 => 'Indonesian Traditional',  // Kulineri Sandang Pangan (FB-21-1)
            17 => 'Beverages & Coffee',      // OLDTOWN White Coffee (FB-21-2)
            18 => 'Indonesian Traditional',  // Bakmi Gocit (FB-21-3)
            19 => 'Indonesian Traditional',  // Bebek Pak Qomar & Rawon (FB-21-4)
            20 => 'Bakery & Pastry',         // Roti O (FB-21-7)
            21 => 'Beverages & Coffee',      // Killiney (FB-22-1)
            22 => 'Bakery & Pastry',         // Le Petit Jemma (FB-22-2)
            23 => 'Fast Food & Restaurant',  // Marugame Udon (FB-22-3)
            24 => 'Indonesian Traditional',  // Padang Merdeka (FB-22-4)
            25 => 'Beverages & Coffee',      // OLDTOWN White Coffee (FB-22-5)
            26 => 'Beverages & Coffee',      // Gloria Jean's Coffee (FB-22-6)
        ];

        foreach ($mappings as $tenantId => $category) {
            DB::table('tenants')
                ->where('id', $tenantId)
                ->whereNull('category')
                ->update(['category' => $category]);
        }
    }
}
