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
        Schema::table('tenants', function (Blueprint $table) {
            $table->decimal('delivery_fee', 12, 2)->nullable()->default(null)->after('is_active');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->enum('pickup_method', ['ambil_sendiri', 'diantar'])->default('ambil_sendiri')->after('is_paid');
            $table->foreignId('delivery_location_id')->nullable()->constrained('delivery_locations')->nullOnDelete()->after('pickup_method');
            $table->decimal('delivery_fee', 12, 2)->nullable()->after('delivery_location_id');
            // Membuat gate menjadi nullable karena user memilih "Ambil Sendiri" tidak butuh mengisi gate secara text
            $table->string('gate')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['delivery_location_id']);
            $table->dropColumn(['pickup_method', 'delivery_location_id', 'delivery_fee']);
            // Revert gate menjadi tidak nullable (harus ada string default jika asalnya not null)
            $table->string('gate')->nullable(false)->change();
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('delivery_fee');
        });
    }
};
