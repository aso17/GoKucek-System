<?php

namespace App\Http\Controllers;

use App\Models\Ms_role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * GET /api/roles
     */
    public function GetRolesByTenantId(Request $request)
    {
         $tenantId = $request->tenant_id;
        $roles = Ms_role::select('id', 'role_name', 'code')
            ->where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('role_name')
            ->get();

        return response()->json($roles);
    }

    public function index(Request $request)
        {
            $tenantId = $request->tenant_id; 
            $keyword = $request->keyword;
            $perPage = $request->per_page ?? 10;
            $roles = Ms_role::where('tenant_id', $tenantId)
                ->when($keyword, function ($query) use ($keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('role_name', 'like', "%{$keyword}%")
                        ->orWhere('code', 'like', "%{$keyword}%");
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return response()->json($roles);
        }


}
