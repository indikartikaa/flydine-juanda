<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')
                ->constrained('tenants')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name', 120);
            $table->decimal('price', 12, 2);
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->index(['tenant_id', 'is_available']);
        });

        // CHECK constraint (Laravel's schema builder has no native check() before 11.x helpers on all drivers)
        DB::statement('ALTER TABLE products ADD CONSTRAINT chk_products_price CHECK (price >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
