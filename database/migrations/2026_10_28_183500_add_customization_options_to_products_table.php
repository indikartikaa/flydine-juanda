<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'customization_options')) {
            Schema::table('products', function (Blueprint $table) {
                $table->json('customization_options')->nullable()->after('note');
            });
        }

        // Inisialisasi default cerdas untuk produk minuman/kopi & hidangan pedas yang sudah ada
        $products = DB::table('products')->get();
        foreach ($products as $product) {
            $lowerName = strtolower($product->name ?? '');
            $lowerCat = strtolower($product->category ?? '');
            $options = [];

            $isCoffeeOrMilk = str_contains($lowerName, 'kopi') ||
                str_contains($lowerName, 'coffee') ||
                str_contains($lowerName, 'latte') ||
                str_contains($lowerName, 'cappuccino') ||
                str_contains($lowerName, 'macchiato') ||
                str_contains($lowerName, 'mocha') ||
                str_contains($lowerName, 'milk tea') ||
                str_contains($lowerName, 'matcha') ||
                str_contains($lowerName, 'chocolate') ||
                str_contains($lowerName, 'milo');

            $isOtherBeverage = str_contains($lowerName, 'americano') ||
                str_contains($lowerName, 'tea') ||
                str_contains($lowerName, 'teh') ||
                str_contains($lowerName, 'lemon') ||
                str_contains($lowerName, 'blend') ||
                str_contains($lowerName, 'frappuccino');

            $isSpicyFood = str_contains($lowerName, 'penyet') ||
                str_contains($lowerName, 'rawon') ||
                str_contains($lowerName, 'soto') ||
                str_contains($lowerName, 'bebek') ||
                str_contains($lowerName, 'pedas') ||
                str_contains($lowerName, 'geprek') ||
                str_contains($lowerName, 'sambal') ||
                str_contains($lowerName, 'nasi goreng') ||
                str_contains($lowerName, 'mie');

            if ($isCoffeeOrMilk) {
                $options = ['temperature', 'size', 'milk', 'sugar', 'ice'];
            } elseif ($isOtherBeverage) {
                $options = ['temperature', 'size', 'sugar', 'ice'];
            } elseif ($isSpicyFood && !str_contains($lowerCat, 'minuman')) {
                $options = ['spicy'];
            }

            if (!empty($options)) {
                DB::table('products')
                    ->where('id', $product->id)
                    ->update(['customization_options' => json_encode($options)]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'customization_options')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('customization_options');
            });
        }
    }
};

