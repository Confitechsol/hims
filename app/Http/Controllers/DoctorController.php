<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Role;
use App\Models\Specialist;
use App\Models\Department;
use App\Models\StaffDesignation;
use App\Models\BloodBankProduct;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('department')->get();
        return view('admin.doctor.doctors', compact("doctors"));
    }
    public function create()
    {
        $roles = Role::all();
        $designations = StaffDesignation::all();
        $departments = Department::all();
        $specialists = Specialist::all();    
        $bloodgroups = BloodBankProduct::all(); 
        return view('admin.doctor.addDoctor', compact('roles', 'designations', 'departments', 'specialists','bloodgroups'));
    }
    public function getSpecialists($id)
    {
        $specialists = Specialist::where('department_id', $id)->get();
        return response()->json($specialists);
    }
    public function edit($id) {
        return view('admin.Doctor.addDoctor', [
            'doctor' => Doctor::findOrFail($id),
            'roles' => Role::all(),
            'designations' => StaffDesignation::all(),
            'departments' => Department::all(),
            'specialists' => Specialist::all(),
            'bloodgroups' => BloodBankProduct::all(),
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required',
            'name' => 'required',
            'email' => 'required|email',
           
        ]);

        //dd($request->all());

        $doctor = new Doctor();
        $doctor->hospital_id     = Auth::user()->hospital_id;
        $doctor->branch_id       = Auth::user()->branch_id ?? null;
        $doctor->doctor_id       = $request->doctor_id;
        $doctor->registration_no = $request->registration_no;
        $doctor->role_id         = $request->role;
        $doctor->staff_designation_id = $request->designation;
        $doctor->department_id = $request->department;
        $doctor->specialist = $request->specialist ?? null;
        $doctor->name = $request->name;
        $doctor->surname = $request->surname;
        $doctor->father_name = $request->father_name;
        $doctor->mother_name = $request->mother_name;
        $doctor->gender = $request->gender;
        $doctor->marital_status = $request->marital_status;
        $doctor->blood_group = $request->blood_group;
        $doctor->dob = $request->dob;
        $doctor->date_of_joining = $request->date_of_joining;
        $doctor->contact_no = $request->contactno;
        $doctor->emergency_contact_no = $request->emgcontactno;
        $doctor->email = $request->email;
        $doctor->local_address = $request->address;
        $doctor->permanent_address = $request->permanent_address;
        $doctor->qualification = $request->qualification;
        $doctor->work_exp = $request->work_exp;
        $doctor->specialization = $request->specialization;
        $doctor->note = $request->note;
        $doctor->pan_number = $request->pan_number;
        $doctor->identification_number = $request->identification_number;
        $doctor->password = '123456';
        $doctor->user_id = '123456';
        $doctor->is_active = '1';

        if ($request->file('file')) {
            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/Doctor'), $filename);
            $doctor->photo = $filename;
        }

        $doctor->save();

        return redirect()->route('doctors.index')->with('success', 'Doctor details added successfully!');

    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'doctor_id' => 'required',
            'name' => 'required',
            'email' => 'required|email',
        ]);

        // Find Doctor record
        $Doctor = Doctor::findOrFail($id);

        $Doctor->doctor_id = $request->doctor_id;
        $Doctor->role_id = $request->role;
        $Doctor->staff_designation_id = $request->designation;
        $Doctor->department_id = $request->department;
        $Doctor->specialist = $request->specialist ?? null;
        $Doctor->name = $request->name;
        $Doctor->surname = $request->surname;
        $Doctor->father_name = $request->father_name;
        $Doctor->mother_name = $request->mother_name;
        $Doctor->gender = $request->gender;
        $Doctor->marital_status = $request->marital_status;
        $Doctor->blood_group = $request->blood_group;
        $Doctor->dob = $request->dob;
        $Doctor->date_of_joining = $request->date_of_joining;
        $Doctor->contact_no = $request->contactno;
        $Doctor->emergency_contact_no = $request->emgcontactno;
        $Doctor->email = $request->email;
        $Doctor->local_address = $request->address;
        $Doctor->permanent_address = $request->permanent_address;
        $Doctor->qualification = $request->qualification;
        $Doctor->work_exp = $request->work_exp;
        $Doctor->specialization = $request->specialization;
        $Doctor->note = $request->note;
        $Doctor->pan_number = $request->pan_number;
        $Doctor->identification_number = $request->identification_number;
        $Doctor->local_identification_number = $request->local_identification_number;
        // Do not change password or user_id during update unless required
        $Doctor->is_active = $request->is_active ?? $Doctor->is_active;

        // Handle photo upload
        if ($request->file('file')) {
            // Delete old photo
            if ($Doctor->photo && file_exists(public_path('uploads/Doctor/' . $Doctor->photo))) {
                unlink(public_path('uploads/Doctor/' . $Doctor->photo));
            }

            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/Doctor'), $filename);
            $Doctor->photo = $filename;
        }

        $Doctor->save();

        return redirect()->route('doctors.index')->with('success', 'Doctor details added successfully!');

    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('selected_Doctors');
        //dd($ids);

        if (! $ids || count($ids) === 0) {
            return redirect()->back()->with('error', 'No Doctors selected.');
        }

        // Soft delete
        Doctor::whereIn('id', $ids)->update(['deleted_at' => now()]);

        return redirect()->back()->with('success', 'Selected Doctors are deleted successfully.');
    }
    
    public function importDoctor()
    {
        $roles = Role::all();
        $designations = StaffDesignation::all();
        $departments = Department::pluck('department_name')->toArray();
        $specialists = Specialist::all();    
        $bloodgroups = BloodBankProduct::all(); 
        return view('admin.Doctor.importDoctor', compact('roles', 'designations', 'departments', 'specialists','bloodgroups'));
    }
    public function exportDoctorExcel()
    {
        
        $departments = Department::pluck('department_name')->toArray();
        $designations = StaffDesignation::pluck('designation')->toArray();
        $specialists = Specialist::pluck('specialist_name')->toArray(); 
        $bloodgroups = BloodBankProduct::where('is_blood_group', 1)
                    ->pluck('name')
                    ->toArray();
        $roles = Role::pluck('name')->toArray();


        // Dropdown values
        $departmentList = '"' . implode(",", $departments) . '"';
        $designationList = '"' . implode(",", $designations) . '"';
        $specialistsList = '"' . implode(",", $specialists) . '"';
        $maritalList = '"Single,Married,Divorced,Widowed"';
        $genderList = '"Male,Female,Other"';
        $bloodList = '"' . implode(",", $bloodgroups) . '"';
        $roleList = '"' . implode(",", $roles) . '"';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Column headings
        $headers = [
            "Doctor Id", "First Name", "Last Name","Department", "Designation",
            "Specialist","Specialization","Qualification",
            "Work Experience", "Fathers Name", "Mothers Name",
            "Contact Number", "Emergency Contact Number", "Email", "DOB",
            "Marital Status", "Date of Joining", "Date of Leaving", "Local Address",
            "Permanent Address", "Gender", "Blood Group", "Addhar Number",
            "PAN", "Role",  "Remarks","Doctor Registration No.",
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
        // Department → Column D
        $this->addDropdown($sheet, "D2:D500", $departmentList);

        // Department → Column E
        $this->addDropdown($sheet, "E2:E500", $designationList);

        // Department → Column F
        $this->addDropdown($sheet, "F2:F500", $specialistsList);

        // Marital Status → Column P
        $this->addDropdown($sheet, "P2:P500", $maritalList);

        // Gender → Column U
        $this->addDropdown($sheet, "U2:U500", $genderList);

        // Blood Group → Column V
        $this->addDropdown($sheet, "V2:V500", $bloodList);

        // Blood Group → Column Y
        $this->addDropdown($sheet, "Y2:Y500", $roleList);

        // Auto column width
        // Lock header row
        // Lock header row
        foreach (range('A', 'AA') as $col) {
            $sheet->getStyle($col.'1')->getProtection()->setLocked(true);
        }

        // Unlock data rows
        $sheet->getStyle('A2:AA500')->getProtection()->setLocked(false);

        // Protect the sheet
        $sheet->getProtection()->setSheet(true);
        $sheet->getProtection()->setSort(true);
        $sheet->getProtection()->setInsertRows(true);
        $sheet->getProtection()->setFormatCells(true);

        // Return file download
        $writer = new Xlsx($spreadsheet);
        $fileName = "Doctors.xlsx";

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
    public function importDoctorExcel(Request $request)
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
            $doctorId           = trim($row['A']);
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
            $registrationNo     = trim($row['AA']);

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

            // Insert into Doctor table
            Doctor::create([
                'hospital_id'               => Auth::user()->hospital_id,
                'branch_id'                 => Auth::user()->branch_id ?? null,
                'doctor_id'                 => $doctorId,
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
                'registration_no'           => $registrationNo,
                'password'                  => '123456',
                'user_id'                   => 0,
                'is_active'                 => 1,
                
            ]);
        }

        return back()->with('success', 'Doctor Excel imported successfully!');
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
}
