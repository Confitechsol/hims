<?php

namespace App\Http\Controllers;
use App\Models\VisitorsPurpose;
use App\Models\VisitorBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitorsController extends Controller
{
    function index(Request $request){

    $query = VisitorsPurpose::with(['visitorBooks'])->get();
    $perPage = intval($request->input('perPage', 5));
     if ($perPage <= 0) {
        $perPage = 5;
    }
    $query = VisitorBook::query();
    if ($request->has('search')) {
        $search_term = $request->search;
        $query->where(function ($q) use ($search_term) {
            $q->where('name', 'like', "%{$search_term}%")
                ->orWhere('purpose', 'like', "%{$search_term}%")
                ->orWhere('visit_to', 'like', "%{$search_term}%");
        });
    }
    $visitorsReports = $query->paginate($perPage);
    
    // Get purposes for dropdown
    $purposes = VisitorsPurpose::all();
    
    // Get distinct visit_to and related_to from visitor_book table
    $visitToOptions = VisitorBook::distinct()->whereNotNull('visit_to')->pluck('visit_to')->toArray();
    $relatedToOptions = VisitorBook::distinct()->whereNotNull('related_to')->pluck('related_to')->toArray();

    return view('admin.front-office.visitorlist' ,compact('visitorsReports', 'purposes', 'visitToOptions', 'relatedToOptions'));
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
            'contact' => 'nullable|string|max:20',
            'id_proof' => 'nullable|string|max:255',
            'visit_to' => 'nullable|string|max:255',
            'related_to' => 'nullable|string|max:255',
            'no_of_pepple' => 'nullable|integer',
            'date' => 'required|date',
            'in_time' => 'nullable|date_format:H:i',
            'out_time' => 'nullable|date_format:H:i',
            'note' => 'nullable|string|max:1000',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $image = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $image = '/uploads/' . $fileName;
        }

        VisitorBook::create([
            'name' => $validated['name'],
            'purpose' => $validated['purpose'],
            'contact' => $validated['contact'] ?? null,
            'id_proof' => $validated['id_proof'] ?? null,
            'visit_to' => $validated['visit_to'] ?? null,
            'related_to' => $validated['related_to'] ?? null,
            'no_of_pepple' => $validated['no_of_pepple'] ?? 1,
            'date' => $validated['date'],
            'in_time' => $validated['in_time'] ?? null,
            'out_time' => $validated['out_time'] ?? null,
            'note' => $validated['note'] ?? null,
            'image' => $image,
        ]);

        return redirect()->back()->with('success', 'Visitor added successfully!');
    }

    public function update(Request $request, $id)
    {
        $visitor = VisitorBook::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
            'contact' => 'nullable|string|max:20',
            'id_proof' => 'nullable|string|max:255',
            'visit_to' => 'nullable|string|max:255',
            'related_to' => 'nullable|string|max:255',
            'no_of_pepple' => 'nullable|integer',
            'date' => 'required|date',
            'in_time' => 'nullable|date_format:H:i',
            'out_time' => 'nullable|date_format:H:i',
            'note' => 'nullable|string|max:1000',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle image replacement
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if (!empty($visitor->image) && str_starts_with($visitor->image, '/uploads/')) {
                $oldPath = public_path(ltrim($visitor->image, '/'));
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $visitor->image = '/uploads/' . $fileName;
        }

        $visitor->name = $validated['name'];
        $visitor->purpose = $validated['purpose'];
        $visitor->contact = $validated['contact'] ?? null;
        $visitor->id_proof = $validated['id_proof'] ?? null;
        $visitor->visit_to = $validated['visit_to'] ?? null;
        $visitor->related_to = $validated['related_to'] ?? null;
        $visitor->no_of_pepple = $validated['no_of_pepple'] ?? 1;
        $visitor->date = $validated['date'];
        $visitor->in_time = $validated['in_time'] ?? null;
        $visitor->out_time = $validated['out_time'] ?? null;
        $visitor->note = $validated['note'] ?? null;

        $visitor->save();

        return redirect()->back()->with('success', 'Visitor updated successfully!');
    }

    public function delete($id)
    {
        $visitor = VisitorBook::findOrFail($id);

        // Remove image if stored locally
        if (!empty($visitor->image) && strpos($visitor->image, '/uploads/') === 0) {
            $oldPath = public_path(ltrim($visitor->image, '/'));
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $visitor->delete();

        return redirect()->back()->with('success', 'Visitor deleted successfully!');
    }
}
