<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Data tenant F&B NYATA (bukan dummy), diambil dari
 * DATABASE_MITRA_USAHA_SUB_2026_WEBSITE.xlsx, difilter JENIS USAHA = 'F&B'.
 *
 * tenant_code = KODE RUANG (unik pada data mitra usaha).
 * name        = BRAND.
 * floor_location hanya berisi info Terminal (1/2); detail lantai per tenant,
 * jam operasional, dan kontak PIC masih menunggu data resmi (lihat PRD Bagian 10).
 */
class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $tenants = [
            // Terminal 1 (15 Tenants)
            ['tenant_code' => 'FB-01-01', 'name' => 'A&W', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-02-05', 'name' => 'Starbucks', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'EP-01-03', 'name' => 'Dunkin\' Donuts', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-02-08A', 'name' => 'Subway', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'EP-01-01', 'name' => 'Beard Papa\'s', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-04', 'name' => 'Roti O', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-05', 'name' => 'Roti Boy', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-08', 'name' => 'Solaria', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-19', 'name' => 'Killiney', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-02', 'name' => 'Bakmi Gocit', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-02-02', 'name' => 'Bakso Pak Djo', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-13', 'name' => 'Sari Bundo', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-11', 'name' => 'Java Café', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-09A', 'name' => 'Mm Juice', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-12B', 'name' => 'Wingman', 'floor_location' => 'Terminal 1'],

            // Terminal 2 (11 Tenants)
            ['tenant_code' => 'FB-21-1', 'name' => 'Kulineri Sandang Pangan', 'floor_location' => 'Terminal 2'],
            ['tenant_code' => 'FB-21-2', 'name' => 'OLDTOWN White Coffee', 'floor_location' => 'Terminal 2'],
            ['tenant_code' => 'FB-21-3', 'name' => 'Bakmi Gocit', 'floor_location' => 'Terminal 2'],
            ['tenant_code' => 'FB-21-4', 'name' => 'Bebek Pak Qomar & Rawon', 'floor_location' => 'Terminal 2'],
            ['tenant_code' => 'FB-21-7', 'name' => 'Roti O', 'floor_location' => 'Terminal 2'],
            ['tenant_code' => 'FB-22-1', 'name' => 'Killiney', 'floor_location' => 'Terminal 2'],
            ['tenant_code' => 'FB-22-2', 'name' => 'Le Petit Jemma', 'floor_location' => 'Terminal 2'],
            ['tenant_code' => 'FB-22-3', 'name' => 'Marugame Udon', 'floor_location' => 'Terminal 2'],
            ['tenant_code' => 'FB-22-4', 'name' => 'Padang Merdeka', 'floor_location' => 'Terminal 2'],
            ['tenant_code' => 'FB-22-5', 'name' => 'OLDTOWN White Coffee', 'floor_location' => 'Terminal 2'],
            ['tenant_code' => 'FB-22-6', 'name' => 'Gloria Jean\'s Coffee', 'floor_location' => 'Terminal 2'],
        ];

        foreach ($tenants as $tenant) {
            DB::table('tenants')->insert(array_merge($tenant, [
                'opening_time' => null,
                'closing_time' => null,
                'phone' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
