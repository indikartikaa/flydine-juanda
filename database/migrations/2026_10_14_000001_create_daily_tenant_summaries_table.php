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
        Schema::create('daily_tenant_summaries', function (Blueprint $table) {
            $table->id();
            $table->date('summary_date');
            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->unsignedInteger('total_orders')->default(0);
            $table->unsignedInteger('paid_orders')->default(0);
            $table->unsignedInteger('cancelled_orders')->default(0);
            $table->decimal('total_revenue', 14, 2)->default(0);
            $table->unsignedInteger('ready_orders')->default(0);
            $table->unsignedInteger('sla_compliant_orders')->default(0);
            $table->decimal('avg_sla_minutes', 8, 2)->nullable();
            $table->timestamps();

            $table->unique(['summary_date', 'tenant_id']);
            $table->index('summary_date');
        });

        // Modifikasi kolom tenant_id pada orders agar nullable untuk mendukung data cleaning & penanganan order anomali
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable(false)->change();
        });

        Schema::dropIfExists('daily_tenant_summaries');
    }
};
