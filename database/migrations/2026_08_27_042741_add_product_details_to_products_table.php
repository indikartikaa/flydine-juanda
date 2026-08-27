<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('product_variant_groups')) {
            Schema::create('product_variant_groups', function (Blueprint $table) {
                $table->id();

                $table->foreignId('product_id')
                    ->constrained('products')
                    ->cascadeOnDelete();

                $table->string('name', 100);
                $table->boolean('is_required')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('product_variant_options')) {
            Schema::create('product_variant_options', function (Blueprint $table) {
                $table->id();

                $table->foreignId('variant_group_id')
                    ->constrained('product_variant_groups')
                    ->cascadeOnDelete();

                $table->string('name', 100);
                $table->decimal('additional_price', 12, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variant_options');
        Schema::dropIfExists('product_variant_groups');
    }
};
