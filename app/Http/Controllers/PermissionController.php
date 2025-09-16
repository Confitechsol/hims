<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermissionGroup;
use App\Models\PermissionPatient;
use App\Models\PermissionCategory;
use App\Models\Role;
use App\Models\RolesPermission;

class PermissionController extends Controller
{
    public function modules()
    {
        $adminModules = PermissionGroup::all();
        $userModules  = PermissionPatient::all();

        return view('admin.setup.modules', compact('adminModules', 'userModules'));
    }
public function permissionsOld(Role $role)
{
    
    $permissionGroups = PermissionGroup::all();

    $permissionCategory = PermissionCategory::with(['rolePermissions' => function($q) use ($role) {
        $q->where('role_id', $role->id);
    }])->whereIn('perm_group_id', $permissionGroups->pluck('id'))->get();

    return view('admin.setup.permissions', [
        'permissionGroups' => $permissionGroups,
        'permissionCategory' => $permissionCategory,
        'role' => $role,
        'roleId' => $role->id,
    ]);
}
public function permissions(Role $role)
{
    $permissionGroups = PermissionGroup::with(['categories.rolePermissions' => function($q) use ($role) {
        $q->where('role_id', $role->id);
    }])->get();

    return view('admin.setup.permissions', [
        'permissionGroups' => $permissionGroups,
        'role' => $role,
        'roleId' => $role->id,
    ]);
}



    public function toggle(Request $request)
    {
        $role = $request->role;
        $id = $request->id;
        $isActive = $request->is_active;

        if ($role === 'admin') {
            $module = PermissionGroup::find($id);
        } else {
            $module = PermissionPatient::find($id);
        }

        if ($module) {
            $module->is_active = $isActive;
            $module->save();
            return response()->json(['success' => true, 'message' => 'Permission updated']);
        }

        return response()->json(['success' => false, 'message' => 'Module not found'], 404);
    }


    public function update(Request $request)
    {
        $role = $request->role;
        $permissions = $request->permissions ?? [];

        foreach ($permissions as $moduleId => $isActive) {
            RoleModulePermission::updateOrCreate(
                ['role' => $role, 'permission_group_id' => $moduleId],
                ['is_active' => $isActive]
            );
        }

        return back()->with('success', ucfirst($role) . ' permissions updated successfully!');
    }
}
