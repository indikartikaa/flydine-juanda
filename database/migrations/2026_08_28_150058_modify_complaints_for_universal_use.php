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
        Schema::table('complaints', function (Blueprint $table) {
            // Drop existing foreign key and make it nullable
            $table->dropForeign(['order_id']);
            $table->foreignId('order_id')->nullable()->change()->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            
            // Add reporter contact info
            $table->string('reporter_name', 100)->after('order_id');
            $table->string('reporter_contact', 50)->after('reporter_name');
        });
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn(['reporter_name', 'reporter_contact']);
            
            // Revert order_id to non-nullable (will fail if there are nulls, but it's for local dev)
            $table->dropForeign(['order_id']);
            $table->foreignId('order_id')->nullable(false)->change()->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }
};
