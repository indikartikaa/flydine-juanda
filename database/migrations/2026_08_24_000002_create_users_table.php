<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {

            // Primary Key
            $table->id();


            // Informasi user
            $table->string('name', 100);

            $table->string('email', 150)
                ->unique();

            $table->string('password', 255);



            /*
            |--------------------------------------------------------------------------
            | ROLE USER
            |--------------------------------------------------------------------------
            |
            | tenant_staff :
            |   User yang bekerja pada tenant tertentu
            |
            | admin_ops :
            |   Pengelola sistem FlyDine
            |
            */

            $table->enum('role', [
                'tenant_staff',
                'admin_ops'
            ])
            ->default('tenant_staff');



            /*
            |--------------------------------------------------------------------------
            | RELASI TENANT
            |--------------------------------------------------------------------------
            |
            | Admin tidak memiliki tenant_id
            | Tenant Staff wajib memiliki tenant_id
            |
            */

            $table->foreignId('tenant_id')
                ->nullable()
                ->constrained('tenants')
                ->nullOnDelete()
                ->cascadeOnUpdate();



            // Status akun
            $table->boolean('is_active')
                ->default(true);



            // Laravel authentication support
            $table->rememberToken();


            // created_at & updated_at
            $table->timestamps();

        });
    }



    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
