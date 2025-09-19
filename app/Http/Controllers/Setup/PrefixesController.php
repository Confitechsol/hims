<?php
namespace App\Http\Controllers\Setup;
use App\Http\Controllers\Controller;
use App\Models\Prefix;
use Illuminate\Http\Request;

class PrefixesController extends Controller
{
    public function index()
    {
        // Fetch all prefixes as key => value (e.g. ['ipd_no' => 'IPD001'])
        $prefixes = Prefix::pluck('prefix', 'type')->toArray();

        return view('admin.setup.prefix', compact('prefixes'));
    }
    public function store(Request $request)
    {
        foreach ($request->fields as $field) {
            Prefix::create([
                'type'   => $field['type'],
                'prefix' => $field['prefix'],
            ]);
        }

        return redirect()->back()->with('success', 'Prefixes saved successfully.');
    }

    public function update(Request $request)
    {
        if (! $request->has('fields')) {
            return back()->with('error', 'No prefixes submitted.');
        }
        foreach ($request->fields as $field) {
            Prefix::updateOrCreate(
                ['type' => $field['type']], // match by type
                ['prefix' => $field['prefix']]
            );
        }

        return redirect()->back()->with('success', 'Prefixes updated successfully.');
    }

}
