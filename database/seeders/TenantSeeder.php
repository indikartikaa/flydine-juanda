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
            ['tenant_code' => 'EP-01-01', 'name' => 'Beard papa\'s', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'EP-02-02', 'name' => 'Doughlab', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'EP-02-04', 'name' => 'Beard papa\'s', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'EP-02-05', 'name' => 'Shuyi Grass Jelly Tea', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-01', 'name' => 'A&W', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-02', 'name' => 'Bakmi Gocit 1', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-03', 'name' => 'Pastry Station', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-03A', 'name' => 'La. Café 1', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-04', 'name' => 'Roti O 1', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-05', 'name' => 'Roti Boy', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-08', 'name' => 'Solaria', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-09A', 'name' => 'Mm juice', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-10', 'name' => 'A&W', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-11', 'name' => 'Java Café', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-12B', 'name' => 'Wingman', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-13', 'name' => 'Sari Bundo', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-14', 'name' => 'SKY BEANS', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-14A', 'name' => 'Let petit jemma', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-15', 'name' => 'Papan Dahar', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-16', 'name' => 'Coffee O', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-17', 'name' => 'Kedai MJ', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-18', 'name' => 'Roti O 3', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-19', 'name' => 'Expat', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-01-20', 'name' => 'A&W', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-02-01', 'name' => 'Warkop Sedati', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-02-02', 'name' => 'Bakso Pak Djo 1', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-02-04', 'name' => 'Bangi Café 1', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-02-05', 'name' => 'Starbucks', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-02-06', 'name' => 'Bakmi Gocit 2', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-02-07', 'name' => 'A&W', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-02-08', 'name' => 'Donut King', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-02-08A', 'name' => 'Subway', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-02-09A', 'name' => 'Bakso Pak Djo 2', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-02-09B', 'name' => 'Roti O 5', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-02-10', 'name' => 'La. Café 2', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-02-11', 'name' => 'Aroma Padang', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-02-12', 'name' => 'Bakery Station', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-02-13', 'name' => 'Bakso Afung', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-02-14', 'name' => 'Gloria Jeans Coffee', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-02-15', 'name' => 'Kulineri Indonesia', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-02-16', 'name' => 'Restaurant Bangkalan 2', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-02-17', 'name' => 'Bakmi Gocit 3', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-02-18', 'name' => 'Bangi Café 2', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-02-20', 'name' => 'Killiney', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'FB-21-1', 'name' => 'Kulineri Sandang Pangan', 'floor_location' => 'Terminal 2'],
            ['tenant_code' => 'FB-21-2', 'name' => 'OLDTOWN WHITE COFFEE', 'floor_location' => 'Terminal 2'],
            ['tenant_code' => 'FB-21-3', 'name' => 'Bakmi Gocit', 'floor_location' => 'Terminal 2'],
            ['tenant_code' => 'FB-21-4', 'name' => 'BEBEK PAK QOMAR & RAWON', 'floor_location' => 'Terminal 2'],
            ['tenant_code' => 'FB-21-7', 'name' => 'Roti O', 'floor_location' => 'Terminal 2'],
            ['tenant_code' => 'FB-22-1', 'name' => 'Killiney', 'floor_location' => 'Terminal 2'],
            ['tenant_code' => 'FB-22-2', 'name' => 'LE PETIT JEMMA', 'floor_location' => 'Terminal 2'],
            ['tenant_code' => 'FB-22-3', 'name' => 'MARUGAME UDON', 'floor_location' => 'Terminal 2'],
            ['tenant_code' => 'FB-22-4', 'name' => 'PADANG MERDEKA', 'floor_location' => 'Terminal 2'],
            ['tenant_code' => 'FB-22-5', 'name' => 'OLDTOWN WHITE COFFEE', 'floor_location' => 'Terminal 2'],
            ['tenant_code' => 'FB-22-6', 'name' => 'Gloria Jeans Coffee', 'floor_location' => 'Terminal 2'],
            ['tenant_code' => 'EP-01-03', 'name' => 'Dunkin Donuts', 'floor_location' => 'Terminal 1'],
            ['tenant_code' => 'EP-02-13', 'name' => 'Famicafe', 'floor_location' => 'Terminal 1'],        ];

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
