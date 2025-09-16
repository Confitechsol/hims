<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermissionGroup;
use App\Models\PermissionPatient;

class PermissionController extends Controller
{
    public function index()
    {
        $adminModules = PermissionGroup::all();
        $userModules  = PermissionPatient::all();

        return view('admin.setup.modules', compact('adminModules', 'userModules'));
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
