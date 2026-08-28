<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Data MVP: Memasukkan tepat 5 menu spesifik berdasarkan brand/tenant_code,
 * sesuai dengan referensi Dokumen MVP FlyDine Juanda (Terminal 1 & 2).
 */
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            // TERMINAL 1
            'FB-01-01' => [ // A&W
                ['name' => 'Ayam Goreng Original (1pc)', 'price' => 28000],
                ['name' => 'Ayam Goreng Crispy (1pc)', 'price' => 30000],
                ['name' => 'Coney Dog', 'price' => 25000],
                ['name' => 'Root Beer Float', 'price' => 22000],
                ['name' => 'Paket Ayam + Nasi + Root Beer', 'price' => 45000],
            ],
            'FB-02-05' => [ // Starbucks
                ['name' => 'Caffe Latte (Hot/Ice)', 'price' => 45000],
                ['name' => 'Cappuccino', 'price' => 45000],
                ['name' => 'Caramel Macchiato', 'price' => 52000],
                ['name' => 'Croissant Butter', 'price' => 32000],
                ['name' => 'Chocolate Chip Cookie', 'price' => 28000],
            ],
            'EP-01-03' => [ // Dunkin' Donuts
                ['name' => 'Donut Glazed', 'price' => 12000],
                ['name' => 'Donut Choco', 'price' => 13000],
                ['name' => 'Munchkins (isi 10)', 'price' => 30000],
                ['name' => 'Kopi Dunkin (Hot/Ice)', 'price' => 25000],
                ['name' => 'Croissant Sandwich', 'price' => 28000],
            ],
            'FB-02-08A' => [ // Subway
                ['name' => 'Subway Club 6"', 'price' => 42000],
                ['name' => 'Chicken Teriyaki 6"', 'price' => 40000],
                ['name' => 'Tuna Sandwich 6"', 'price' => 38000],
                ['name' => 'Cookies', 'price' => 15000],
                ['name' => 'Paket Combo Sandwich + Minum', 'price' => 55000],
            ],
            'EP-01-01' => [ // Beard Papa's
                ['name' => 'Cream Puff Original', 'price' => 22000],
                ['name' => 'Cream Puff Choco', 'price' => 24000],
                ['name' => 'Cream Puff Matcha', 'price' => 25000],
                ['name' => 'Eclair Vanilla', 'price' => 23000],
                ['name' => 'Paket 4 Cream Puff Mix', 'price' => 80000],
            ],
            'FB-01-04' => [ // Roti O
                ['name' => 'Roti O Original', 'price' => 18000],
                ['name' => 'Roti O Choco Cheese', 'price' => 20000],
                ['name' => 'Roti O Pandan', 'price' => 19000],
                ['name' => 'Roti Sobek Keju', 'price' => 21000],
                ['name' => 'Paket 3 Roti Mix', 'price' => 50000],
            ],
            'FB-01-05' => [ // Roti Boy
                ['name' => 'Roti Boy Original', 'price' => 16000],
                ['name' => 'Roti Boy Choco Chips', 'price' => 18000],
                ['name' => 'Roti Boy Cheese Chips', 'price' => 19000],
                ['name' => 'Bun Sausage', 'price' => 20000],
                ['name' => 'Paket 2 Roti Boy', 'price' => 30000],
            ],
            'FB-01-08' => [ // Solaria
                ['name' => 'Nasi Goreng Solaria', 'price' => 32000],
                ['name' => 'Mie Goreng Solaria', 'price' => 30000],
                ['name' => 'Ayam Penyet', 'price' => 35000],
                ['name' => 'Es Teh Manis', 'price' => 10000],
                ['name' => 'Paket Nasi + Ayam + Es Teh', 'price' => 42000],
            ],
            'FB-01-19' => [ // Killiney (T1)
                ['name' => 'Kaya Toast Set', 'price' => 28000],
                ['name' => 'Soft Boiled Egg (2 butir)', 'price' => 12000],
                ['name' => 'Kopi O', 'price' => 18000],
                ['name' => 'Teh Tarik', 'price' => 20000],
                ['name' => 'Paket Kaya Toast + Kopi', 'price' => 42000],
            ],
            'FB-01-02' => [ // Bakmi Gocit (T1)
                ['name' => 'Bakmi Ayam Original', 'price' => 28000],
                ['name' => 'Bakmi Ayam Jamur', 'price' => 30000],
                ['name' => 'Pangsit Goreng', 'price' => 15000],
                ['name' => 'Es Jeruk', 'price' => 12000],
                ['name' => 'Paket Bakmi + Pangsit', 'price' => 40000],
            ],
            'FB-02-02' => [ // Bakso Pak Djo
                ['name' => 'Bakso Urat', 'price' => 25000],
                ['name' => 'Bakso Halus', 'price' => 23000],
                ['name' => 'Bakso Campur (Urat+Halus+Tahu)', 'price' => 28000],
                ['name' => 'Es Teh Manis', 'price' => 10000],
                ['name' => 'Paket Bakso Komplit', 'price' => 33000],
            ],
            'FB-01-13' => [ // Sari Bundo
                ['name' => 'Nasi Rendang', 'price' => 38000],
                ['name' => 'Nasi Ayam Pop', 'price' => 32000],
                ['name' => 'Nasi Gulai Tunjang', 'price' => 35000],
                ['name' => 'Es Teh Manis', 'price' => 10000],
                ['name' => 'Paket Nasi Rendang + Es Teh', 'price' => 45000],
            ],
            'FB-01-11' => [ // Java Café
                ['name' => 'Kopi Tubruk', 'price' => 18000],
                ['name' => 'Es Kopi Susu Gula Aren', 'price' => 25000],
                ['name' => 'Nasi Goreng Kampung', 'price' => 30000],
                ['name' => 'Pisang Goreng', 'price' => 15000],
                ['name' => 'Paket Kopi + Pisang Goreng', 'price' => 35000],
            ],
            'FB-01-09A' => [ // Mm Juice
                ['name' => 'Jus Alpukat', 'price' => 20000],
                ['name' => 'Jus Mangga', 'price' => 18000],
                ['name' => 'Jus Jeruk', 'price' => 16000],
                ['name' => 'Smoothie Mixed Berry', 'price' => 25000],
                ['name' => 'Paket 2 Jus Pilihan', 'price' => 35000],
            ],
            'FB-01-12B' => [ // Wingman
                ['name' => 'Wings Original (5pc)', 'price' => 35000],
                ['name' => 'Wings Spicy BBQ (5pc)', 'price' => 38000],
                ['name' => 'Wings Salted Egg (5pc)', 'price' => 40000],
                ['name' => 'Kentang Goreng', 'price' => 18000],
                ['name' => 'Paket Wings + Kentang', 'price' => 48000],
            ],

            // TERMINAL 2
            'FB-21-1' => [ // Kulineri Sandang Pangan
                ['name' => 'Nasi Campur', 'price' => 30000],
                ['name' => 'Soto Ayam', 'price' => 28000],
                ['name' => 'Es Teh Manis', 'price' => 10000],
                ['name' => 'Kerupuk', 'price' => 5000],
                ['name' => 'Paket Nasi Campur + Es Teh', 'price' => 38000],
            ],
            'FB-21-2' => [ // OLDTOWN White Coffee (T2 1)
                ['name' => 'White Coffee (Hot/Ice)', 'price' => 28000],
                ['name' => 'Kaya Toast', 'price' => 22000],
                ['name' => 'Half Boiled Egg', 'price' => 12000],
                ['name' => 'Mee Goreng', 'price' => 30000],
                ['name' => 'Paket White Coffee + Kaya Toast', 'price' => 42000],
            ],
            'FB-21-3' => [ // Bakmi Gocit (T2)
                ['name' => 'Bakmi Ayam Original', 'price' => 28000],
                ['name' => 'Bakmi Yamin', 'price' => 29000],
                ['name' => 'Pangsit Rebus', 'price' => 15000],
                ['name' => 'Es Jeruk', 'price' => 12000],
                ['name' => 'Paket Bakmi + Es Jeruk', 'price' => 38000],
            ],
            'FB-21-4' => [ // Bebek Pak Qomar & Rawon
                ['name' => 'Nasi Bebek Goreng', 'price' => 35000],
                ['name' => 'Nasi Rawon Daging', 'price' => 33000],
                ['name' => 'Sambal Korek', 'price' => 5000],
                ['name' => 'Es Teh Manis', 'price' => 10000],
                ['name' => 'Paket Bebek/Rawon + Es Teh', 'price' => 42000],
            ],
            'FB-21-7' => [ // Roti O (T2)
                ['name' => 'Roti O Original', 'price' => 18000],
                ['name' => 'Roti O Choco Cheese', 'price' => 20000],
                ['name' => 'Roti Sobek Keju', 'price' => 21000],
                ['name' => 'Donat Mini (isi 5)', 'price' => 17000],
                ['name' => 'Paket 3 Roti Mix', 'price' => 50000],
            ],
            'FB-22-1' => [ // Killiney (T2)
                ['name' => 'Kaya Toast Set', 'price' => 28000],
                ['name' => 'Soft Boiled Egg (2 butir)', 'price' => 12000],
                ['name' => 'Kopi O', 'price' => 18000],
                ['name' => 'Teh Tarik', 'price' => 20000],
                ['name' => 'Paket Kaya Toast + Kopi', 'price' => 42000],
            ],
            'FB-22-2' => [ // Le Petit Jemma
                ['name' => 'Croissant Butter', 'price' => 25000],
                ['name' => 'Pain au Chocolat', 'price' => 27000],
                ['name' => 'Eclair', 'price' => 26000],
                ['name' => 'Macaron (3pc)', 'price' => 35000],
                ['name' => 'Paket Pastry Mix', 'price' => 65000],
            ],
            'FB-22-3' => [ // Marugame Udon
                ['name' => 'Kake Udon', 'price' => 32000],
                ['name' => 'Curry Udon', 'price' => 38000],
                ['name' => 'Chicken Karaage', 'price' => 30000],
                ['name' => 'Ocha (Green Tea)', 'price' => 12000],
                ['name' => 'Paket Udon + Karaage', 'price' => 55000],
            ],
            'FB-22-4' => [ // Padang Merdeka
                ['name' => 'Nasi Rendang', 'price' => 38000],
                ['name' => 'Nasi Ayam Pop', 'price' => 32000],
                ['name' => 'Nasi Gulai Ikan', 'price' => 34000],
                ['name' => 'Es Teh Manis', 'price' => 10000],
                ['name' => 'Paket Nasi Rendang + Es Teh', 'price' => 45000],
            ],
            'FB-22-5' => [ // OLDTOWN White Coffee (T2 2)
                ['name' => 'White Coffee (Hot/Ice)', 'price' => 28000],
                ['name' => 'Kaya Toast', 'price' => 22000],
                ['name' => 'Half Boiled Egg', 'price' => 12000],
                ['name' => 'Nasi Lemak', 'price' => 32000],
                ['name' => 'Paket White Coffee + Nasi Lemak', 'price' => 45000],
            ],
            'FB-22-6' => [ // Gloria Jean's Coffee
                ['name' => 'Cappuccino', 'price' => 42000],
                ['name' => 'Caramel Latte', 'price' => 48000],
                ['name' => 'Chocolate Muffin', 'price' => 28000],
                ['name' => 'Butter Croissant', 'price' => 30000],
                ['name' => 'Paket Cappuccino + Muffin', 'price' => 60000],
            ],
        ];

        // Dapatkan semua tenant yang sudah disemai oleh TenantSeeder
        $tenants = DB::table('tenants')->get();

        foreach ($tenants as $tenant) {
            // Cek apakah tenant_code ada di mapping menus kita
            if (isset($menus[$tenant->tenant_code])) {
                $tenantMenus = $menus[$tenant->tenant_code];
                
                foreach ($tenantMenus as $menuData) {
                    DB::table('products')->insert([
                        'tenant_id' => $tenant->id,
                        'name' => $menuData['name'],
                        'price' => $menuData['price'],
                        'is_available' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
