<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\GstMaster;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

class GstMasterController extends Controller
{
    public function index()
    {
        $gstMasters = GstMaster::orderBy('id', 'desc')->get();
        return view('admin.setup.gst_master', compact('gstMasters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:gst_master,code',
            'description' => 'required|string',
            'gst_rate' => 'required|numeric|min:0|max:100',
        ]);

        GstMaster::create([
            'code' => $request->code,
            'description' => $request->description,
            'gst_rate' => $request->gst_rate,
        ]);

        return redirect()->back()->with('success', 'GST Master created successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:gst_master,id',
            'code' => 'required|string|max:50|unique:gst_master,code,' . $request->id,
            'description' => 'required|string',
            'gst_rate' => 'required|numeric|min:0|max:100',
        ]);

        $gstMaster = GstMaster::findOrFail($request->id);
        $gstMaster->code = $request->code;
        $gstMaster->description = $request->description;
        $gstMaster->gst_rate = $request->gst_rate;
        $gstMaster->save();

        return redirect()->back()->with('success', 'GST Master updated successfully!');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:gst_master,id',
        ]);

        $gstMaster = GstMaster::findOrFail($request->id);
        $gstMaster->delete();

        return redirect()->back()->with('success', 'GST Master deleted successfully!');
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            $file = $request->file('csv_file');
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            DB::beginTransaction();

            $imported = 0;
            $skipped = 0;

            foreach ($rows as $index => $row) {
                if ($index == 1) continue; // Skip header row

                // Skip empty rows
                if (empty($row['A']) && empty($row['B'])) {
                    continue;
                }

                $code = trim($row['A']);
                $description = trim($row['B']);
                $gstRate = trim($row['C']);

                // Validate required fields
                if (empty($code) || empty($description) || empty($gstRate)) {
                    $skipped++;
                    continue;
                }

                // Check if code already exists
                if (GstMaster::where('code', $code)->exists()) {
                    $skipped++;
                    continue;
                }

                // Validate GST rate
                $gstRate = (float) $gstRate;
                if ($gstRate < 0 || $gstRate > 100) {
                    $skipped++;
                    continue;
                }

                GstMaster::create([
                    'code' => $code,
                    'description' => $description,
                    'gst_rate' => $gstRate,
                ]);

                $imported++;
            }

            DB::commit();

            return redirect()->back()->with('success', "CSV imported successfully! {$imported} records imported, {$skipped} records skipped.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error importing CSV: ' . $e->getMessage());
        }
    }

    public function exportCsv()
    {
        $gstMasters = GstMaster::orderBy('id', 'desc')->get();

        $filename = 'gst_master_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($gstMasters) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers (excluding Action column)
            fputcsv($file, ['Code', 'Description', 'GST Rate (%)']);

            // Add data rows
            foreach ($gstMasters as $gstMaster) {
                fputcsv($file, [
                    $gstMaster->code,
                    $gstMaster->description,
                    number_format($gstMaster->gst_rate, 2)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadSampleCsv()
    {
        $filename = 'gst_master_sample.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['Code', 'Description', 'GST Rate (%)']);

            // Add sample data
            fputcsv($file, ['GST001', 'Standard GST Rate', '18.00']);
            fputcsv($file, ['GST002', 'Reduced GST Rate', '5.00']);
            fputcsv($file, ['GST003', 'Zero GST Rate', '0.00']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
