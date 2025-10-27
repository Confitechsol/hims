<?php

namespace App\Http\Controllers;

use App\Models\MedicineCategory;
use App\Models\MedicineGroup;
use App\Models\Pharmacy;
use App\Models\PharmacyCompany;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PharmacyController extends Controller
{
    /**
     * Display a listing of medicines
     */
    public function index()
    {
        $medicines = Pharmacy::with(['medicineCategory', 'company', 'medicineGroup', 'unitRelation'])
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.pharmacy.index', compact('medicines'));
    }

    /**
     * Show the form for creating a new medicine
     */
    public function create()
    {
        $categories = MedicineCategory::all();
        $companies = PharmacyCompany::all();
        $groups = MedicineGroup::all();
        $units = Unit::all();

        return view('admin.pharmacy.create', compact('categories', 'companies', 'groups', 'units'));
    }

    /**
     * Store a newly created medicine
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'medicine_name' => 'required|string|max:200',
            'medicine_category_id' => 'required|exists:medicine_categories,id',
            'medicine_company' => 'nullable|exists:pharmacy_company,id',
            'medicine_composition' => 'nullable|string|max:100',
            'medicine_group' => 'nullable|exists:medicine_group,id',
            'unit' => 'nullable|exists:unit,id',
            'min_level' => 'nullable|string|max:50',
            'reorder_level' => 'nullable|string|max:50',
            'gst_percentage' => 'nullable|numeric|min:0|max:100',
            'unit_packing' => 'nullable|string|max:50',
            'rack_number' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'medicine_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'nullable|in:yes,no',
        ]);

        try {
            DB::beginTransaction();

            // Handle image upload
            if ($request->hasFile('medicine_image')) {
                $imagePath = $request->file('medicine_image')->store('medicine_images', 'public');
                $validated['medicine_image'] = $imagePath;
            }

            // Set default active status
            $validated['is_active'] = $validated['is_active'] ?? 'yes';

            $medicine = Pharmacy::create($validated);

            DB::commit();

            return redirect()->route('pharmacy.index')
                ->with('success', 'Medicine added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to add medicine: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified medicine
     */
    public function show($id)
    {
        $medicine = Pharmacy::with(['medicineCategory', 'company', 'medicineGroup', 'unitRelation', 'batches'])
            ->findOrFail($id);

        return view('admin.pharmacy.show', compact('medicine'));
    }

    /**
     * Show the form for editing the specified medicine
     */
    public function edit($id)
    {
        $medicine = Pharmacy::findOrFail($id);
        $categories = MedicineCategory::all();
        $companies = PharmacyCompany::all();
        $groups = MedicineGroup::all();
        $units = Unit::all();

        return view('admin.pharmacy.edit', compact('medicine', 'categories', 'companies', 'groups', 'units'));
    }

    /**
     * Update the specified medicine
     */
    public function update(Request $request, $id)
    {
        $medicine = Pharmacy::findOrFail($id);

        $validated = $request->validate([
            'medicine_name' => 'required|string|max:200',
            'medicine_category_id' => 'required|exists:medicine_categories,id',
            'medicine_company' => 'nullable|exists:pharmacy_company,id',
            'medicine_composition' => 'nullable|string|max:100',
            'medicine_group' => 'nullable|exists:medicine_group,id',
            'unit' => 'nullable|exists:unit,id',
            'min_level' => 'nullable|string|max:50',
            'reorder_level' => 'nullable|string|max:50',
            'gst_percentage' => 'nullable|numeric|min:0|max:100',
            'unit_packing' => 'nullable|string|max:50',
            'rack_number' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'medicine_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'nullable|in:yes,no',
        ]);

        try {
            DB::beginTransaction();

            // Handle image upload
            if ($request->hasFile('medicine_image')) {
                // Delete old image
                if ($medicine->medicine_image) {
                    Storage::disk('public')->delete($medicine->medicine_image);
                }
                
                $imagePath = $request->file('medicine_image')->store('medicine_images', 'public');
                $validated['medicine_image'] = $imagePath;
            }

            $medicine->update($validated);

            DB::commit();

            return redirect()->route('pharmacy.index')
                ->with('success', 'Medicine updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update medicine: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified medicine
     */
    public function destroy($id)
    {
        try {
            $medicine = Pharmacy::findOrFail($id);

            // Delete image if exists
            if ($medicine->medicine_image) {
                Storage::disk('public')->delete($medicine->medicine_image);
            }

            $medicine->delete();

            return redirect()->route('pharmacy.index')
                ->with('success', 'Medicine deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete medicine: ' . $e->getMessage());
        }
    }

    /**
     * Get medicines list for AJAX
     */
    public function getMedicines(Request $request)
    {
        $search = $request->input('search');

        $medicines = Pharmacy::with(['medicineCategory', 'company', 'batches'])
            ->when($search, function ($query) use ($search) {
                $query->where('medicine_name', 'like', "%{$search}%");
            })
            ->active()
            ->get();

        return response()->json($medicines);
    }

    /**
     * Get medicine stock info
     */
    public function getStockInfo($id)
    {
        $medicine = Pharmacy::with(['batches' => function ($query) {
            $query->available()->orderBy('expiry', 'asc');
        }])->findOrFail($id);

        $totalQuantity = $medicine->batches->sum('available_quantity');
        $batches = $medicine->batches;

        return response()->json([
            'medicine' => $medicine,
            'total_quantity' => $totalQuantity,
            'batches' => $batches,
            'is_below_min_level' => $medicine->isBelowMinLevel(),
            'needs_reorder' => $medicine->needsReorder(),
        ]);
    }

    /**
     * Show medicines below min level
     */
    public function belowMinLevel()
    {
        $medicines = Pharmacy::with(['medicineCategory', 'company', 'batches'])
            ->whereHas('batches')
            ->get()
            ->filter(function ($medicine) {
                return $medicine->isBelowMinLevel();
            });

        return view('admin.pharmacy.below-min-level', compact('medicines'));
    }

    /**
     * Show medicines that need reordering
     */
    public function needsReorder()
    {
        $medicines = Pharmacy::with(['medicineCategory', 'company', 'batches'])
            ->whereHas('batches')
            ->get()
            ->filter(function ($medicine) {
                return $medicine->needsReorder();
            });

        return view('admin.pharmacy.needs-reorder', compact('medicines'));
    }

    /**
     * Import medicines from CSV
     */
    public function import(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('admin.pharmacy.import');
        }

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        try {
            $file = $request->file('csv_file');
            $path = $file->getRealPath();
            $rows = array_map('str_getcsv', file($path));
            $header = array_map('strtolower', array_map('trim', $rows[0]));

            unset($rows[0]); // Remove header

            DB::beginTransaction();

            foreach ($rows as $row) {
                $row = array_map('trim', $row);
                
                Pharmacy::create([
                    'medicine_name' => $row[0],
                    'medicine_category_id' => $row[1],
                    'medicine_company' => $row[2] ?? null,
                    'medicine_composition' => $row[3] ?? null,
                    'medicine_group' => $row[4] ?? null,
                    'unit' => $row[5] ?? null,
                    'min_level' => $row[6] ?? null,
                    'reorder_level' => $row[7] ?? null,
                    'gst_percentage' => $row[8] ?? null,
                    'unit_packing' => $row[9] ?? null,
                    'rack_number' => $row[10] ?? null,
                    'is_active' => 'yes',
                ]);
            }

            DB::commit();

            return redirect()->route('pharmacy.index')
                ->with('success', 'Medicines imported successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to import medicines: ' . $e->getMessage());
        }
    }
}

