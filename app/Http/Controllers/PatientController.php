<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Organisation;
use App\Models\Patient;
use App\Models\BloodBankProduct;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class PatientController extends Controller
{
    public function index()
    {
        $bloodGroups = BloodBankProduct::where('is_blood_group', 1)->get();
        $patients = Patient::get();
        return view('admin.setup.patient', compact('patients','bloodGroups'));
    }
    public function store(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'guardian_name'      => 'nullable|string|max:255',
            'gender'             => 'required|in:Male,Female',
            'birth_date'         => 'nullable|date',
            'age.year'           => 'nullable|integer|min:0',
            'age.month'          => 'nullable|integer|min:0|max:11',
            'age.day'            => 'nullable|integer|min:0|max:31',
            'blood_group'        => 'nullable|in:1,2,3,4,5,6',
            'marital_status'     => 'nullable|in:Single,Married,Widowed,Separated,Not Specified',
            'file'               => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'phone'              => 'nullable|string|max:20',
            'email'              => 'nullable|email|max:255',
            'height'             => 'nullable|string|max:255',
            'weight'             => 'nullable|string|max:255',
            'temperature'        => 'nullable|string|max:255',
            'address'            => 'nullable|string|max:500',
            'remarks'            => 'nullable|string|max:500',
            'allergies'          => 'nullable|string|max:255',
            'tpa'                => 'nullable|in:1,2,3,4,5',
            'tpa_id'             => 'nullable|string|max:100',
            'tpa_validity'       => 'nullable|string|max:100',
            'national_id_number' => 'nullable|string|max:50',
        ]);

        // Handle file upload
        $photoPath = null;
        if ($request->hasFile('file')) {
            $photoPath = $request->file('file')->store('patient_photos', 'public');
        }

        // Save the patient
        $patient = Patient::create([
            'patient_name'          => $validated['name'],
            'guardian_name'         => $validated['guardian_name'] ?? null,
            'gender'                => $validated['gender'],
            'dob'                   => $validated['birth_date'] ?? null,
            'age'                   => $validated['age']['year'] ?? null,
            'month'                 => $validated['age']['month'] ?? null,
            'day'                   => $validated['age']['day'] ?? null,
            'blood_group'           => $validated['blood_group'] ?? null,
            'marital_status'        => $validated['marital_status'] ?? null,
            'image'                 => $photoPath,
            'mobileno'              => $validated['phone'] ?? null,
            'email'                 => $validated['email'] ?? null,
            'address'               => $validated['address'] ?? null,
            'note'                  => $validated['remarks'] ?? null,
            'known_allergies'       => $validated['allergies'] ?? null,
            'organisation_id'       => $validated['tpa'] ?? null,
            'tpa_code'              => $validated['tpa_id'] ?? null,
            'tpa_validity'          => $validated['tpa_validity'] ?? null,
            'identification_number' => $validated['national_id_number'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Patient saved successfully!');
    }
    public function edit($id)
    {
        
        $patient = Patient::with('organisation')->find($id);
        return view('admin.setup.edit-patient', compact('patient'));

    }
    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'guardian_name'      => 'nullable|string|max:255',
            'gender'             => 'required|in:Male,Female',
            'birth_date'         => 'nullable|date',
            'age.year'           => 'nullable|integer|min:0',
            'age.month'          => 'nullable|integer|min:0|max:11',
            'age.day'            => 'nullable|integer|min:0|max:31',
            'blood_group'        => 'nullable|in:1,2,3,4,5,6',
            'marital_status'     => 'nullable|in:Single,Married,Widowed,Separated,Not Specified',
            'file'               => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'phone'              => 'nullable|string|max:20',
            'email'              => 'nullable|email|max:255',
            'address'            => 'nullable|string|max:500',
            'remarks'            => 'nullable|string|max:500',
            'allergies'          => 'nullable|string|max:255',
            'tpa'                => 'nullable|in:1,2,3,4,5',
            'tpa_id'             => 'nullable|string|max:100',
            'tpa_validity'       => 'nullable|string|max:100',
            'national_id_number' => 'nullable|string|max:50',
        ]);

        // Handle file upload
        $photoPath = $patient->image; // keep old image

        if ($request->hasFile('file')) {
            // Delete old image if exists
            if ($patient->image && \Storage::disk('public')->exists($patient->image)) {
                \Storage::disk('public')->delete($patient->image);
            }

            // Upload new image
            $photoPath = $request->file('file')->store('patient_photos', 'public');
        }

        // Update patient
        $patient->update([
            'patient_name'          => $validated['name'],
            'guardian_name'         => $validated['guardian_name'] ?? null,
            'gender'                => $validated['gender'],
            'dob'                   => $validated['birth_date'] ?? null,
            'age'                   => $validated['age']['year'] ?? null,
            'month'                 => $validated['age']['month'] ?? null,
            'day'                   => $validated['age']['day'] ?? null,
            'blood_group'           => $validated['blood_group'] ?? null,
            'marital_status'        => $validated['marital_status'] ?? null,
            'image'                 => $photoPath,
            'mobileno'              => $validated['phone'] ?? null,
            'email'                 => $validated['email'] ?? null,
            'address'               => $validated['address'] ?? null,
            'note'                  => $validated['remarks'] ?? null,
            'known_allergies'       => $validated['allergies'] ?? null,
            'organisation_id'       => $validated['tpa'] ?? null,
            'insurance_id'          => $validated['tpa_id'] ?? null,
            'insurance_validity'    => $validated['tpa_validity'] ?? null,
            'identification_number' => $validated['national_id_number'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Patient updated successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('selected_patients');

        if (! $ids || count($ids) === 0) {
            return redirect()->back()->with('error', 'No patients selected.');
        }

        Patient::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', 'Selected patients deleted successfully.');
    }

    public function import(Request $request)
    {
         $bloodgroups = BloodBankProduct::all(); 
            return view('admin.setup.import_patient', compact('bloodgroups'));
        
        
    }
    public function bulkImport(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $file = $request->file('csv_file');

        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        // Loop through rows starting from row 2
        foreach ($rows as $index => $row) {

            if ($index == 1) continue; // Skip header row

            // Skip empty rows
            if (empty($row['A']) && empty($row['B'])) {
                continue;
            }

            // Mapping Excel Columns → Variables
            $patientName        = trim($row['A']);
            $gender             = trim($row['B']);
            $bloodGroup         = trim($row['C']);
            $ageYear            = trim($row['D']);
            $ageMonth           = trim($row['E']);
            $ageDay             = trim($row['F']);
            $maritalStatus      = trim($row['G']);
            $phone              = trim($row['H']);
            $email              = trim($row['I']);
            $address            = trim($row['J']);
            $remarks            = trim($row['K']);
            $allergies          = trim($row['L']);
            $identification     = trim($row['M']);
            $tpaId              = trim($row['N']);
            $tpaValidity        = $this->formatExcelDate(trim($row['O']));

            // Insert into patient table
            try {
                Patient::create([
                    'patient_name'          => $patientName,
                    'gender'                => $gender,
                    'blood_group'           => $bloodGroup,
                    'age'                   => $ageYear,
                    'month'                 => $ageMonth,
                    'day'                   => $ageDay,
                    'marital_status'        => $maritalStatus,
                    'mobileno'              => $phone,
                    'email'                 => $email,
                    'address'               => $address,
                    'note'                  => $remarks,
                    'known_allergies'       => $allergies,
                    'identification_number' => $identification,
                    'insurance_id'          => $tpaId,
                    'insurance_validity'    => $tpaValidity,
                ]);
            } catch (\Exception $e) {
                return back()->with('error', "Error on Row {$index}: " . $e->getMessage());
            }
        }

        return back()->with('success', 'Patients imported successfully!');
    }
    public function exportPatientsExcel()
    {
        // Dropdown values (static or DB-based)
        $genderList = '"Male,Female,Other"';
        $maritalList = '"Single,Married,Divorced,Widowed"';
        $bloodGroups = BloodBankProduct::where('is_blood_group', 1)->pluck('name')->toArray();
        $tpas = Organisation::pluck('organisation_name')->toArray();
        $bloodList = '"' . implode(",", $bloodGroups) . '"';
        $tpaList = '"' . implode(",", $tpas) . '"';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Column headings (match your import format)
        $headers = [
            "Patient", "Gender", "Blood Group", "Age(Year)", "Age(Month)", "Age(Day)",
            "Marital Status", "Phone", "Email", "Address", "Remarks", "Known Allergies",
            "Height","Weight","Temperature","Identification Number", "TPA", "TPA ID", "TPA Validity"
        ];

        // Add header row
        $col = "A";
        foreach ($headers as $header) {
            $sheet->setCellValue($col.'1', $header);
            $col++;
        }

        // Freeze header row
        $sheet->freezePane('A2');

        // Apply dropdowns
        // Gender → Column B
        $this->addDropdown($sheet, "B2:B500", $genderList);

        // Blood Group → Column C
        $this->addDropdown($sheet, "C2:C500", $bloodList);

        // Marital Status → Column G
        $this->addDropdown($sheet, "G2:G500", $maritalList);

        // Marital Status → Column N
        $this->addDropdown($sheet, "N2:N500", $tpaList);

        // Lock header row
        foreach (range('A', 'Z') as $col) {
            $sheet->getStyle($col.'1')->getProtection()->setLocked(true);
        }

        // Unlock data rows
        $sheet->getStyle('A2:Z500')->getProtection()->setLocked(false);

        // Protect sheet
        $sheet->getProtection()->setSheet(true);
        $sheet->getProtection()->setSort(true);
        $sheet->getProtection()->setInsertRows(true);
        $sheet->getProtection()->setFormatCells(true);

        // Export file
        $writer = new Xlsx($spreadsheet);
        $fileName = "Patients.xlsx";

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    private function addDropdown($sheet, $cellRange, $formulaList)
    {
        // Apply dropdown to each cell in the range
        foreach ($sheet->getCellCollection()->getCoordinates() as $coord) {}

        [$start, $end] = explode(":", $cellRange);

        // Convert start and end rows
        preg_match('/([A-Z]+)([0-9]+)/', $start, $startMatch);
        preg_match('/([A-Z]+)([0-9]+)/', $end, $endMatch);

        $column = $startMatch[1];
        $startRow = (int)$startMatch[2];
        $endRow   = (int)$endMatch[2];

        for ($row = $startRow; $row <= $endRow; $row++) {

            $cell = $sheet->getCell($column . $row);
            $validation = $cell->getDataValidation();

            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);

            // CORRECT: Formula must be exactly => "item1,item2,item3"
            $validation->setFormula1($formulaList);
        }
    }
    public function importStaffExcel(Request $request)
    {
        
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);
        
        $file = $request->file('file');

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        // Skip header row (row 1)
        foreach ($rows as $index => $row) {
            if ($index == 1) continue;

            if (empty($row['A']) && empty($row['B'])) {
                continue; // skip empty rows
            }

            // Mapping Excel columns → Variables
            $employeeId         = trim($row['A']);
            $firstName          = trim($row['B']);
            $lastName           = trim($row['C']);
            $departmentName     = trim($row['D']);
            $designationName    = trim($row['E']);
            $specialistName     = trim($row['F']);
            $specialization     = trim($row['G']);
            $qualification      = trim($row['H']);
            $workExperience     = trim($row['I']);
            $fatherName         = trim($row['J']);
            $motherName         = trim($row['K']);
            $contactNumber      = trim($row['L']);
            $emergencyContact   = trim($row['M']);
            $email              = trim($row['N']);
            $dob                = $this->formatExcelDate(trim($row['O']));
            $maritalStatus      = trim($row['P']);
            $dateJoining        = $this->formatExcelDate(trim($row['Q']));
            $dateLeaving        = $this->formatExcelDate(trim($row['R']));
            $localAddress       = trim($row['S']);
            $permanentAddress   = trim($row['T']);
            $gender             = trim($row['U']);
            $bloodGroupName     = trim($row['V']);
            $identification     = trim($row['W']);
            $pan                = trim($row['X']);
            $rolesName          = trim($row['Y']);
            $remarks            = trim($row['Z']);

            // Convert Names → IDs
            $departmentId  = Department::where('department_name', $departmentName)->value('id');
            $designationId = StaffDesignation::where('designation', $designationName)->value('id');
            $bloodGroupId  = BloodBankProduct::where('name', $bloodGroupName)->value('id');
            $specialistId  = Specialist::where('specialist_name', $specialistName)->value('id');
            $rolesId       = Role::where('name', $rolesName)->value('id');


            // Validate existence
            if (!$departmentId) {
                return back()->with('error', "Department '{$departmentName}' not found (Row {$index}).");
            }

            if (!$designationId) {
                return back()->with('error', "Designation '{$designationName}' not found (Row {$index}).");
            }

            if (!$bloodGroupId && $bloodGroupName != "") {
                return back()->with('error', "Blood Group '{$bloodGroupName}' not found (Row {$index}).");
            }

            if (!$specialistId && $specialistName != "") {
                return back()->with('error', "specialist '{$specialistName}' not found (Row {$index}).");
            }

            // Insert into staff table
            Staff::create([
                'employee_id'               => $employeeId,
                'name'                      => $firstName,
                'surname'                   => $lastName,
                'department_id'             => $departmentId,
                'designation_id'            => $designationId,
                'specialist'                => $specialistId,
                'specialization'            => $specialization,
                'qualification'             => $qualification,
                'work_exp'                  => $workExperience,
                'father_name'               => $fatherName,
                'mother_name'               => $motherName,
                'contact_no'            => $contactNumber,
                'emergency_contact_no'  => $emergencyContact,
                'email'                     => $email,
                'dob'                       => $dob,
                'marital_status'            => $maritalStatus,
                'date_of_joining'           => $dateJoining,
                'date_of_leaving'           => $dateLeaving,
                'local_address'             => $localAddress,
                'permanent_address'         => $permanentAddress,
                'gender'                    => $gender,
                'blood_group'               => $bloodGroupId,
                'identification_number'     => $identification,
                'pan'                       => $pan,
                'roles'                     => $rolesId,
                'remarks'                   => $remarks,
                'password'                  => '123456',
                'user_id'                   => 0,
                'is_active'                 => 1,
            ]);
        }

        return back()->with('success', 'Staff Excel imported successfully!');
    }
    private function formatExcelDate($value)
    {
        if (empty($value)) {
            return null;
        }

        // If Excel date is numeric (serial number)
        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
        }

        // If date is already a string like 9/21/2008 or 21-09-2008
        return date('Y-m-d', strtotime($value));
    }
    public function organizations()
    {
        $organizations = Organisation::all();
        return response()->json($organizations, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }
    public function getPatients()
    {
        $patients = Patient::with('organisation', 'bloodGroup')->get();
        // dd($patients);
        return response()->json($patients, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }

}