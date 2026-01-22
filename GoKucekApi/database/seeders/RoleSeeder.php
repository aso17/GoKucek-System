<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('Ms_roles')->insert([
            // 1. Pemilik (Akses ke semua laporan & pengaturan)
            [
                'role_name'  => 'Owner (Super Admin)',
                'code'       => 'OWNER',
                'is_active'  => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            
            // 2. Admin/Manager (Bisa mengelola karyawan & stok)
            [
                'role_name'  => 'Administrator',
                'code'       => 'ADMIN',
                'is_active'  => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // 3. Kasir (Fokus di transaksi & pendaftaran pelanggan)
            [
                'role_name'  => 'Cashier',
                'code'       => 'CASHIER',
                'is_active'  => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // 4. Petugas Produksi (Tukang Cuci & Setrika)
            [
                'role_name'  => 'Production Staff',
                'code'       => 'PRODUCTION',
                'is_active'  => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // 5. Kurir (Antar Jemput Cucian)
            [
                'role_name'  => 'Courier',
                'code'       => 'COURIER',
                'is_active'  => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // 6. Member/Customer (Hanya untuk akses aplikasi mobile/tracking)
            [
                'role_name'  => 'Customer',
                'code'       => 'CUSTOMER',
                'is_active'  => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}