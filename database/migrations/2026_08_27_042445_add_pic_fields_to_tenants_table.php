<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('pic_name', 100)->nullable()->after('phone');
            $table->string('pic_email', 150)->nullable()->after('pic_name');
            $table->string('pic_phone', 20)->nullable()->after('pic_email');
            $table->string('logo')->nullable()->after('pic_phone');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'pic_name',
                'pic_email',
                'pic_phone',
                'logo',
            ]);
        });
    }
};
