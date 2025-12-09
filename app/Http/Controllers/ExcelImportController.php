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
use App\Models\Radio;
use App\Models\RadiologyCategory;
use App\Models\PathologyParameter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

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
            $file        = $request->file('file');
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray(null, true, true, true);

            DB::beginTransaction();

            foreach ($rows as $index => $row) {

                // Skip Header
                if ($index == 1) continue;

                // Skip Blank Row
                if (empty($row['A'])) continue;

                // Excel → Variables
                $testName       = trim($row['A']);
                $shortName      = trim($row['B']);
                $testType       = trim($row['C']);
                $categoryName   = trim($row['D']);
                $subCategory    = trim($row['E']);
                $method         = trim($row['F']);
                $reportDays     = trim($row['G']);
                $chargeCategory = trim($row['H']);
                $chargeName     = trim($row['I']);
                $standardCharge = trim($row['J']);
                $amount         = trim($row['K']);
                $description    = trim($row['L']); // optional field
                $unitName       = trim($row['M']); // if required

                // Convert Names → IDs
                //dd($categoryName, $chargeName );
                $categoryId = PathologyCategory::where('category_name', $categoryName)->value('id');
                $chargeId   = Charge::where('name', $chargeName)->value('id');
                $chargeCategoryId = ChargeCategory::where('name', $chargeCategory)->value('id');
                $unitId = ChargeUnit::where('unit', $unitName)->value('id');
               // dd($categoryId, $chargeId );

                // Skip duplicate test names
                if (Pathology::where('test_name', $testName)->exists()) {
                    Log::warning("Duplicate test skipped: $testName");
                    continue;
                }

                // Prepare payload similar to store() logic
                $createData = [
                    'test_name'             => $testName,
                    'short_name'            => $shortName,
                    'test_type'             => $testType ?? '',
                    'pathology_category_id' => $categoryId,
                    'sub_category'          => $subCategory ?? '',
                    'method'                => $method ?? '',
                    'report_days'           => is_numeric($reportDays) ? $reportDays : 0,
                    'charge_id'             => $chargeId,
                ];

                // Only add optional fields if exist in DB
                if (Schema::hasColumn('pathology', 'standard_charge')) {
                    $createData['standard_charge'] = $standardCharge ?: 0;
                }

                if (Schema::hasColumn('pathology', 'amount')) {
                    $createData['amount'] = $amount ?: 0;
                }

                if (Schema::hasColumn('pathology', 'charge_category_id')) {
                    $createData['charge_category_id'] = $chargeCategoryId;
                }

                if (Schema::hasColumn('pathology', 'unit')) {
                    $createData['unit'] = $unitId;
                }

                // Create pathology test
                $pathology = Pathology::create($createData);

                Log::info('Imported pathology test:', ['id' => $pathology->id]);

                /**
                 * -------------------------------
                 *  CREATE / UPDATE TPA CHARGES
                 * -------------------------------
                 */
                $user = Auth::user();
                $hospitalId = $user->hospital_id ?? null;
                $branchId   = $user->branch_id ?? null;

                if ($hospitalId) {
                    $organisations = Organisation::all();

                    foreach ($organisations as $organisation) {

                        // Excel does not include TPA charges → set amount = standard charge
                        $orgCharge = floatval($standardCharge ?? $amount ?? 0);

                        // If TPA charge is empty skip
                        if ($orgCharge <= 0) continue;

                        $existing = OrganisationsCharge::where('charge_id', $chargeId)
                            ->where('org_id', $organisation->id)
                            ->first();

                        if ($existing) {
                            $existing->org_charge = $orgCharge;
                            $existing->save();
                        } else {
                            $tpaChargeData = [
                                'charge_id'  => $chargeId,
                                'org_id'     => $organisation->id,
                                'org_charge' => $orgCharge,
                            ];

                            if (Schema::hasColumn('organisations_charges', 'hospital_id'))
                                $tpaChargeData['hospital_id'] = $hospitalId;

                            if (Schema::hasColumn('organisations_charges', 'branch_id'))
                                $tpaChargeData['branch_id'] = $branchId;

                            OrganisationsCharge::create($tpaChargeData);
                        }
                    }
                }
            }

            DB::commit();

            return back()->with('success', 'Pathology Excel imported successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Import Error: " . $e->getMessage());
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

    public function importRadiology()
{
    $radiology     = Radio::with(['radiologyCategory', 'charge', 'chargeCategory'])->get();
    $categories    = RadiologyCategory::all();
    $charges       = Charge::all();
    $chargeUnits   = ChargeUnit::all();
    $chargeTypes   = ChargeTypeModule::all();

    return view('admin.radiology.test.importTest', compact(
        'radiology',
        'categories',
        'charges',
        'chargeUnits',
        'chargeTypes'
    ));

    
}
public function exportRadiologyTestExcel()
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Radiology Test');

    $organisations = Organisation::all();
    $categories    = RadiologyCategory::all();
    $chargeCats    = ChargeCategory::all();
    $charges       = Charge::all();

    $headers = [
        'Test Name', 'Short Name', 'Test Type', 'Category Name',
        'Sub Category', 'Method', 'Report Days',
        'Charge Category', 'Charge Name', 'Tax (%)',
        'Standard Charge', 'Amount', 'Unit (optional)'
    ];

    foreach ($organisations as $org) {
        $headers[] = "TPA {$org->organisation_name} Charge";
        $headers[] = "TPA {$org->organisation_name} Code";
    }

    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', $header);
        $col++;
    }

    // Dropdown sheet
    $dropdown = $spreadsheet->createSheet();
    $dropdown->setTitle('DropdownData');

    $row = 1;
    foreach ($categories as $cat) {
        $dropdown->setCellValue("A$row", $cat->name);
        $row++;
    }

    $row = 1;
    foreach ($chargeCats as $cc) {
        $dropdown->setCellValue("B$row", $cc->name);
        $row++;
    }

    $row = 1;
    foreach ($charges as $charge) {
        $dropdown->setCellValue("C$row", $charge->name);
        $row++;
    }

    // Apply dropdowns
    $this->setDropdown($sheet, 'D2:D500', 'DropdownData', 'A', count($categories));
    $this->setDropdown($sheet, 'H2:H500', 'DropdownData', 'B', count($chargeCats));
    $this->setDropdown($sheet, 'I2:I500', 'DropdownData', 'C', count($charges));

    $writer = new Xlsx($spreadsheet);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="RadiologyTestTemplate.xlsx"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
}

public function importRadiologyExcel(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    try {
        $file        = $request->file('file');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray(null, true, true, true);

        DB::beginTransaction();

        foreach ($rows as $index => $row) {

            if ($index == 1) continue;      // skip header
            if (empty($row['A'])) continue; // skip blank

            // Excel columns
            $testName       = trim($row['A']);
            $shortName      = trim($row['B']);
            $testType       = trim($row['C']);
            $categoryName   = trim($row['D']);
            $subCategory    = trim($row['E']);
            $method         = trim($row['F']);
            $reportDays     = trim($row['G']);
            $chargeCategory = trim($row['H']);
            $chargeName     = trim($row['I']);
            $standardCharge = trim($row['J']);
            $amount         = trim($row['K']);
            $unitName       = trim($row['L']);

            // Convert names → IDs
            $categoryId        = RadiologyCategory::where('name', $categoryName)->value('id');
            $chargeId          = Charge::where('name', $chargeName)->value('id');
            $chargeCategoryId  = ChargeCategory::where('name', $chargeCategory)->value('id');
            $unitId            = ChargeUnit::where('unit', $unitName)->value('id');

            // Skip duplicates
            if (Radio::where('test_name', $testName)->exists()) {
                continue;
            }

            // Prepare Insert
            $createData = [
                'test_name'             => $testName,
                'short_name'            => $shortName,
                'test_type'             => $testType ?? '',
                'radiology_category_id' => $categoryId,
                'sub_category'          => $subCategory ?? '',
                'method'                => $method ?? '',
                'report_days'           => is_numeric($reportDays) ? $reportDays : 0,
                'charge_id'             => $chargeId,
            ];

            if (Schema::hasColumn('radiology', 'standard_charge')) {
                $createData['standard_charge'] = $standardCharge ?: 0;
            }

            if (Schema::hasColumn('radiology', 'amount')) {
                $createData['amount'] = $amount ?: 0;
            }

            if (Schema::hasColumn('radiology', 'charge_category_id')) {
                $createData['charge_category_id'] = $chargeCategoryId;
            }

            if (Schema::hasColumn('radiology', 'unit')) {
                $createData['unit'] = $unitId;
            }

            $radiology = Radio::create($createData);

            /**
             * Add/Update TPA Charges
             */
            $user = Auth::user();
            $hospitalId = $user->hospital_id ?? null;
            $branchId   = $user->branch_id ?? null;

            if ($hospitalId) {
                $organisations = Organisation::all();

                foreach ($organisations as $organisation) {

                    $orgCharge = floatval($standardCharge ?? $amount ?? 0);
                    if ($orgCharge <= 0) continue;

                    $existing = OrganisationsCharge::where('charge_id', $chargeId)
                        ->where('org_id', $organisation->id)
                        ->first();

                    if ($existing) {
                        $existing->org_charge = $orgCharge;
                        $existing->save();
                    } else {
                        $tpaData = [
                            'charge_id'  => $chargeId,
                            'org_id'     => $organisation->id,
                            'org_charge' => $orgCharge,
                        ];

                        if (Schema::hasColumn('organisations_charges', 'hospital_id'))
                            $tpaData['hospital_id'] = $hospitalId;

                        if (Schema::hasColumn('organisations_charges', 'branch_id'))
                            $tpaData['branch_id'] = $branchId;

                        OrganisationsCharge::create($tpaData);
                    }
                }
            }
        }

        DB::commit();
        return back()->with('success', 'Radiology Excel imported successfully!');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error importing file: ' . $e->getMessage());
    }
}




    // =========================
    // Helper: Apply dropdown
    // =========================
    private function setDropdown($sheet, $cellRange, $dropdownSheet, $column, $count)
{
    $validation = $sheet->getCell(explode(':', $cellRange)[0])->getDataValidation();
    $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
    $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
    $validation->setAllowBlank(true);
    $validation->setShowInputMessage(true);
    $validation->setShowErrorMessage(true);
    $validation->setShowDropDown(true);

    $formula = "'$dropdownSheet'!\$".$column."\$1:\$".$column."\$".$count;
    $validation->setFormula1($formula);

    foreach ($sheet->getCellCollection() as $cell) {
        if ($cell->isInRange($cellRange)) {
            $cell->setDataValidation(clone $validation);
        }
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
