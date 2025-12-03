<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Role;
use App\Models\Specialist;
use App\Models\Department;
use App\Models\StaffDesignation;
use App\Models\BloodBankProduct;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Illuminate\Support\Facades\Auth;




class StaffController extends Controller
{
    public function index()
    {
        $staffs = Staff::with('department')->get();
        return view('admin.staff.staffs', compact("staffs"));
    }
    public function create()
    {
        $roles = Role::all();
        $designations = StaffDesignation::all();
        $departments = Department::all();
        $specialists = Specialist::all();    
        $bloodgroups = BloodBankProduct::all(); 
        return view('admin.staff.addStaff', compact('roles', 'designations', 'departments', 'specialists','bloodgroups'));
    }
    public function getSpecialists($id)
    {
        $specialists = Specialist::where('department_id', $id)->get();
        return response()->json($specialists);
    }
    public function edit($id) {
        return view('admin.staff.addStaff', [
            'staff' => Staff::findOrFail($id),
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
            'employee_id' => 'required',
            'name' => 'required',
            'email' => 'required|email',
           
        ]);

        //dd($request->all());

        $staff = new Staff();
        $staff->hospital_id     = Auth::user()->hospital_id;
        $staff->branch_id       = Auth::user()->branch_id ?? null;
        $staff->employee_id     = $request->employee_id;
        $staff->role_id = $request->role;
        $staff->staff_designation_id = $request->designation;
        $staff->department_id = $request->department;
        $staff->specialist = $request->specialist ?? null;
        $staff->name = $request->name;
        $staff->surname = $request->surname;
        $staff->father_name = $request->father_name;
        $staff->mother_name = $request->mother_name;
        $staff->gender = $request->gender;
        $staff->marital_status = $request->marital_status;
        $staff->blood_group = $request->blood_group;
        $staff->dob = $request->dob;
        $staff->date_of_joining = $request->date_of_joining;
        $staff->contact_no = $request->contactno;
        $staff->emergency_contact_no = $request->emgcontactno;
        $staff->email = $request->email;
        $staff->local_address = $request->address;
        $staff->permanent_address = $request->permanent_address;
        $staff->qualification = $request->qualification;
        $staff->work_exp = $request->work_exp;
        $staff->specialization = $request->specialization;
        $staff->note = $request->note;
        $staff->pan_number = $request->pan_number;
        $staff->identification_number = $request->identification_number;
        $staff->local_identification_number = $request->local_identification_number;
        $staff->password = '123456';
        $staff->user_id = '123456';
        $staff->is_active = '1';

        if ($request->file('file')) {
            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/staff'), $filename);
            $staff->photo = $filename;
        }

        $staff->save();

        return back()->with('success', 'Staff details added successfully!');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required',
            'name' => 'required',
            'email' => 'required|email',
        ]);

        // Find staff record
        $staff = Staff::findOrFail($id);

        $staff->employee_id = $request->employee_id;
        $staff->role_id = $request->role;
        $staff->staff_designation_id = $request->designation;
        $staff->department_id = $request->department;
        $staff->specialist = $request->specialist ?? null;
        $staff->name = $request->name;
        $staff->surname = $request->surname;
        $staff->father_name = $request->father_name;
        $staff->mother_name = $request->mother_name;
        $staff->gender = $request->gender;
        $staff->marital_status = $request->marital_status;
        $staff->blood_group = $request->blood_group;
        $staff->dob = $request->dob;
        $staff->date_of_joining = $request->date_of_joining;
        $staff->contact_no = $request->contactno;
        $staff->emergency_contact_no = $request->emgcontactno;
        $staff->email = $request->email;
        $staff->local_address = $request->address;
        $staff->permanent_address = $request->permanent_address;
        $staff->qualification = $request->qualification;
        $staff->work_exp = $request->work_exp;
        $staff->specialization = $request->specialization;
        $staff->note = $request->note;
        $staff->pan_number = $request->pan_number;
        $staff->identification_number = $request->identification_number;
        $staff->local_identification_number = $request->local_identification_number;
        // Do not change password or user_id during update unless required
        $staff->is_active = $request->is_active ?? $staff->is_active;

        // Handle photo upload
        if ($request->file('file')) {
            // Delete old photo
            if ($staff->photo && file_exists(public_path('uploads/staff/' . $staff->photo))) {
                unlink(public_path('uploads/staff/' . $staff->photo));
            }

            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/staff'), $filename);
            $staff->photo = $filename;
        }

        $staff->save();

        return back()->with('success', 'Staff details updated successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('selected_staffs');
        //dd($ids);

        if (! $ids || count($ids) === 0) {
            return redirect()->back()->with('error', 'No Staffs selected.');
        }

        // Soft delete
        Staff::whereIn('id', $ids)->update(['deleted_at' => now()]);

        return redirect()->back()->with('success', 'Selected Staffs are deleted successfully.');
    }
    
    public function importStaff()
    {
        $roles = Role::all();
        $designations = StaffDesignation::all();
        $departments = Department::pluck('department_name')->toArray();
        $specialists = Specialist::all();    
        $bloodgroups = BloodBankProduct::all(); 
        return view('admin.staff.importStaff', compact('roles', 'designations', 'departments', 'specialists','bloodgroups'));
    }
   public function exportStaffExcel()
{
    $spreadsheet = new Spreadsheet();

    // =========================
    // Sheet1: Staffs Template
    // =========================
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Staffs');

    $headers = [
        "Staff Id", "First Name", "Last Name", "Department", "Designation",
        "Specialist", "Specialization", "Qualification", "Work Experience",
        "Fathers Name", "Mothers Name", "Contact Number", "Emergency Contact Number",
        "Email", "DOB", "Marital Status", "Date of Joining", "Date of Leaving",
        "Local Address", "Permanent Address", "Gender", "Blood Group",
        "Aadhar Number", "PAN", "Role", "Remarks", "Staff Registration No.",
    ];

    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', $header);
        $col++;
    }

    $sheet->freezePane('A2'); // freeze header

    // =========================
    // Sheet2: DropdownData
    // =========================
    $dropdownSheet = $spreadsheet->createSheet();
    $dropdownSheet->setTitle('DropdownData');

    $columns = [
        'A' => Department::pluck('department_name')->filter()->values()->all(),
        'B' => StaffDesignation::pluck('designation')->filter()->values()->all(),
        'C' => Specialist::pluck('specialist_name')->filter()->values()->all(),
        'D' => ["Single", "Married", "Divorced", "Widowed"],
        'E' => ["Male", "Female", "Other"],
        'F' => BloodBankProduct::where('is_blood_group', 1)->pluck('name')->filter()->values()->all(),
        'G' => Role::pluck('name')->filter()->values()->all(),
    ];

    foreach ($columns as $col => $values) {
        $rowIndex = 1;
        foreach ($values as $val) {
            $dropdownSheet->setCellValue($col . $rowIndex, $val);
            $rowIndex++;
        }
    }

    // =========================
    // Apply dropdowns in Sheet1
    // =========================
    $this->setDropdown($sheet, 'D2:D500', 'DropdownData', 'A', count($columns['A'])); // Department
    $this->setDropdown($sheet, 'E2:E500', 'DropdownData', 'B', count($columns['B'])); // Designation
    $this->setDropdown($sheet, 'F2:F500', 'DropdownData', 'C', count($columns['C'])); // Specialist
    $this->setDropdown($sheet, 'P2:P500', 'DropdownData', 'D', count($columns['D'])); // Marital Status
    $this->setDropdown($sheet, 'U2:U500', 'DropdownData', 'E', count($columns['E'])); // Gender
    $this->setDropdown($sheet, 'V2:V500', 'DropdownData', 'F', count($columns['F'])); // Blood Group
    $this->setDropdown($sheet, 'Y2:Y500', 'DropdownData', 'G', count($columns['G'])); // Role

    // =========================
    // Output Excel file
    // =========================
    $writer = new Xlsx($spreadsheet);
    $fileName = "Staffs.xlsx";

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$fileName\"");
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
}

// =========================
// Helper: Apply dropdown
// =========================
private function setDropdown($sheet, $cellRange, $sheetName, $columnLetter, $count)
{
    [$start, $end] = explode(':', $cellRange);
    preg_match('/([A-Z]+)([0-9]+)/', $start, $startMatch);
    preg_match('/([A-Z]+)([0-9]+)/', $end, $endMatch);

    $startRow = (int)$startMatch[2];
    $endRow   = (int)$endMatch[2];
    $column   = $startMatch[1];

    for ($row = $startRow; $row <= $endRow; $row++) {
        $cell = $sheet->getCell($column . $row);
        $validation = new DataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setAllowBlank(true);
        $validation->setShowDropDown(true);
        $validation->setFormula1("='{$sheetName}'!\${$columnLetter}\$1:\${$columnLetter}\${$count}");
        $cell->setDataValidation($validation);
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
                'hospital_id'               => Auth::user()->hospital_id,
                'branch_id'                 => Auth::user()->branch_id ?? null,
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

    

}
