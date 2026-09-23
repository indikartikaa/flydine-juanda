<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categoryMappings = [
            'Cepat Saji' => [
                'A&W',
                'SUBWAY',
                'SHIHLIN',
                'HONEY POK',
            ],
            'Roti & Kue' => [
                'ROTI\'O',
                'ROTI O',
                'ROTI BOY',
                'BEARD PAPA\'S',
                'LE PETIT JEMMA',
                'PASTRY STATION ROTI\'O',
                'DOUGH LAB',
                'PAFU',
                'DONUT KINGS',
                'DUNKIN\' DONUTS',
            ],
            'Minuman' => [
                'EXPAT',
                'SKY BEANS',
                'COFFEE BY ROTI\'O',
                'MM JUICE RESTAURANT',
                'MM JUICE',
                'JAVA CAFFE',
                'JAVA CAFÉ',
                'CAFE GARDEN',
                'LA CAFE',
                'KILLINEY',
                'BANGI CAFE',
                'FAMI CAFE',
                'SHUYI',
                'YOGURT REPUBLIC',
                'KEDAI ES JADOEL',
                'GLORIA JEAN\'S COFFEES',
                'GLORIA JEANS',
                'STARBUCKS',
                'OLD TOWN',
                'OLDTOWN WHITE COFFEE',
            ],
            'Makanan Berat' => [
                'SARI BUNDO',
                'PAK QOMAR',
                'BEBEK PAK QOMAR & RAWON',
                'PAPAN DAHAR',
                'SOLARIA',
                'BAKMI GOCIT',
                'BAKSO PAK DJO',
                'YOSINOYA',
                'SAMBELAN JUARA',
                'AYAM GORENG TRETES SRIRASA',
                'MIE KLUNTUNG & NASI GORENG JAWA PAK MITRO',
                'PEMPEK FARIMA',
                'BEBEK GORENG HARISA',
                'SELERA SURABAYA',
                'NASI PECEL YU GEMBROT MADIUN',
                'BU TOPO RUJAK CINGUR',
                'SOTO MADURA WAWAN',
                'A FUNG',
                'AROMA PADANG',
                'KULINER SANDANG PANGAN',
                'KULINERI SANDANG PANGAN',
                'MARUGAME UDON',
                'PADANG MERDEKA',
                'WINGMAN',
            ],
        ];

        foreach ($categoryMappings as $category => $tenantNames) {
            foreach ($tenantNames as $name) {
                DB::table('tenants')
                    ->where('name', 'like', $name)
                    ->orWhere('name', 'like', '%' . $name . '%')
                    ->update(['category' => $category]);
            }
        }

        // Pastikan tidak ada yang tersisa kosong: defaultkan ke Makanan Berat jika ada tenant baru
        DB::table('tenants')
            ->whereNull('category')
            ->orWhere('category', '')
            ->update(['category' => 'Makanan Berat']);
    }
}
