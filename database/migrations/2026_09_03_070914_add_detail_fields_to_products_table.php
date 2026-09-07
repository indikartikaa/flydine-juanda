<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('category', 50)->nullable()->after('name');
            $table->text('description')->nullable()->after('category');
            $table->string('image')->nullable()->after('description');
            $table->text('note')->nullable()->after('image');
            $table->integer('stock')->default(0)->after('note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'category',
                'description',
                'image',
                'note',
                'stock'
            ]);
        });
    }
};