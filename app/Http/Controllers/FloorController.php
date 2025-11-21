<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Floor;
use Illuminate\Support\Facades\Auth;
class FloorController extends Controller
{
    public function index(Request $request)
    {
        $floors = Floor::query();
        $perPage = intval($request->input('perPage', 5));
        if ($perPage <= 0) {
           $perPage = 5;
        }
        if($request->has('search')){
            $search_term = $request->search;
            $floors->where(function ($query) use ($search_term) {
                $query->where('name', 'like', "%{$search_term}%");
            });
            $floors = $floors->paginate($perPage);
            return array("result"=>$floors);

        }
        $floors = $floors->paginate($perPage);
        return view('admin.floor.index', compact('floors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:floors,name',
        ]);

        Floor::create(['name' => $request->name]);

        return redirect()->back()->with('success', 'Floor created successfully.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:floors,id',
            'name' => 'required|string|max:255|unique:floors,name,' . $request->id,
        ]);

        $floor = Floor::findOrFail($request->id);
        $floor->name = $request->name;
        $floor->save();

        return redirect()->back()->with('success', 'Floor updated successfully.');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:floors,id',
        ]);

        Floor::findOrFail($request->id)->delete();

        return redirect()->back()->with('success', 'Floor deleted successfully.');
    }
}
