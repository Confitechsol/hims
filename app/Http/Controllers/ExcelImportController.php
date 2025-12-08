<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organisation;
use App\Models\PathologyCategory;
use App\Models\ChargeCategory;
use App\Models\ChargeTypeModule;
use App\Models\ChargeUnit;
use App\Models\Charge;
use App\Models\Pathology;
use App\Models\PathologyParameter;
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

    try {
        $file = $request->file('file');

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray(null, true, true, true);

        foreach ($rows as $index => $row) {

            // Skip header row
            if ($index == 1) continue;

            // Row empty? Skip
            if (empty($row['A']) && empty($row['B'])) continue;

            // Excel → Variables
            $testName        = trim($row['A']);
            $categoryName    = trim($row['B']);
            $chargeName      = trim($row['C']);
            $amount          = trim($row['D']);
            $chargeTypeName  = trim($row['E']);
            $unitName        = trim($row['F']);
            $description     = trim($row['G']);
            $reportDays      = trim($row['H']);
            $reportDate      = $this->formatExcelDate(trim($row['I']));

            // Name → IDs
            $categoryId   = PathologyCategory::where('category_name', $categoryName)->value('id');
            $chargeId     = Charge::where('charge_name', $chargeName)->value('id');
            $chargeTypeId = ChargeTypeModule::where('name', $chargeTypeName)->value('id');
            $unitId       = ChargeUnit::where('unit_name', $unitName)->value('id');

            // Skip if test name already exists
            if (Pathology::where('test_name', $testName)->exists()) {
                continue; // or log duplicates
            }

            // Insert Pathology Test
            Pathology::create([
                'hospital_id'     => Auth::user()->hospital_id,
                'branch_id'       => Auth::user()->branch_id ?? null,
                'test_name'       => $testName,
                'category_id'     => $categoryId,
                'charge_id'       => $chargeId,
                'charge_type_id'  => $chargeTypeId,
                'charge_unit_id'  => $unitId,
                'amount'          => $amount,
                'description'     => $description,
                'report_days'     => $reportDays,
                'report_date'     => $reportDate,
                'is_active'       => 1,
            ]);
        }

        return back()->with('success', 'Pathology Excel imported successfully!');

    } catch (\Exception $e) {
        return back()->with('error', 'Error importing file: ' . $e->getMessage());
    }
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
            $dropdown->setCellValue("C$row", $charge->charge_name);
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



    // =========================
    // Helper: Apply dropdown
    // =========================
    private function setDropdown($sheet, $cellRange, $sheetName, $columnLetter, $count)
{
    [$start, $end] = explode(':', $cellRange);
    preg_match('/([A-Z]+)([0-9]+)/', $start, $m);

    $startRow = (int)$m[2];
    $column   = $m[1];
    $endRow   = explode($column, $end)[1];

    for ($row = $startRow; $row <= $endRow; $row++) {
        $validation = new DataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setAllowBlank(true);
        $validation->setFormula1("='{$sheetName}'!\${$columnLetter}\$1:\${$columnLetter}\${$count}");

        $sheet->getCell($column . $row)->setDataValidation($validation);
    }
}

   private function formatExcelDate($value)
{
    // 1. Handle empty values
    if ($value === null || $value === '' || trim($value) == '') {
        return null;
    }

    // 2. If value is a DateTime object (PhpSpreadsheet sometimes gives this)
    if ($value instanceof \DateTimeInterface) {
        return $value->format('Y-m-d');
    }

    // 3. Excel serial date (numeric)
    if (is_numeric($value)) {
        try {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)
                   ->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    // 4. Try to parse manually if it's a string date
    try {
        // Replace "/" with "-" for consistent parsing
        $value = str_replace('/', '-', trim($value));

        // Some people write DD-MM-YYYY → strtotime may confuse it on some systems
        $parts = explode('-', $value);

        if (count($parts) === 3 && strlen($parts[2]) === 4) {
            // Detect if DD-MM-YYYY (Indian format)
            if (strlen($parts[0]) <= 2 && strlen($parts[1]) <= 2) {
                // Convert DD-MM-YYYY → YYYY-MM-DD
                return date('Y-m-d', strtotime("$parts[2]-$parts[1]-$parts[0]"));
            }
        }

        // Fallback for any normal date string
        return date('Y-m-d', strtotime($value));
    } catch (\Throwable $e) {
        return null;
    }
}

    
}
