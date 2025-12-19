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

    public function importBirth()
    {
       

        return view('admin.birthordeath.importbirth');
    }
    public function exportPathologyTestExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Pathology Test');

        $organisations = Organisation::all();       // TPA list
        $categories    = PathologyCategory::all();
        $chargeCats    = ChargeCategory::all();
        $charges       = Charge::all();
        
        $parameters    = PathologyParameter::with('unitRelation')->get();

        // ===============================
        // 1. Define Header Columns
        // ===============================

        $headers = [
            'Test Name', 'Short Name', 'Test Type', 'Category Name',
            'Sub Category', 'Method', 'Report Days',
            'Charge Category', 'Charge Name', 'Tax (%)',
            'Standard Charge', 'Amount'
        ];

        // Add TPA Columns
        foreach ($organisations as $org) {
            $headers[] = "TPA {$org->organisation_name} Charge";
            $headers[] = "TPA {$org->organisation_name} Code";
        }

        // Add Parameter Columns (supports multi-rows on import)
        $headers[] = "Parameter ID (comma separated)";
        $headers[] = "Reference Range (optional)";
        $headers[] = "Unit (optional)";

        // Write headers to Excel
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        $sheet->freezePane('A2');

        // ===============================
        // 2. Dropdown Sheet
        // ===============================

        $dropdown = $spreadsheet->createSheet();
        $dropdown->setTitle('DropdownData');

        // Category List
        $row = 1;
        foreach ($categories as $cat) {
            $dropdown->setCellValue("A$row", $cat->category_name);
            $row++;
        }

        // Charge Category List
        $row = 1;
        foreach ($chargeCats as $cc) {
            $dropdown->setCellValue("B$row", $cc->name);
            $row++;
        }

        // Charge Names
        $row = 1;
        foreach ($charges as $charge) {
            $dropdown->setCellValue("C$row", $charge->name);
            $row++;
        }

        // Parameters
        $row = 1;
        foreach ($parameters as $param) {
            $dropdown->setCellValue("D$row", "{$param->id} - {$param->parameter_name}");
            $row++;
        }

        // ===============================
        // 3. Apply Dropdowns in main sheet
        // ===============================

        // Category Name dropdown
        $this->setDropdown($sheet, 'D2:D500', 'DropdownData', 'A', count($categories));

        // Charge Category dropdown
        $this->setDropdown($sheet, 'H2:H500', 'DropdownData', 'B', count($chargeCats));

        // Charge Name dropdown
        $this->setDropdown($sheet, 'I2:I500', 'DropdownData', 'C', count($charges));

        // Parameter dropdown
        $this->setDropdown($sheet, 'A2:A500', 'DropdownData', 'D', count($parameters));

        // ===============================
        // 4. Final Excel Output
        // ===============================

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="PathologyTestTemplate.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }



}
