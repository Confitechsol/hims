<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PathologyCategory;
use App\Models\ChargeCategory;
use App\Models\ChargeTypeModule;
use App\Models\ChargeUnit;
use App\Models\Charge;
use App\Models\Pathology;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Illuminate\Support\Facades\Auth;

class ExcelImportController extends Controller
{
    public function importPathology()
{
    $pathology     = Pathology::with(['category', 'charge', 'chargeCategory'])->get();
    $categories    = PathologyCategory::all();
    $charges       = Charge::all();
    $chargeUnits   = ChargeUnit::all();
    $chargeTypes   = ChargeTypeModule::all();

    return view('admin.pathology.test.importTest', compact(
        'pathology',
        'categories',
        'charges',
        'chargeUnits',
        'chargeTypes'
    ));
}

    public function importPathologyExcel(Request $request)
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
                'contact_no'                => $contactNumber,
                'emergency_contact_no'      => $emergencyContact,
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

    public function exportPathologyTestExcel()
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Tests');

    $headers = [
        "Test Code", "Test Name", "Category", "Sample Type",
        "Method", "Normal Range", "Unit", "Rate", "Remarks"
    ];

    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue("$col"."1", $header);
        $col++;
    }

    $sheet->freezePane('A2');

    // Dropdown Sheet
    $dropdownSheet = $spreadsheet->createSheet();
    $dropdownSheet->setTitle('DropdownData');

    $columns = [
        'A' => TestCategory::pluck('category_name')->filter()->values()->all(),
        'B' => SampleType::pluck('sample_name')->filter()->values()->all(),
        'C' => Unit::pluck('unit_name')->filter()->values()->all(),
    ];

    foreach ($columns as $col => $values) {
        $row = 1;
        foreach ($values as $val) {
            $dropdownSheet->setCellValue($col . $row, $val);
            $row++;
        }
    }

    $this->setDropdown($sheet, 'C2:C500', 'DropdownData', 'A', count($columns['A']));
    $this->setDropdown($sheet, 'D2:D500', 'DropdownData', 'B', count($columns['B']));
    $this->setDropdown($sheet, 'G2:G500', 'DropdownData', 'C', count($columns['C']));

    $writer = new Xlsx($spreadsheet);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="PathologyTest.xlsx"');
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
