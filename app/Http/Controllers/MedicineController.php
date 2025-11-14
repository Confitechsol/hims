<?php
namespace App\Http\Controllers;

use App\Models\DoseDuration;
use App\Models\DoseInterval;
use App\Models\MedicineCategory;
use App\Models\MedicineDosage;
use App\Models\Pharmacy;

class MedicineController extends Controller
{
    public function getCategories()
    {
        return response()->json(MedicineCategory::select('id', 'medicine_category')->get());
    }

    public function getMedicines($categoryId)
    {
        return response()->json(Pharmacy::where('medicine_category_id', $categoryId)->select('id', 'medicine_name')->get());
    }

    public function getDoses($categoryId)
    {

        return response()->json(MedicineDosage::with('unit')->where('medicine_category_id', $categoryId)->get());
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
