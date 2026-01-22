<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Mengambil ID Module yang relevan
        $mainModId = DB::table('Ms_modules')->where('code', 'MAIN')->value('id');
        $prodModId = DB::table('Ms_modules')->where('code', 'PROD')->value('id');
        $mstrModId = DB::table('Ms_modules')->where('code', 'MSTR')->value('id');
        $fincModId = DB::table('Ms_modules')->where('code', 'FINC')->value('id');
        $systModId = DB::table('Ms_modules')->where('code', 'SYST')->value('id');

        /*
        |--------------------------------------------------------------------------
        | 1. MAIN MENU (DASHBOARD & KASIR)
        |--------------------------------------------------------------------------
        | Ikon disesuaikan untuk aktivitas utama laundry.
        */
        $mainMenus = [
            ['DASHBOARD', 'Dashboard', 'layout', '/dashboard', 1],
            ['POS_TRANSAKSI', 'Kasir / Order Baru', 'shopping-cart', '/transaksi/baru', 2],
            ['ORDER_LIST', 'Daftar Semua Pesanan', 'clipboard', '/orders', 3],
            ['WHATSAPP_NOTIF', 'WhatsApp Gateway', 'message-square', '/whatsapp', 13],
        ];

        foreach ($mainMenus as $menu) {
            DB::table('Ms_menus')->insert([
                'module_id'  => $mainModId,
                'code'       => $menu[0],
                'menu_name'  => $menu[1],
                'icon'       => $menu[2],
                'route_name' => $menu[3],
                'parent_id'  => null,
                'order_no'   => $menu[4],
                'is_active'  => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 2. OPERASIONAL / PRODUKSI (PARENT + CHILD)
        |--------------------------------------------------------------------------
        | Alur kerja dari cucian masuk sampai siap ambil.
        */
        $prodParentId = DB::table('Ms_menus')->insertGetId([
            'module_id'  => $prodModId,
            'code'       => 'PRODUCTION',
            'menu_name'  => 'Operasional Dapur',
            'icon'       => 'refresh-cw',
            'route_name' => null,
            'parent_id'  => null,
            'order_no'   => 4,
            'is_active'  => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $prodChildren = [
            ['PROD_WASH', 'Proses Cuci', '/prod/washing'],
            ['PROD_IRON', 'Proses Setrika', '/prod/ironing'],
            ['PROD_PACK', 'Packing & QC', '/prod/packing'],
            ['PROD_READY', 'Siap Diambil/Kirim', '/prod/ready'],
        ];

        foreach ($prodChildren as $i => $child) {
            DB::table('Ms_menus')->insert([
                'module_id'  => $prodModId,
                'code'       => $child[0],
                'menu_name'  => $child[1],
                'icon'       => 'layers', // Ikon tumpukan tugas
                'route_name' => $child[2],
                'parent_id'  => $prodParentId,
                'order_no'   => $i + 1,
                'is_active'  => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 3. MASTER DATA (PELANGGAN & LAYANAN)
        |--------------------------------------------------------------------------
        */
        $mstrParentId = DB::table('Ms_menus')->insertGetId([
            'module_id'  => $mstrModId,
            'code'       => 'MASTER',
            'menu_name'  => 'Master Data',
            'icon'       => 'database',
            'route_name' => null,
            'parent_id'  => null,
            'order_no'   => 5,
            'is_active'  => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $mstrChildren = [
            ['MSTR_CUSTOMER', 'Data Pelanggan', '/master/customer'],
            ['MSTR_SERVICE', 'Layanan & Harga', '/master/services'],
            ['MSTR_COUPON', 'Kupon & Diskon', '/master/coupons'],
        ];

        foreach ($mstrChildren as $i => $child) {
            DB::table('Ms_menus')->insert([
                'module_id'  => $mstrModId,
                'code'       => $child[0],
                'menu_name'  => $child[1],
                'icon'       => 'users',
                'route_name' => $child[2],
                'parent_id'  => $mstrParentId,
                'order_no'   => $i + 1,
                'is_active'  => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 4. FINANCE (BILING & LAPORAN)
        |--------------------------------------------------------------------------
        */
        $billingParentId = DB::table('Ms_menus')->insertGetId([
            'module_id'  => $fincModId,
            'code'       => 'FINANCE',
            'menu_name'  => 'Keuangan',
            'icon'       => 'credit-card',
            'route_name' => null,
            'parent_id'  => null,
            'order_no'   => 11,
            'is_active'  => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $billingChildren = [
            ['FINC_INVOICE_UNPAID', 'Tagihan Belum Bayar', '/finance/unpaid'],
            ['FINC_INVOICE_PAID', 'Riwayat Pendapatan', '/finance/paid'],
            ['FINC_EXPENSE', 'Biaya Pengeluaran', '/finance/expense'],
            ['FINC_REPORT', 'Laporan Laba/Rugi', '/finance/report'],
        ];

        foreach ($billingChildren as $i => $child) {
            DB::table('Ms_menus')->insert([
                'module_id'  => $fincModId,
                'code'       => $child[0],
                'menu_name'  => $child[1],
                'icon'       => 'file-text',
                'route_name' => $child[2],
                'parent_id'  => $billingParentId,
                'order_no'   => $i + 1,
                'is_active'  => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 5. SYSTEM (PENGATURAN TEKNIS)
        |--------------------------------------------------------------------------
        */
        $systemMenus = [
            ['SETTING', 'Pengaturan Toko', 'settings', '/system/setting', 20],
            ['ADMIN_MGMT', 'Manajemen Staff', 'user-check', '/system/admin', 22],
            ['LOGS', 'Log Aktivitas', 'activity', '/system/logs', 23],
        ];

        foreach ($systemMenus as $menu) {
            DB::table('Ms_menus')->insert([
                'module_id'  => $systModId,
                'code'       => $menu[0],
                'menu_name'  => $menu[1],
                'icon'       => $menu[2],
                'route_name' => $menu[3],
                'parent_id'  => null,
                'order_no'   => $menu[4],
                'is_active'  => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}