<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        
        DB::table('Ms_modules')->insert([
            // 1. MODULE UTAMA (DASHBOARD & TRANSAKSI)
            [
                'module_name' => 'Main Operations',
                'code'        => 'MAIN',
                'is_active'   => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],

            // 2. MODULE PRODUKSI (UPDATE STATUS CUCIAN)
            [
                'module_name' => 'Laundry Production',
                'code'        => 'PROD',
                'is_active'   => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
          
            // 3. MODULE MASTER DATA (LAYANAN, PELANGGAN, OUTLET)
            [
                'module_name' => 'Master Data',
                'code'        => 'MSTR',
                'is_active'   => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],

            // 4. MODULE FINANCE (PENGELUARAN & LAPORAN)
            [
                'module_name' => 'Finance & Reports',
                'code'        => 'FINC',
                'is_active'   => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],

            // 5. MODULE SYSTEM (SETTING, USER MANAGEMENT, LOGS)
            [
                'module_name' => 'System Management',
                'code'        => 'SYST',
                'is_active'   => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],

            // 6. MODULE CUSTOMER (KHUSUS TRACKING & BOOKING)
            [
                'module_name' => 'Customer Portal',
                'code'        => 'CUST',
                'is_active'   => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ]);
    }
}