<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan composite index pada tabel orders untuk optimasi query respons (Query Performance Tuning).
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['tenant_id', 'ordered_at'], 'idx_orders_tenant_ordered');
            $table->index(['status', 'ordered_at'], 'idx_orders_status_ordered');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_tenant_ordered');
            $table->dropIndex('idx_orders_status_ordered');
        });
    }
};
