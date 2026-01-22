<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoleMenuPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Ambil role ID berdasarkan code yang baru
        $roles = DB::table('Ms_roles')->pluck('id', 'code');

        // Ambil semua menu laundry
        $menus = DB::table('Ms_menus')->select('id', 'code', 'module_id')->get();

        $permissions = [];

        /*
        |--------------------------------------------------------------------------
        | 1. OWNER — FULL ACCESS (Segala Akses)
        |--------------------------------------------------------------------------
        */
        foreach ($menus as $menu) {
            $permissions[] = $this->permissionRow($roles['OWNER'], $menu, true, true, true, true, true, $now);
        }

        /*
        |--------------------------------------------------------------------------
        | 2. CASHIER — TRANSAKSI & CUSTOMER
        |--------------------------------------------------------------------------
        */
        $cashierAllowed = ['DASHBOARD', 'POS_TRANSAKSI', 'ORDER_LIST', 'MSTR_CUSTOMER', 'MSTR_COUPON', 'FINANCE'];
        
        foreach ($menus as $menu) {
            // Cek apakah menu termasuk yang diizinkan untuk kasir
            $canAccess = in_array($menu->code, $cashierAllowed) || str_contains($menu->code, 'PROD_READY');

            if ($canAccess) {
                $permissions[] = $this->permissionRow(
                    $roles['CASHIER'], 
                    $menu, 
                    canView: true, 
                    canCreate: true, 
                    canUpdate: true, 
                    canDelete: false, // Kasir tidak boleh hapus transaksi/data
                    canExport: true, 
                    now: $now
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 3. PRODUCTION — HANYA PROSES CUCI
        |--------------------------------------------------------------------------
        */
        foreach ($menus as $menu) {
            // Produksi hanya bisa lihat Dashboard dan menu Operasional (PROD_...)
            $isProdMenu = str_contains($menu->code, 'PROD_') || $menu->code === 'DASHBOARD';

            if ($isProdMenu) {
                $permissions[] = $this->permissionRow(
                    $roles['PRODUCTION'], 
                    $menu, 
                    canView: true, 
                    canCreate: false, 
                    canUpdate: true, // Hanya untuk update status (sedang dicuci -> selesai)
                    canDelete: false, 
                    canExport: false, 
                    now: $now
                );
            }
        }

        DB::table('Ms_role_menu_permissions')->insert($permissions);
    }

    private function permissionRow($roleId, $menu, $canView, $canCreate, $canUpdate, $canDelete, $canExport, $now): array 
    {
        return [
            'role_id'    => $roleId,
            'module_id'  => $menu->module_id,
            'menu_id'    => $menu->id,
            'can_view'   => $canView,
            'can_create' => $canCreate,
            'can_update' => $canUpdate,
            'can_delete' => $canDelete,
            'can_export' => $canExport,
            'is_active'  => true,
            'created_by' => 'system',
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}