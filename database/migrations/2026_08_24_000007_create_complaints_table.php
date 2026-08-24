<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('complaint_code', 40)->unique();
            $table->foreignId('order_id')
                ->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('category', ['pesanan_salah', 'status_tidak_update', 'lainnya']);
            $table->text('description');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->foreignId('handled_by_user_id')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->dateTime('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
