<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\PermissionRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all()->groupBy('module');
        $roles = ['admin', 'kasir'];
        
        // Get existing assignments
        $assignments = PermissionRole::all()->groupBy('role_name')->map(function ($items) {
            return $items->pluck('permission_id')->toArray();
        });

        return view('permission.index', compact('permissions', 'roles', 'assignments'));
    }

    public function update(Request $request)
    {
        $data = $request->input('permissions', []); // [role => [perm_id, perm_id]]

        DB::transaction(function () use ($data) {
            // Clear existing for admin and kasir (Owner is protected)
            PermissionRole::whereIn('role_name', ['admin', 'kasir'])->delete();

            foreach ($data as $role => $permIds) {
                if (!in_array($role, ['admin', 'kasir'])) continue;

                foreach ($permIds as $permId) {
                    PermissionRole::create([
                        'permission_id' => $permId,
                        'role_name' => $role
                    ]);
                }
            }
        });

        return redirect()->back()->with('success', 'Otoritas kewenangan berhasil diperbarui.');
    }
}
