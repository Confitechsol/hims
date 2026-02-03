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
    public function medicineMasters()
    {
        try {
            //code...
            return response()->json(MedMaster::select('id', 'name')->orderBy('name')->get());
        } catch (\Exception $th) {

            return response()->json(['success' => false, 'message' => $th->getMessage()]);
            //throw $th;
        }
    }

    public function getCategories()
    {
        return response()->json(MedicineCategory::select('id', 'medicine_category')->get());
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
        return response()->json(DoseInterval::select('id', 'name')->get());
    }

    public function getDurations()
    {
        return response()->json(DoseDuration::select('id', 'name')->get());
    }
}
