<?php

namespace App\Http\Controllers;

use App\Models\DispatchReceive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DispatchReceiveController extends Controller
{
    /**
     * Show receive list
     */
    public function receive(Request $request)
    {
        $items = DispatchReceive::where('type', 'receive')->orderBy('date', 'desc')->paginate(25);
        return view('admin.front-office.dispatch-receive.index', ['type' => 'Receive', 'items' => $items]);
    }

    /**
     * Show dispatch list
     */
    public function dispatch(Request $request)
    {
        $items = DispatchReceive::where('type', 'dispatch')->orderBy('date', 'desc')->paginate(25);
        return view('admin.front-office.dispatch-receive.index', ['type' => 'Dispatch', 'items' => $items]);
    }

    /**
     * Return JSON of dispatch_receive rows. Optional ?type=receive|dispatch to filter.
     */
    public function listJson(Request $request)
    {
        $query = DispatchReceive::query();
        if ($request->has('type')) {
            $type = $request->query('type');
            $query->where('type', $type);
        }

        // Return latest first
        $items = $query->orderBy('date', 'desc')->get();

        return response()->json(['data' => $items]);
    }

    /**
     * Show create page (we also use modal forms in index)
     */
    public function create(Request $request)
    {
        return redirect()->back();
    }

    /**
     * Store a new record
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reference_no' => 'required|string|max:50',
            'to_title' => 'nullable|string|max:100',
            'from_title' => 'nullable|string|max:200',
            'address' => 'nullable|string',
            'note' => 'nullable|string',
            'date' => 'nullable|date',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:4096',
            'type' => 'required|in:receive,dispatch',
        ]);

        $image = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $image = '/uploads/' . $fileName;
        }

        DispatchReceive::create([
            'hospital_id' => $request->user()->hospital_id ?? '0',
            'branch_id' => $request->user()->branch_id ?? '0',
            'reference_no' => $validated['reference_no'],
            'to_title' => $validated['to_title'] ?? null,
            'from_title' => $validated['from_title'] ?? null,
            'address' => $validated['address'] ?? null,
            'note' => $validated['note'] ?? null,
            'date' => $validated['date'] ?? null,
            'image' => $image,
            'type' => $validated['type'],
        ]);

        return redirect()->back()->with('success', ucfirst($validated['type']) . ' record added successfully!');
    }

    /**
     * Update an existing record
     */
    public function update(Request $request, $id)
    {
        $item = DispatchReceive::findOrFail($id);

        $validated = $request->validate([
            'reference_no' => 'required|string|max:50',
            'to_title' => 'nullable|string|max:100',
            'from_title' => 'nullable|string|max:200',
            'address' => 'nullable|string',
            'note' => 'nullable|string',
            'date' => 'nullable|date',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:4096',
            'type' => 'required|in:receive,dispatch',
        ]);

        if ($request->hasFile('image')) {
            if (!empty($item->image) && str_starts_with($item->image, '/uploads/')) {
                $oldPath = public_path(ltrim($item->image, '/'));
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $item->image = '/uploads/' . $fileName;
        }

        $item->reference_no = $validated['reference_no'];
        $item->to_title = $validated['to_title'] ?? null;
        $item->from_title = $validated['from_title'] ?? null;
        $item->address = $validated['address'] ?? null;
        $item->note = $validated['note'] ?? null;
        $item->date = $validated['date'] ?? null;
        $item->type = $validated['type'];
        $item->save();

        return redirect()->back()->with('success', 'Record updated successfully!');
    }

    /**
     * Show a single record
     */
    public function show($id)
    {
        $item = DispatchReceive::findOrFail($id);

        // Get columns from the dispatch_receive table and filter out any sensitive/internal columns if needed
        $columns = Schema::getColumnListing((new DispatchReceive())->getTable());

        // Optionally remove columns we don't want to show
        $exclude = ['hospital_id', 'branch_id'];
        $columns = array_values(array_filter($columns, function ($c) use ($exclude) {
            return !in_array($c, $exclude);
        }));

        return view('admin.front-office.dispatch-receive.show', compact('item', 'columns'));
    }

    /**
     * Delete a record
     */
    public function destroy($id)
    {
        $item = DispatchReceive::findOrFail($id);
        if (!empty($item->image) && strpos($item->image, '/uploads/') === 0) {
            $oldPath = public_path(ltrim($item->image, '/'));
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }
        $item->delete();
        return redirect()->back()->with('success', 'Record deleted successfully!');
    }
}
