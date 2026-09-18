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
            $table->string('company_name')->nullable()->after('name');
            $table->string('category')->nullable()->after('company_name');
            $table->date('contract_start')->nullable()->after('delivery_fee');
            $table->date('contract_end')->nullable()->after('contract_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'category', 'contract_start', 'contract_end']);
        });
    }
};
