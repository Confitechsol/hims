<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Str;

class RolesController extends Controller
{
    public function index()
    {
        // Fetch all roles for the current hospital (optional filtering by branch)
        $roles = Role::where('hospital_id', auth()->user()->hospital_id)
                     ->orderBy('id', 'desc')
                     ->get();

        return view('admin.setup.role', compact('roles'));
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $role = Role::create([
            'hospital_id'   => auth()->user()->hospital_id, // or session('hospital_id')
            'branch_id'     => $request->branch_id ?? null,
            'name'          => $request->name,
            'slug'          => Str::slug($request->name),
            'is_active'     => 1,
            'is_system'     => 1,
            'is_superadmin' => 0,
        ]);

        return redirect()->back()->with('success', 'Role created successfully!');
    }
    
}
