<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Dummy: 1 akun Tenant Staff untuk beberapa tenant pilot + 1 akun Admin/Ops.
 * Target indikatif PRD 17.1.3: 4-6 baris users.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $tenants = DB::table('tenants')->get();

        foreach ($tenants as $tenant) {
            $slug = Str::slug($tenant->name . '-' . $tenant->tenant_code);
            DB::table('users')->insert([
                'name' => 'Staff ' . $tenant->name . ' (' . $tenant->tenant_code . ')',
                'email' => 'staff.' . $slug . '@flydine.test',
                'password' => Hash::make('password'),
                'role' => 'tenant_staff',
                'tenant_id' => $tenant->id,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('users')->insert([
            'name' => 'Admin Ops Komersial',
            'email' => 'admin.ops@flydine.test',
            'password' => Hash::make('password'),
            'role' => 'admin_ops',
            'tenant_id' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
