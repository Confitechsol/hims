<?php

namespace App\Http\Controllers;
use App\Models\BirthReport;
use Illuminate\Http\Request;

class BirthController extends Controller
{

    private function encodeImage($content)
    {
        if ($content) {
            // Detect MIME type
            $finfo    = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_buffer($finfo, $content);
            finfo_close($finfo);
            return 'data:' . $mimeType . ';base64,' . base64_encode($content);
        }
        return null;
    }
 
    private function processImage($content)
    {
        return file_get_contents($content->getRealPath());
    }

    function index(Request $request){
     $perPage = intval($request->input('perPage', 5));
    if ($perPage <= 0) {
        $perPage = 5;
    }
    $query = BirthReport::with(['patient']);
    if ($request->has('search')) {
        $search_term = $request->search;
        $query->where(function ($q) use ($search_term) {
            $q->where('child_name', 'like', "%{$search_term}%")
                ->orWhere('father_name', 'like', "%{$search_term}%")
                ->orWhereHas('patient', function ($sub) use ($search_term) {
                    $sub->where('patient_name', 'like', "%{$search_term}%");
                });
        });
    }
    $birthReports = $query->paginate($perPage);
    //return response()->json($birthReports, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);~
     return view('admin.birthordeath.index', compact('birthReports'));
    }

   public function create(Request $request)
{
    $validated = $request->validate([
        'child_name' => 'required|string|max:255',
        'gender' => 'required|string|max:10',
        'weight' => 'required|string|max:20',
        'birth_date' => 'required|date',
        'contact_person_phone' => 'required|string|max:15',
        'address' => 'nullable|string|max:255',
        'caseId' => 'nullable|string|max:50',
        'mother_name' => 'required|string|max:255',
        'father_name' => 'required|string|max:255',
        'report' => 'required|string|max:255',
        'baby_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'mother_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'father_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'report_image' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        'icd_code' => 'required|string|max:255',
    ]);

  
   if ($request->hasFile('baby_image')) {
            $baby_image = $this->processImage($request->file('baby_image'));
        } else {
            // Keep the existing image from DB
            $baby_image = null;
        }

     if ($request->hasFile('mother_image')) {
            $mother_image = $this->processImage($request->file('mother_image'));
        } else {
            // Keep the existing image from DB
            $mother_image = null;
        }

     if ($request->hasFile('father_image')) {
            $father_image = $this->processImage($request->file('father_image'));
        } else {
            // Keep the existing image from DB
            $father_image = null;
        }

     if ($request->hasFile('report_image')) {
            $report_image = $this->processImage($request->file('report_image'));
        } else {
            // Keep the existing image from DB
            $report_image = null;
        }

    BirthReport::create([
        'child_name' => $validated['child_name'],
        'gender' => $validated['gender'],
        'weight' => $validated['weight'],
        'birth_date' => $validated['birth_date'],
        'contact' => $validated['contact_person_phone'] ?? null,
        'address' => $validated['address'] ?? null,
        'case_reference_id' => $validated['caseId'] ?? null,
        'mother_name' => $validated['mother_name'],
        'father_name' => $validated['father_name'],
        'birth_report' => $validated['report'] ?? null,
        'child_pic' => $baby_image,
        'mother_pic' => $mother_image,
        'father_pic' => $father_image,
        'document' => $report_image,
        'is_active' => 1,
        'icd_code' => $validated['icd_code'] ?? null,
    ]);

    return redirect()->back()->with('success', 'Birth Record added successfully!');
}

public function delete($id)
{
    $birth = BirthReport::findOrFail($id);
    $birth->delete();

    return redirect()->back()->with('success', 'Birth record deleted successfully!');
}

public function update(Request $request, $id)
{
    $birth = BirthReport::findOrFail($id);

    $validated = $request->validate([
        'child_name' => 'required|string|max:255',
        'gender' => 'required|string|max:10',
        'weight' => 'required|string|max:20',
        'birth_date' => 'required|date',
        'contact_person_phone' => 'required|string|max:15',
        'address' => 'nullable|string|max:255',
        'caseId' => 'nullable|string|max:50',
        'mother_name' => 'required|string|max:255',
        'father_name' => 'required|string|max:255',
        'report' => 'required|string|max:255',
        'baby_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'mother_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'father_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'report_image' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        'icd_code' => 'required|string|max:255',
    ]);

    // Only process and replace images if new files were uploaded
    if ($request->hasFile('baby_image')) {
        $birth->child_pic = $this->processImage($request->file('baby_image'));
    }

    if ($request->hasFile('mother_image')) {
        $birth->mother_pic = $this->processImage($request->file('mother_image'));
    }

    if ($request->hasFile('father_image')) {
        $birth->father_pic = $this->processImage($request->file('father_image'));
    }

    if ($request->hasFile('report_image')) {
        $birth->document = $this->processImage($request->file('report_image'));
    }

    $birth->child_name = $validated['child_name'];
    $birth->gender = $validated['gender'];
    $birth->weight = $validated['weight'];
    $birth->birth_date = $validated['birth_date'];
    $birth->contact = $validated['contact_person_phone'] ?? $birth->contact;
    $birth->address = $validated['address'] ?? $birth->address;
    $birth->case_reference_id = $validated['caseId'] ?? $birth->case_reference_id;
    $birth->mother_name = $validated['mother_name'];
    $birth->father_name = $validated['father_name'];
    $birth->birth_report = $validated['report'] ?? $birth->birth_report;
    $birth->icd_code = $validated['icd_code'] ?? $birth->icd_code;

    $birth->save();

    return redirect()->back()->with('success', 'Birth record updated successfully!');
}



}
