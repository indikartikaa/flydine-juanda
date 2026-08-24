<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code', 40)->unique();
            $table->foreignId('tenant_id')
                ->constrained('tenants')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('customer_id')
                ->constrained('customers')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('customer_name', 100);
            $table->string('flight_number', 15)->nullable();
            $table->string('gate', 20)->nullable();
            $table->dateTime('boarding_time')->nullable();
            $table->enum('status', ['menunggu', 'diproses', 'siap', 'selesai', 'dibatalkan'])
                ->default('menunggu');
            $table->dateTime('heading_to_tenant_at')->nullable();
            $table->dateTime('auto_cancel_at');
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->dateTime('ordered_at');
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status', 'ordered_at']);
            $table->index('auto_cancel_at');
            $table->index('ordered_at');
        });

        DB::statement('ALTER TABLE orders ADD CONSTRAINT chk_orders_total_amount CHECK (total_amount >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
