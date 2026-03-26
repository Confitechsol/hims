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
    
    $permissionGroups = PermissionGroup::where('is_active', 1)->get();
    //dd($permissionGroups);

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
        }])->where('is_active', 1)->get();

        return view('admin.setup.permissions', [
            'permissionGroups' => $permissionGroups,
            'role' => $role,
            'roleId' => $role->id,
        ]);
    }
    public function savePermissions(Request $request)
    {
        $roleId      = (int) $request->role_id;
        $permissions = $request->permissions ?? []; // array from checkboxes, keyed by perm_cat_id
        $hospitalId  = auth()->user()->hospital_id ?? null;

        // 1) Save / update all categories that have at least one checked permission
        foreach ($permissions as $permCatId => $types) {
            RolesPermission::updateOrCreate(
                [
                    'role_id'     => $roleId,
                    'perm_cat_id' => $permCatId,
                    'hospital_id' => $hospitalId,
                ],
                [
                    'can_view'   => in_array('can_view', $types),
                    'can_add'    => in_array('can_add', $types),
                    'can_edit'   => in_array('can_edit', $types),
                    'can_delete' => in_array('can_delete', $types),
                ]
            );
        }

        // 2) For any existing categories that are missing from the request
        //    (all checkboxes unchecked), explicitly set all permissions to 0
        $touchedPermCatIds = array_map('intval', array_keys($permissions));

        if (!empty($touchedPermCatIds)) {
            RolesPermission::where('role_id', $roleId)
                ->where('hospital_id', $hospitalId)
                ->whereNotIn('perm_cat_id', $touchedPermCatIds)
                ->update([
                    'can_view'   => 0,
                    'can_add'    => 0,
                    'can_edit'   => 0,
                    'can_delete' => 0,
                ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Permissions saved successfully.'
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
