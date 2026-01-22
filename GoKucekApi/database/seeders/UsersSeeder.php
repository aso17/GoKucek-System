<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Ambil roles yang baru (OWNER, CASHIER, dll)
        $roles = DB::table('Ms_roles')
            ->whereIn('code', ['OWNER', 'ADMIN', 'CASHIER', 'PRODUCTION'])
            ->pluck('id', 'code')
            ->toArray();

        // 2. Ambil tenant default (Outlet Utama GoKucek)
        $defaultTenantId = DB::table('Ms_tenants')
            ->where('code', 'TEN-001')
            ->value('id');

        if (!$defaultTenantId) {
            // Kita buatkan tenant default jika belum ada supaya tidak error
            $defaultTenantId = DB::table('Ms_tenants')->insertGetId([
                'code' => 'TEN-001',
                'name' => 'GoKucek Pusat',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 3. Daftar User sesuai Role Laundry
        $users = [
            [
                'full_name' => 'Aso Owner',
                'username'  => 'aso_owner',
                'email'     => 'owner@gokucek.id',
                'role_code' => 'OWNER',
            ],
            [
                'full_name' => 'Kasir Utama',
                'username'  => 'kasir1',
                'email'     => 'kasir@gokucek.id',
                'role_code' => 'CASHIER',
            ],
            [
                'full_name' => 'Tim Cuci',
                'username'  => 'tukang_cuci',
                'email'     => 'produksi@gokucek.id',
                'role_code' => 'PRODUCTION',
            ],
        ];

        $payload = collect($users)->map(function ($user) use ($roles, $defaultTenantId, $now) {
            return [
                'full_name'         => $user['full_name'],
                'username'          => $user['username'],
                'email'             => $user['email'],
                'password'          => Hash::make('P@ssw0rd12345'), // Password default

                'is_active'         => true,
                'status'            => 'active',
                'email_verified_at' => $now,
                
                'role_id'           => $roles[$user['role_code']] ?? null,
                'tenant_id'         => $defaultTenantId,

                'created_at'        => $now,
                'updated_at'        => $now,
            ];
        })->toArray();

        // Menggunakan upsert agar aman saat dijalankan berkali-kali
        DB::table('Ms_users')->upsert(
            $payload,
            ['email'], // Kunci unik
            ['full_name', 'username', 'role_id', 'tenant_id', 'updated_at']
        );
    }
}