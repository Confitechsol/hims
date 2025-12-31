<?php

namespace App\Http\Controllers;
use App\Models\VisitorsPurpose;
use App\Models\VisitorBook;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\GeneralCall;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

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
    
   
    $purposes = VisitorsPurpose::all();
    
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

    public function phoneCallLog(Request $request)
    {
        $perPage = intval($request->input('perPage', 10));
        if ($perPage <= 0) {
            $perPage = 10;
        }

        // Prefer the `general_call` table if present (singular), otherwise check model table, then fallback to VisitorBook.
        if (DB::getSchemaBuilder()->hasTable('general_call')) {
            $query = DB::table('general_call')->select('*');
            if ($request->has('search')) {
                $search_term = $request->search;
                $query->where(function ($q) use ($search_term) {
                    $q->where('name', 'like', "%{$search_term}%")
                      ->orWhere('contact', 'like', "%{$search_term}%")
                      ->orWhere('purpose', 'like', "%{$search_term}%")
                      ->orWhere('call_type', 'like', "%{$search_term}%");
                });
            }
            $callLogs = $query->orderBy('date', 'desc')->paginate($perPage);
        } elseif (class_exists(GeneralCall::class) && DB::getSchemaBuilder()->hasTable((new GeneralCall())->getTable())) {
            $modelTable = (new GeneralCall())->getTable();
            $query = GeneralCall::query();
            if ($request->has('search')) {
                $search_term = $request->search;
                $query->where(function ($q) use ($search_term) {
                    $q->where('name', 'like', "%{$search_term}%")
                      ->orWhere('contact', 'like', "%{$search_term}%")
                      ->orWhere('description', 'like', "%{$search_term}%")
                      ->orWhere('call_type', 'like', "%{$search_term}%");
                });
            }
            $callLogs = $query->orderBy('date', 'desc')->paginate($perPage);
        } else {
            // Fallback to VisitorBook if `general_call` doesn't exist
            $query = VisitorBook::query();
            if ($request->has('search')) {
                $search_term = $request->search;
                $query->where(function ($q) use ($search_term) {
                    $q->where('name', 'like', "%{$search_term}%")
                      ->orWhere('contact', 'like', "%{$search_term}%")
                      ->orWhere('purpose', 'like', "%{$search_term}%");
                });
            }
            $callLogs = $query->paginate($perPage);
        }

        // Provide purposes to the view so the edit modal's Purpose select can be populated
        $purposes = VisitorsPurpose::all();

        return view('admin.front-office.phone-call-log', compact('callLogs', 'purposes'));
    }

    public function createCallLog(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:50',
            'date' => 'required|date',
            'next_follow_up_date' => 'nullable|date',
            'call_type' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:2000',
            'call_duration' => 'nullable|integer|min:0',
        ]);

        $table = DB::getSchemaBuilder()->hasTable('general_call') ? 'general_call' : (class_exists(GeneralCall::class) ? (new GeneralCall())->getTable() : null);

        if ($table) {
            // Build insert data and include optional columns if they exist in the table
            $insertData = [
                'name' => $validated['name'],
                'contact' => $validated['contact'],
                'date' => $validated['date'],
                'follow_up_date' => $validated['next_follow_up_date'] ?? null,
                'call_type' => $validated['call_type'] ?? null,
                'description' => $validated['note'] ?? null,
            ];

            // hospital/branch context if applicable
            $hospitalId = auth()->user()->hospital_id ?? session('hospital_id', '1');
            $branchId = auth()->user()->branch_id ?? session('branch_id', '1');
            if (Schema::hasColumn($table, 'hospital_id')) {
                $insertData['hospital_id'] = $hospitalId;
            }
            if (Schema::hasColumn($table, 'branch_id')) {
                $insertData['branch_id'] = $branchId;
            }

            // Ensure call_type isn't null if the DB requires it
            if (empty($insertData['call_type']) && Schema::hasColumn($table, 'call_type')) {
                $insertData['call_type'] = $validated['call_type'] ?? 'Unknown';
            }

            // If the table has a call_duration column, provide a safe default ('0') when not supplied
            if (Schema::hasColumn($table, 'call_duration')) {
                $insertData['call_duration'] = (string)($validated['call_duration'] ?? $request->input('call_duration') ?? '0');
            }

            // If created_at column is present and the DB doesn't default it, set it to now()
            if (Schema::hasColumn($table, 'created_at') && !array_key_exists('created_at', $insertData)) {
                $insertData['created_at'] = now();
            }

            DB::table($table)->insert($insertData);

            return redirect()->back()->with('success', 'Call log added successfully!');
        }

        return redirect()->back()->with('error', 'Unable to add call log (table missing)');
    }
}
