<?php
namespace App\Http\Controllers;

use App\Models\DoseDuration;
use App\Models\DoseInterval;
use App\Models\MedicineCategory;
use App\Models\MedicineDosage;
use App\Models\MedMaster;
use App\Models\Pharmacy;

class MedicineController extends Controller
{
    public function test()
    {
        return response()->json(['status' => 'ok', 'message' => 'Controller is working']);
    }

    public function medicineMasters()
    {
        try {
            //code...
            return response()->json(MedMaster::select('id', 'name', 'medicine_type')->orderBy('name')->get());
        } catch (\Exception $th) {

            return response()->json(['success' => false, 'message' => $th->getMessage()]);
            //throw $th;
        }
    }

    public function getCategories()
    {
        try {
            // Check if model exists
            if (! class_exists(MedicineCategory::class)) {
                throw new \Exception('MedicineCategory model not found');
            }

            $categories = MedicineCategory::select('id', 'medicine_category')->get();

            if ($categories === null) {
                throw new \Exception('Query returned null');
            }

            return response()->json($categories);
        } catch (\Throwable $e) {
            \Log::error('Error fetching medicine categories: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'type'    => get_class($e),
            ], 500);
        }
    }

    public function getMedicines($categoryId)
    {
        return response()->json(Pharmacy::where('medicine_category_id', $categoryId)->with('medicineCategory.dosages')->get());
    }

    public function getDoses($categoryId)
    {

        return response()->json(MedicineDosage::with('unit')->where('medicine_category_id', $categoryId)->get());
    }
    public function getAllDoses()
    {
        return response()->json(MedicineDosage::with('unit')->get());
    }

    public function getIntervals()
    {
        try {
            if (! class_exists(DoseInterval::class)) {
                throw new \Exception('DoseInterval model not found');
            }

            $intervals = DoseInterval::select('id', 'name')->get();

            if ($intervals === null) {
                throw new \Exception('Query returned null');
            }

            return response()->json($intervals);
        } catch (\Throwable $e) {
            \Log::error('Error fetching dose intervals: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'type'    => get_class($e),
            ], 500);
        }
    }

    public function getDurations()
    {
        try {
            if (! class_exists(DoseDuration::class)) {
                throw new \Exception('DoseDuration model not found');
            }

            $durations = DoseDuration::select('id', 'name')->get();

            if ($durations === null) {
                throw new \Exception('Query returned null');
            }

            return response()->json($durations);
        } catch (\Throwable $e) {
            \Log::error('Error fetching dose durations: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'type'    => get_class($e),
            ], 500);
        }
    }
}