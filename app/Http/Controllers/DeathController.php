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




}
