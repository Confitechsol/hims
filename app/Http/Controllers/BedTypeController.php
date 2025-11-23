<?php
namespace App\Http\Controllers;

use App\Models\BedType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
class BedTypeController extends Controller
{
    public function index(Request $request)
    {
        $perPage = intval($request->input('perPage', 5));
        if ($perPage <= 0) {
            $perPage = 5;
        }
        $bedTypeQuery = BedType::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $bedTypeQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            });
            $bedTypes = $bedTypeQuery->paginate($perPage);
            return response()->json([
                'result' => $bedTypes
            ]);
        }
        $bedTypes = $bedTypeQuery->paginate($perPage);
        return view('admin.bed-type.index', compact('bedTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:bed_type,name',
        ]);

        BedType::create(['name' => $request->name]);

        return redirect()->back()->with('success', 'Bed type created successfully.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:bed_type,id',
            'name' => 'required|string|max:255|unique:bed_type,name,' . $request->id,
        ]);

        $type = BedType::findOrFail($request->id);
        $type->name = $request->name;
        $type->save();

        return redirect()->back()->with('success', 'Bed type updated successfully.');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:bed_type,id',
        ]);

        BedType::findOrFail($request->id)->delete();

        return redirect()->back()->with('success', 'Bed type deleted successfully.');
    }
}

