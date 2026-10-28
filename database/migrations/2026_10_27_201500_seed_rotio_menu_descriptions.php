<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $descriptions = [
            'Roti\'O Original Coffee Bun' => [
                'desc' => 'Roti bun hangat berlapis topping krim kopi aromatik yang renyah di luar dengan isian butter gurih meleleh di dalam.',
                'note' => 'Sajikan fresh dari warmer box. Kemas rapat dalam kantong anti minyak untuk penumpang take-away.',
            ],
            'Roti\'O Bun Original' => [
                'desc' => 'Roti bun hangat berlapis topping krim kopi aromatik yang renyah di luar dengan isian butter gurih meleleh di dalam.',
                'note' => 'Sajikan fresh dari warmer box. Kemas rapat dalam kantong anti minyak untuk penumpang take-away.',
            ],
            'Roti O Original' => [
                'desc' => 'Roti bun hangat berlapis topping krim kopi aromatik yang renyah di luar dengan isian butter gurih meleleh di dalam.',
                'note' => 'Sajikan fresh dari warmer box. Kemas rapat dalam kantong anti minyak untuk penumpang take-away.',
            ],
            'Roti\'O Cheese Cream' => [
                'desc' => 'Roti lembut bertabur keju parut gurih berlimpah dengan lelehan krim keju manis legit khas Roti\'O.',
                'note' => 'Pastikan kemasan rapi dan tidak tertindih roti bun hangat.',
            ],
            'Roti O Choco Cheese' => [
                'desc' => 'Roti lembut dengan kombinasi filling cokelat manis pekat dan gurihnya parutan keju cheddar melimpah.',
                'note' => 'Sajikan fresh, pastikan keju tidak menempel pada kemasan.',
            ],
            'Roti\'O Chocolate' => [
                'desc' => 'Roti lembut dengan lelehan cokelat premium pekat manis gurih yang lumer di setiap gigitan.',
                'note' => 'Hangatkan 30 detik jika penumpang memesan hangat.',
            ],
            'Roti\'O Banana Choco Cheese' => [
                'desc' => 'Kombinasi pisang manis harum, cokelat lumer, dan keju gurih dalam balutan roti lembut khas Roti\'O.',
                'note' => 'Kemas menggunakan box take-away khusus agar bentuk tetap utuh.',
            ],
            'Roti\'O Crombo\'O Chocolate' => [
                'desc' => 'Pastry renyah berlapis-lapis khas cromboloni dengan lelehan krim cokelat manis gurih melimpah.',
                'note' => 'Gunakan jepitan pastry saat memasukkan ke kotak take-away.',
            ],
            'Roti\'O Almond Turnover' => [
                'desc' => 'Pastry segitiga renyah beraroma mentega dengan taburan irisan almond panggang dan isian manis gurih.',
                'note' => 'Sajikan hangat untuk kerenyahan maksimal.',
            ],
            'Roti\'O Tuna Pastry' => [
                'desc' => 'Puff pastry gurih renyah dengan isian daging ikan tuna berbumbu rempah lezat dan gurih.',
                'note' => 'Tawarkan saus sambal sachet saat penyerahan pesanan.',
            ],
            'Roti\'O Beef Pastry' => [
                'desc' => 'Puff pastry emas berlapis mentega dengan isian daging sapi cincang berbumbu lada hitam gurih.',
                'note' => 'Hangatkan sebentar sebelum diserahkan ke pelanggan.',
            ],
            'Roti\'O Chicken Pastry' => [
                'desc' => 'Pastry renyah dengan isian daging ayam cincang gurih berbumbu sedap yang cocok untuk teman perjalanan.',
                'note' => 'Sajikan hangat dengan tisu dan kantong bersih.',
            ],
            'Roti\'O Cheese Feuillette' => [
                'desc' => 'Pastry tipis berlapis keju renyah garing dengan rasa gurih mentega yang nagih.',
                'note' => 'Pastikan kemasan tidak tergencet.',
            ],
            'Butter Croissant' => [
                'desc' => 'Croissant klasik Perancis dengan lapisan mentega gurih keemasan dan tekstur berongga lembut di dalam.',
                'note' => 'Panggang 1 menit jika diminta hangat oleh penumpang.',
            ],
            'Sausage Croissant' => [
                'desc' => 'Pastry croissant renyah berselubung mentega dengan isian sosis sapi premium gurih lezat.',
                'note' => 'Sajikan hangat, berikan saus sambal sachet.',
            ],
            'Kopi\'O Aren' => [
                'desc' => 'Perpaduan kopi espresso robusta segar dengan gula aren murni dan susu krim lembut khas bandara.',
                'note' => 'Tutup cup rapat dan pasang lid seal anti-tumpah untuk penumpang kabin/transit.',
            ],
            'Kopi\'O Latte' => [
                'desc' => 'Paduan seimbang antara double shot espresso dan susu steamed lembut dengan rasa kopi yang creamy.',
                'note' => 'Tutup cup rapat dan gunakan sleeve pelindung panas jika dipesan hangat.',
            ],
            'Chocolate Drink' => [
                'desc' => 'Minuman cokelat pekat nikmat dari biji kakao pilihan dengan rasa creamy manis gurih yang menyegarkan.',
                'note' => 'Aduk rata sebelum disajikan agar cokelat larut sempurna.',
            ],
        ];

        // Cari semua produk dari tenant yang namanya mengandung ROTI'O atau ROTI O
        $tenantIds = DB::table('tenants')
            ->where('name', 'like', '%ROTI%O%')
            ->orWhere('name', 'like', '%ROTI O%')
            ->pluck('id');

        foreach ($descriptions as $productName => $data) {
            DB::table('products')
                ->whereIn('tenant_id', $tenantIds)
                ->where('name', $productName)
                ->update([
                    'description' => $data['desc'],
                    'note' => $data['note'],
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tenantIds = DB::table('tenants')
            ->where('name', 'like', '%ROTI%O%')
            ->orWhere('name', 'like', '%ROTI O%')
            ->pluck('id');

        DB::table('products')
            ->whereIn('tenant_id', $tenantIds)
            ->update([
                'description' => null,
                'note' => null,
            ]);
    }
};
