<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Terminal2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     * Menginput 16 Tenant dan 239 Menu Resmi Terminal 2 Bandara Juanda.
     */
    public function run(): void
    {
        $tenantsData = [
            // ==========================================
            // LANTAI 1 - KEBERANGKATAN INTERNASIONAL
            // ==========================================
            [
                'tenant_code'    => 'FB-T2-01-01',
                'name'           => 'BAKMI GOCIT',
                'terminal'       => 'T2',
                'zone'           => 'Airside',
                'floor_location' => 'Lantai 1 - Keberangkatan Internasional',
                'category'       => 'Indonesian & Noodles',
                'menus'          => [
                    ['name' => 'Bakmi Rempah Spesial', 'price' => 54000],
                    ['name' => 'Bakmi Pangsit', 'price' => 54000],
                    ['name' => 'Bakmi Ayam Jamur', 'price' => 49000],
                    ['name' => 'Bakmi Bakso Sapi', 'price' => 49000],
                    ['name' => 'Bakmi Ayam', 'price' => 46000],
                    ['name' => 'Bakmi Sapi Lada Hitam', 'price' => 60000],
                    ['name' => 'Bakmi Sukiakuw', 'price' => 58000],
                    ['name' => 'Bakmi Hijau Ayam Sukiakuw', 'price' => 58000],
                    ['name' => 'Bakmi Ayam Charsiu', 'price' => 57000],
                    ['name' => 'Tahu Bakso + Bakso', 'price' => 59000],
                    ['name' => 'Bakmi Lebar Spesial', 'price' => 54000],
                    ['name' => 'Nasi Goreng Ayam Spesial', 'price' => 58000],
                    ['name' => 'Bakmi Goreng Spesial', 'price' => 58000],
                    ['name' => 'Ifumie Ayam', 'price' => 64000],
                    ['name' => 'Nasi Sapi Lada Hitam', 'price' => 65000],
                ],
            ],

            // ==========================================
            // LANTAI 1 - KEBERANGKATAN UMRAH
            // ==========================================
            [
                'tenant_code'    => 'FB-T2-01-02',
                'name'           => 'OLD TOWN',
                'terminal'       => 'T2',
                'zone'           => 'Airside',
                'floor_location' => 'Lantai 1 - Keberangkatan Umrah',
                'category'       => 'Coffee & Asian Food',
                'menus'          => [
                    ['name' => 'OLDTOWN White Coffee', 'price' => 51000],
                    ['name' => 'OLDTOWN White Coffee Mocha', 'price' => 55000],
                    ['name' => 'OLDTOWN White Coffee Hazelnut', 'price' => 53000],
                    ['name' => 'OLDTOWN White Coffee Gao', 'price' => 53000],
                    ['name' => 'Cappuccino', 'price' => 53000],
                    ['name' => 'Coffee Latte', 'price' => 53000],
                    ['name' => 'Teh Tarik', 'price' => 53000],
                    ['name' => 'Fresh Lemon Tea', 'price' => 52000],
                    ['name' => 'Nasi Lemak Rendang Chicken', 'price' => 85000],
                    ['name' => 'Nasi Lemak With Fried Chicken', 'price' => 85000],
                    ['name' => 'Nasi Lemak Whole Leg', 'price' => 98000],
                    ['name' => 'Kaya & Butter Toast (Double)', 'price' => 50000],
                    ['name' => 'Peanut Butter Toast (Double)', 'price' => 50000],
                    ['name' => 'Tuna Toast', 'price' => 52000],
                    ['name' => 'French Fries Basket', 'price' => 46000],
                ],
            ],
            [
                'tenant_code'    => 'FB-T2-01-03',
                'name'           => 'SHUYI',
                'terminal'       => 'T2',
                'zone'           => 'Airside',
                'floor_location' => 'Lantai 1 - Keberangkatan Umrah',
                'category'       => 'Beverages & Grass Jelly',
                'menus'          => [
                    ['name' => 'Signature Grass Jelly (L)', 'price' => 34000],
                    ['name' => 'Signature Grass Jelly (M)', 'price' => 28000],
                    ['name' => 'Milk Grass Jelly (L)', 'price' => 37000],
                    ['name' => 'Milk Grass Jelly (M)', 'price' => 32000],
                    ['name' => 'Cheese Lava Boba Grass Jelly', 'price' => 25000],
                    ['name' => 'Brown Sugar Boba Milk', 'price' => 30000],
                    ['name' => 'Matcha Boba Milk', 'price' => 29000],
                    ['name' => 'Brown Sugar Boba Chocolate', 'price' => 29000],
                    ['name' => 'Grass Jelly Coconut Milk Tea', 'price' => 28000],
                    ['name' => 'Grass Jelly Cool Cincau Tea (L)', 'price' => 23000],
                    ['name' => 'Grass Jelly Cool Cincau Tea (M)', 'price' => 20000],
                    ['name' => 'Peach Oolong Milk Tea', 'price' => 32000],
                    ['name' => 'Coconut Peach Milk Tea', 'price' => 32000],
                    ['name' => 'Roast Oolong Milk Tea', 'price' => 28000],
                ],
            ],
            [
                'tenant_code'    => 'FB-T2-01-04',
                'name'           => 'KULINER SANDANG PANGAN',
                'terminal'       => 'T2',
                'zone'           => 'Airside',
                'floor_location' => 'Lantai 1 - Keberangkatan Umrah',
                'category'       => 'Indonesian Traditional',
                'menus'          => [
                    ['name' => 'Bakso Campur Pak Djo', 'price' => 47000],
                    ['name' => 'Nasi Goreng Ayam', 'price' => 61000],
                    ['name' => 'Sambelan Ayam Goreng', 'price' => 71000],
                    ['name' => 'Sambelan Empal Goreng', 'price' => 77000],
                    ['name' => 'Sambelan Ayam Bakar', 'price' => 71000],
                    ['name' => 'Sambelan Makmur Makan Murah', 'price' => 46000],
                    ['name' => 'Soto Campur Istimewa', 'price' => 71000],
                    ['name' => 'Soto Daging', 'price' => 63000],
                    ['name' => 'Soto Ayam', 'price' => 67000],
                    ['name' => 'Rawon Sapi', 'price' => 69000],
                    ['name' => 'Sop Buntut', 'price' => 97000],
                    ['name' => 'Lontong Kupang', 'price' => 49000],
                    ['name' => 'Siomay Campur', 'price' => 64000],
                    ['name' => 'Siomay Lengkap', 'price' => 79000],
                    ['name' => 'Pisang Coklat', 'price' => 21000],
                ],
            ],

            // ==========================================
            // LANTAI 1 - LOBBY (LANDSIDE)
            // ==========================================
            [
                'tenant_code'    => 'FB-T2-01-05',
                'name'           => 'BAKMI GOCIT',
                'terminal'       => 'T2',
                'zone'           => 'Landside',
                'floor_location' => 'Lantai 1 - Lobby',
                'category'       => 'Indonesian & Noodles',
                'menus'          => [
                    ['name' => 'Bakmi Rempah Spesial', 'price' => 54000],
                    ['name' => 'Bakmi Pangsit', 'price' => 54000],
                    ['name' => 'Bakmi Ayam Jamur', 'price' => 49000],
                    ['name' => 'Bakmi Bakso Sapi', 'price' => 49000],
                    ['name' => 'Bakmi Ayam', 'price' => 46000],
                    ['name' => 'Bakmi Sapi Lada Hitam', 'price' => 60000],
                    ['name' => 'Bakmi Sukiakuw', 'price' => 58000],
                    ['name' => 'Bakmi Hijau Ayam Sukiakuw', 'price' => 58000],
                    ['name' => 'Bakmi Ayam Charsiu', 'price' => 57000],
                    ['name' => 'Tahu Bakso + Bakso', 'price' => 59000],
                    ['name' => 'Bakmi Lebar Spesial', 'price' => 54000],
                    ['name' => 'Nasi Goreng Ayam Spesial', 'price' => 58000],
                    ['name' => 'Bakmi Goreng Spesial', 'price' => 58000],
                    ['name' => 'Ifumie Ayam', 'price' => 64000],
                    ['name' => 'Nasi Sapi Lada Hitam', 'price' => 65000],
                ],
            ],
            [
                'tenant_code'    => 'FB-T2-01-06',
                'name'           => 'PAFU',
                'terminal'       => 'T2',
                'zone'           => 'Landside',
                'floor_location' => 'Lantai 1 - Lobby',
                'category'       => 'Pastry & Bakery',
                'menus'          => [
                    ['name' => 'Iced Earl Grey Matcha', 'price' => 55000],
                    ['name' => 'Iced Pure Matcha Latte', 'price' => 50000],
                    ['name' => 'Iced Coconut Matcha', 'price' => 50000],
                    ['name' => 'Iced Taro Latte', 'price' => 45000],
                    ['name' => 'Iced Banana Taro Milk', 'price' => 45000],
                    ['name' => 'Apple Puff', 'price' => 35000],
                    ['name' => 'Choco Lovers Puff', 'price' => 35000],
                    ['name' => 'Strawberry Puff', 'price' => 35000],
                    ['name' => 'Blueberry Cheese Puff', 'price' => 35000],
                    ['name' => 'Banana Caramel Puff', 'price' => 35000],
                    ['name' => 'Mochi Pistachio Puff', 'price' => 35000],
                    ['name' => 'Smoked Beef & Cheese Puff', 'price' => 35000],
                    ['name' => 'Truffle Mushroom Puff', 'price' => 35000],
                    ['name' => 'Original Melon Pan', 'price' => 24000],
                    ['name' => 'Chocolate Salt Bread', 'price' => 33000],
                ],
            ],
            [
                'tenant_code'    => 'FB-T2-01-07',
                'name'           => 'OLD TOWN',
                'terminal'       => 'T2',
                'zone'           => 'Landside',
                'floor_location' => 'Lantai 1 - Lobby',
                'category'       => 'Coffee & Asian Food',
                'menus'          => [
                    ['name' => 'OLDTOWN White Coffee', 'price' => 51000],
                    ['name' => 'OLDTOWN White Coffee Mocha', 'price' => 55000],
                    ['name' => 'OLDTOWN White Coffee Hazelnut', 'price' => 53000],
                    ['name' => 'OLDTOWN White Coffee Gao', 'price' => 53000],
                    ['name' => 'Cappuccino', 'price' => 53000],
                    ['name' => 'Coffee Latte', 'price' => 53000],
                    ['name' => 'Teh Tarik', 'price' => 53000],
                    ['name' => 'Fresh Lemon Tea', 'price' => 52000],
                    ['name' => 'Nasi Lemak Rendang Chicken', 'price' => 85000],
                    ['name' => 'Nasi Lemak With Fried Chicken', 'price' => 85000],
                    ['name' => 'Nasi Lemak Whole Leg', 'price' => 98000],
                    ['name' => 'Kaya & Butter Toast (Double)', 'price' => 50000],
                    ['name' => 'Peanut Butter Toast (Double)', 'price' => 50000],
                    ['name' => 'Tuna Toast', 'price' => 52000],
                    ['name' => 'French Fries Basket', 'price' => 46000],
                ],
            ],
            [
                'tenant_code'    => 'FB-T2-01-08',
                'name'           => 'KULINER SANDANG PANGAN',
                'terminal'       => 'T2',
                'zone'           => 'Landside',
                'floor_location' => 'Lantai 1 - Lobby',
                'category'       => 'Indonesian Traditional',
                'menus'          => [
                    ['name' => 'Bakso Campur Pak Djo', 'price' => 47000],
                    ['name' => 'Nasi Goreng Ayam', 'price' => 61000],
                    ['name' => 'Sambelan Ayam Goreng', 'price' => 71000],
                    ['name' => 'Sambelan Empal Goreng', 'price' => 77000],
                    ['name' => 'Sambelan Ayam Bakar', 'price' => 71000],
                    ['name' => 'Sambelan Makmur Makan Murah', 'price' => 46000],
                    ['name' => 'Soto Campur Istimewa', 'price' => 71000],
                    ['name' => 'Soto Daging', 'price' => 63000],
                    ['name' => 'Soto Ayam', 'price' => 67000],
                    ['name' => 'Rawon Sapi', 'price' => 69000],
                    ['name' => 'Sop Buntut', 'price' => 97000],
                    ['name' => 'Lontong Kupang', 'price' => 49000],
                    ['name' => 'Siomay Campur', 'price' => 64000],
                    ['name' => 'Siomay Lengkap', 'price' => 79000],
                    ['name' => 'Pisang Coklat', 'price' => 21000],
                ],
            ],
            [
                'tenant_code'    => 'FB-T2-01-09',
                'name'           => 'PAK QOMAR',
                'terminal'       => 'T2',
                'zone'           => 'Landside',
                'floor_location' => 'Lantai 1 - Lobby',
                'category'       => 'Indonesian Duck & Rawon',
                'menus'          => [
                    ['name' => 'Paket Dada Bebek', 'price' => 65000],
                    ['name' => 'Paket Paha Bebek', 'price' => 65000],
                    ['name' => 'Paket Dada Ayam', 'price' => 57000],
                    ['name' => 'Paket Paha Ayam', 'price' => 57000],
                    ['name' => 'Bebek Goreng', 'price' => 57000],
                    ['name' => 'Bebek Sambal Korek', 'price' => 57000],
                    ['name' => 'Ayam Goreng', 'price' => 47000],
                    ['name' => 'Ayam Sambal Korek', 'price' => 47000],
                    ['name' => 'Nasi Rawon Daging', 'price' => 83000],
                    ['name' => 'Nasi Rawon Iga', 'price' => 91000],
                    ['name' => 'Nasi Soto Daging', 'price' => 83000],
                    ['name' => 'Telur Asin', 'price' => 17000],
                    ['name' => 'Tahu Goreng', 'price' => 13000],
                    ['name' => 'Es Cendol', 'price' => 31000],
                    ['name' => 'Wedang Ronde', 'price' => 35000],
                ],
            ],
            [
                'tenant_code'    => 'FB-T2-01-10',
                'name'           => "ROTI'O",
                'terminal'       => 'T2',
                'zone'           => 'Landside',
                'floor_location' => 'Lantai 1 - Lobby',
                'category'       => 'Bakery & Coffee',
                'menus'          => [
                    ['name' => "Roti'O Original Coffee Bun", 'price' => 18000],
                    ['name' => "Roti'O Cheese Cream", 'price' => 22000],
                    ['name' => "Roti'O Chocolate", 'price' => 22000],
                    ['name' => "Roti'O Banana Choco Cheese", 'price' => 25000],
                    ['name' => "Roti'O Crombo'O Chocolate", 'price' => 25000],
                    ['name' => "Roti'O Almond Turnover", 'price' => 25000],
                    ['name' => "Roti'O Tuna Pastry", 'price' => 25000],
                    ['name' => "Roti'O Beef Pastry", 'price' => 25000],
                    ['name' => "Roti'O Chicken Pastry", 'price' => 25000],
                    ['name' => "Roti'O Cheese Feuillette", 'price' => 25000],
                    ['name' => 'Butter Croissant', 'price' => 20000],
                    ['name' => 'Sausage Croissant', 'price' => 25000],
                    ['name' => "Kopi'O Aren", 'price' => 18000],
                    ['name' => "Kopi'O Latte", 'price' => 22000],
                    ['name' => 'Chocolate Drink', 'price' => 25000],
                ],
            ],

            // ==========================================
            // LANTAI 2 - GATE 1 s/d GATE 5 (AIRSIDE)
            // ==========================================
            [
                'tenant_code'    => 'FB-T2-02-01',
                'name'           => 'KILLINEY',
                'terminal'       => 'T2',
                'zone'           => 'Airside',
                'floor_location' => 'Lantai 2 - Gate 1',
                'category'       => 'Kopitiam & Asian Food',
                'menus'          => [
                    ['name' => 'Nasi Lemak With Chicken Katsu', 'price' => 80000],
                    ['name' => 'Rendang Ayam Rice', 'price' => 65000],
                    ['name' => 'Hainan Chicken Rice', 'price' => 65000],
                    ['name' => 'Chicken Leg Soy With Rice', 'price' => 55000],
                    ['name' => 'Beef Blackpepper With Rice', 'price' => 58000],
                    ['name' => 'Fried Rice', 'price' => 60000],
                    ['name' => 'Mee Goreng', 'price' => 60000],
                    ['name' => 'Kwetiaw Goreng', 'price' => 60000],
                    ['name' => 'Curry Chicken With Rice', 'price' => 70000],
                    ['name' => 'Asam Fish', 'price' => 73000],
                    ['name' => 'Mee Laksa', 'price' => 75000],
                    ['name' => 'Butter Kaya Toast', 'price' => 41000],
                    ['name' => 'Kaya Toast', 'price' => 38000],
                    ['name' => 'Coffee C', 'price' => 45000],
                    ['name' => 'Milo Dinosaur', 'price' => 48000],
                ],
            ],
            [
                'tenant_code'    => 'FB-T2-02-02',
                'name'           => 'LE PETIT JEMMA',
                'terminal'       => 'T2',
                'zone'           => 'Airside',
                'floor_location' => 'Lantai 2 - Gate 3',
                'category'       => 'French Bakery & Coffee',
                'menus'          => [
                    ['name' => 'Pain Au Chocolat', 'price' => 50000],
                    ['name' => 'Triple Cheese Puff', 'price' => 65000],
                    ['name' => 'Almond Croissant', 'price' => 55000],
                    ['name' => 'Chicken BBQ Sandwich', 'price' => 75000],
                    ['name' => 'Ham & Cheese Sandwich', 'price' => 75000],
                    ['name' => 'Raisin Danish', 'price' => 55000],
                    ['name' => 'Choux Bread', 'price' => 55000],
                    ['name' => 'Coffee Bread', 'price' => 55000],
                    ['name' => 'Caffe Latte', 'price' => 45000],
                    ['name' => 'Cappuccino', 'price' => 45000],
                    ['name' => 'Hot Chocolate', 'price' => 45000],
                    ['name' => 'Iced Latte', 'price' => 55000],
                    ['name' => 'Iced Chocolate', 'price' => 55000],
                    ['name' => 'Matcha Latte', 'price' => 60000],
                    ['name' => 'Coffee Latte Signature', 'price' => 65000],
                ],
            ],
            [
                'tenant_code'    => 'FB-T2-02-03',
                'name'           => 'MARUGAME UDON',
                'terminal'       => 'T2',
                'zone'           => 'Airside',
                'floor_location' => 'Lantai 2 - Gate 3',
                'category'       => 'Japanese Udon & Rice',
                'menus'          => [
                    ['name' => 'Niku Udon', 'price' => 106000],
                    ['name' => 'Beef Curry Udon', 'price' => 106000],
                    ['name' => 'Beef Abura Udon', 'price' => 106000],
                    ['name' => 'Beef Carbonara Udon', 'price' => 100000],
                    ['name' => 'Tori Baitan Udon', 'price' => 98000],
                    ['name' => 'Teriyaki Abura Udon', 'price' => 98000],
                    ['name' => 'Chicken Katsu Curry Udon', 'price' => 101000],
                    ['name' => 'Spicy Tori Baitan Udon', 'price' => 98000],
                    ['name' => 'Kake Udon', 'price' => 71000],
                    ['name' => 'Sukiyaki Beef Rice', 'price' => 106000],
                    ['name' => 'Beef Curry Rice', 'price' => 106000],
                    ['name' => 'Chicken Katsu Curry Rice', 'price' => 99000],
                    ['name' => 'Tendon Rice', 'price' => 99000],
                    ['name' => 'Teriyaki Chicken Rice', 'price' => 99000],
                    ['name' => 'Spicy Tori Rice', 'price' => 99000],
                ],
            ],
            [
                'tenant_code'    => 'FB-T2-02-04',
                'name'           => 'PADANG MERDEKA',
                'terminal'       => 'T2',
                'zone'           => 'Airside',
                'floor_location' => 'Lantai 2 - Gate 3',
                'category'       => 'Masakan Padang',
                'menus'          => [
                    ['name' => 'Rendang Sapi Merdeka', 'price' => 50000],
                    ['name' => 'Dendeng Balado', 'price' => 46000],
                    ['name' => 'Dendeng Lambok', 'price' => 46000],
                    ['name' => 'Gulai Cincang Sapi', 'price' => 46000],
                    ['name' => 'Gulai Otak', 'price' => 46000],
                    ['name' => 'Ayam Bakar', 'price' => 41000],
                    ['name' => 'Ayam Balado', 'price' => 41000],
                    ['name' => 'Ayam Cabe Ijo', 'price' => 41000],
                    ['name' => 'Ayam Pop', 'price' => 41000],
                    ['name' => 'Ayam Rendang', 'price' => 41000],
                    ['name' => 'Cumi Asin Balado', 'price' => 43000],
                    ['name' => 'Ikan Kakap Balado', 'price' => 43000],
                    ['name' => 'Nasi Rames', 'price' => 30000],
                    ['name' => 'Telur Dadar', 'price' => 20000],
                    ['name' => 'Sambal Kentang Ati', 'price' => 25000],
                ],
            ],
            [
                'tenant_code'    => 'FB-T2-02-05',
                'name'           => 'OLD TOWN',
                'terminal'       => 'T2',
                'zone'           => 'Airside',
                'floor_location' => 'Lantai 2 - Gate 4',
                'category'       => 'Coffee & Asian Food',
                'menus'          => [
                    ['name' => 'OLDTOWN White Coffee', 'price' => 51000],
                    ['name' => 'OLDTOWN White Coffee Mocha', 'price' => 55000],
                    ['name' => 'OLDTOWN White Coffee Hazelnut', 'price' => 53000],
                    ['name' => 'OLDTOWN White Coffee Gao', 'price' => 53000],
                    ['name' => 'Cappuccino', 'price' => 53000],
                    ['name' => 'Coffee Latte', 'price' => 53000],
                    ['name' => 'Teh Tarik', 'price' => 53000],
                    ['name' => 'Fresh Lemon Tea', 'price' => 52000],
                    ['name' => 'Nasi Lemak Rendang Chicken', 'price' => 85000],
                    ['name' => 'Nasi Lemak With Fried Chicken', 'price' => 85000],
                    ['name' => 'Nasi Lemak Whole Leg', 'price' => 98000],
                    ['name' => 'Kaya & Butter Toast (Double)', 'price' => 50000],
                    ['name' => 'Peanut Butter Toast (Double)', 'price' => 50000],
                    ['name' => 'Tuna Toast', 'price' => 52000],
                    ['name' => 'French Fries Basket', 'price' => 46000],
                ],
            ],
            [
                'tenant_code'    => 'FB-T2-02-06',
                'name'           => 'GLORIA JEANS',
                'terminal'       => 'T2',
                'zone'           => 'Airside',
                'floor_location' => 'Lantai 2 - Gate 5',
                'category'       => 'Coffee & Western Food',
                'menus'          => [
                    ['name' => 'Caramel Latte', 'price' => 62000],
                    ['name' => 'Caffè Latte', 'price' => 56000],
                    ['name' => 'Cappuccino', 'price' => 56000],
                    ['name' => 'Hot Caramel Latte', 'price' => 73000],
                    ['name' => 'Hot Very Vanilla Latte', 'price' => 79000],
                    ['name' => 'Hot Mocha Caramel Latte', 'price' => 79000],
                    ['name' => 'Very Vanilla Chiller', 'price' => 69000],
                    ['name' => 'Crème Brûlée Chiller', 'price' => 79000],
                    ['name' => 'Cookies \'N Cream Chiller', 'price' => 79000],
                    ['name' => 'Iced Mocha', 'price' => 64000],
                    ['name' => 'Iced Matcha Latte', 'price' => 77000],
                    ['name' => 'Cocoa Loco Chiller', 'price' => 70000],
                    ['name' => 'Club Sandwich', 'price' => 79000],
                    ['name' => 'GJC\'s Burger', 'price' => 71000],
                    ['name' => 'Big Breakfast', 'price' => 87000],
                ],
            ],
        ];

        DB::transaction(function () use ($tenantsData) {
            $totalTenants = 0;
            $totalProducts = 0;

            foreach ($tenantsData as $item) {
                // 1. Simpan / Perbarui Tenant
                $tenant = Tenant::updateOrCreate(
                    ['tenant_code' => $item['tenant_code']],
                    [
                        'name'           => $item['name'],
                        'terminal'       => $item['terminal'],
                        'zone'           => $item['zone'],
                        'floor_location' => $item['floor_location'],
                        'category'       => $item['category'],
                        'is_active'      => true,
                    ]
                );
                $totalTenants++;

                // 2. Buat Akun Login Tenant Staff jika belum ada (Fitur 13)
                $email = strtolower(str_replace(['-', ' '], ['_', ''], $item['tenant_code'])) . '@flydine.com';
                User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name'       => 'Staff ' . $item['name'] . ' (' . $item['tenant_code'] . ')',
                        'password'   => Hash::make('juanda123'),
                        'role'       => 'tenant_staff',
                        'tenant_id'  => $tenant->id,
                        'is_active'  => true,
                    ]
                );

                // 3. Simpan Seluruh Katalog Produk Tenant
                // Bersihkan dulu menu lama jika ada untuk tenant ini agar tidak duplikat saat re-seed
                Product::where('tenant_id', $tenant->id)->delete();

                foreach ($item['menus'] as $menu) {
                    Product::create([
                        'tenant_id'        => $tenant->id,
                        'name'             => $menu['name'],
                        'price'            => $menu['price'],
                        'stock'            => 100,
                        'is_available'     => true,
                        'preparation_time' => 15, // Default SLA bandara
                    ]);
                    $totalProducts++;
                }
            }

            $this->command->info("Terminal 2 Seeding Berhasil: {$totalTenants} Tenant dan {$totalProducts} Menu dimasukkan!");
        });
    }
}
