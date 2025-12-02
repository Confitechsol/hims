<?php

namespace App\Http\Controllers;
use App\Models\DeathReport;
use Illuminate\Http\Request;
use App\Models\Patient;
class DeathController extends Controller
{
    function index(Request $request){     
    $query = DeathReport::with(['patient'])->get();
     $perPage = intval($request->input('perPage', 5));
     if ($perPage <= 0) {
        $perPage = 5;
    }
    $query = DeathReport::with(['patient']);
    if ($request->has('search')) {
        $search_term = $request->search;
        $query->where(function ($q) use ($search_term) {
            $q->where('guardian_name', 'like', "%{$search_term}%")
                ->orWhere('case_reference_id', 'like', "%{$search_term}%")
                ->orWhereHas('patient', function ($sub) use ($search_term) {
                    $sub->where('patient_name', 'like', "%{$search_term}%");
                });
        });
    }
    $deathReports = $query->paginate($perPage);
    //    return response()->json($deathReports , 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
      return view('admin.birthordeath.indexdeath', compact('deathReports'));
    }


    public function create(Request $request)
{
  
    $validated = $request->validate([
        'case_reference_id'   => 'nullable|string|max:255',
        'patient_name'   => 'required|string|max:255',
        'patient_id' => 'required|integer',
        'death_date'     => 'required|date',
        'guardian_name'  => 'required|string|max:255',
        'attachment_name' => 'nullable|string|max:255',
        'attachment'     => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:5120',
    ]);

    

    // Convert file to BLOB
    if ($request->hasFile('attachment')) {
        $attachment = file_get_contents($request->file('attachment')->getRealPath());
    } else {
        $attachment = null;
    }
  //  dd($validated);
    DeathReport::create([
        
        'case_reference_id' => $validated['case_reference_id'],
        'patient_id'       => $validated['patient_id'],
        'patient_name'  =>  $validated['patient_name'],
        'death_date'    =>  $validated['death_date'],
        'guardian_name' => $validated['guardian_name'],
        'attachment_name'  => $validated['attachment_name'], 
        'attachment'    => $attachment,   // ✅ Store BLOB
        'is_active'     => 1
    ]);

    return redirect()->back()->with('success', 'Death Record Added Successfully');
}

 public function getPatient($id)
    {
        // Fetch patient by ID
        $patient = Patient::where('id',$id)->firstOrFail();

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Patient not found'
            ], 404);
        }

        // Optional: Include death report details
        $deathReport = DeathReport::where('patient_id', $id)->first();

        return response()->json([

            'success' => true,
            'patient' => $patient,
            'death_report' => $deathReport
        ],200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }

    public function delete($id)
{
    $death = DeathReport::findOrFail($id);
    $death->delete();

    return redirect()->back()->with('success', 'Death record deleted successfully!');
}

    /**
     * Update the specified death report.
     * Route: PUT /death/update/{id}
     */
    public function update(Request $request, $id)
    {
        $death = DeathReport::findOrFail($id);

        $validated = $request->validate([
            'case_id' => 'nullable|string|max:255',
            'patient_id' => 'required|integer',
            'patient_name' => 'nullable|string|max:255',
            'death_date' => 'required|date',
            'guardian_name' => 'required|string|max:255',
            'report' => 'nullable|string|max:255', // mapped to attachment_name
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:5120',
        ]);

        // Update simple fields — note view uses 'case_id' and 'report' names for edit form
        $death->case_reference_id = $validated['case_id'] ?? $death->case_reference_id;
        $death->patient_id = $validated['patient_id'] ?? $death->patient_id;
        if (!empty($validated['patient_name'])) {
            $death->patient_name = $validated['patient_name'];
        }
        $death->death_date = $validated['death_date'];
        $death->guardian_name = $validated['guardian_name'];

        // attachment name / report
        if (!empty($validated['report'])) {
            $death->attachment_name = $validated['report'];
        }

        // If a new file uploaded, convert to BLOB and store (existing code stores BLOB)
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $death->attachment = file_get_contents($file->getRealPath());
            // If no explicit report name provided, use original filename
            if (empty($death->attachment_name)) {
                $death->attachment_name = $file->getClientOriginalName();
            }
        }

        $death->save();

        return redirect()->back()->with('success', 'Death record updated successfully!');
    }




}
