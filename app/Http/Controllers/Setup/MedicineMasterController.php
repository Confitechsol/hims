<?php
namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\MedMaster;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicineMasterController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = (int) $request->input('perPage', 10);
            if ($perPage <= 0) {
                $perPage = 10;
            }

            $search = $request->input('search');
            $query  = MedMaster::orderBy('id', 'asc');

            if (! empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            }
            $medicines = $query->paginate($perPage);
            return view('admin.medicine-master.index', compact('medicines'));
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to Fetch Medicine Data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified Medicine
     */
    public function create()
    {
        try {
            return view('admin.medicine-master.create');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to Open: ' . $e->getMessage());
        }
    }
    public function importMedicine()
    {
        try {
            return view('admin.medicine-master.importMedicine');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to Open: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'medicine_name.*'      => 'required|string',
                'medicine_price.*'     => 'nullable|numeric',
                'manufacturer_name.*'  => 'nullable|string',
                'pack_size_label.*'    => 'nullable|string',
                'short_composition1.*' => 'nullable|string',
                'short_composition2.*' => 'nullable|string',
            ]);

            foreach ($request->medicine_name as $key => $name) {

                MedMaster::create([
                    'name'               => $name,
                    'price'              => $request->medicine_price[$key] ?? 0,
                    'manufacturer_name'  => $request->manufacturer_name[$key] ?? "NA",
                    'pack_size_label'    => $request->pack_size_label[$key] ?? "NA",
                    'short_composition1' => $request->short_composition1[$key] ?? "NA",
                    'short_composition2' => $request->short_composition2[$key] ?? "NA",
                ]);
            }

            return redirect()->route('medicine-master')
                ->with('success', 'Medicines inserted successfully.');

        } catch (Exception $e) {

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to Insert Medicines: ' . $e->getMessage());
        }
    }
    // public function store(Request $request)
    // {
    //     try {
    //         // dd($request->all());
    //         $request->validate([
    //             'medicine_name'      => 'required|string',
    //             'medicine_price'     => 'nullable|numeric',
    //             'manufacturer_name'  => 'nullable|string',
    //             'pack_size_label'    => 'nullable|string',
    //             'short_composition1' => 'nullable|string',
    //             'short_composition2' => 'nullable|string',
    //         ]);

    //         MedMaster::create([
    //             'name'               => $request->medicine_name,
    //             'price'              => $request->medicine_price ?? 0,
    //             'manufacturer_name'  => $request->manufacturer_name ?? "NA",
    //             'pack_size_label'    => $request->pack_size_label ?? "NA",
    //             'short_composition1' => $request->short_composition1 ?? "NA",
    //             'short_composition2' => $request->short_composition2 ?? "NA",
    //         ]);
    //         return redirect()->route('medicine-master')->with('success', 'Medicine Inserted successfully.');
    //     } catch (Exception $e) {
    //         return redirect()->back()->withInput()->with('error', 'Failed to Insert Medicine: ' . $e->getMessage());
    //     }
    // }

    /**
     * Show the form for editing the specified Medicine
     */
    public function edit($id)
    {
        try {
            $medicine = MedMaster::findOrFail($id);
            return view('admin.medicine-master.edit', compact('medicine'));
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to Fetch Medicine Data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $medicine = MedMaster::findOrFail($id);

            // dd($request->all());
            $request->validate([
                'medicine_name'      => 'required|string',
                'medicine_price'     => 'nullable|numeric',
                'manufacturer_name'  => 'nullable|string',
                'pack_size_label'    => 'nullable|string',
                'short_composition1' => 'nullable|string',
                'short_composition2' => 'nullable|string',
            ]);

            $medicine->update([
                'name'               => $request->medicine_name,
                'price'              => $request->medicine_price ?? 0,
                'manufacturer_name'  => $request->manufacturer_name ?? "NA",
                'pack_size_label'    => $request->pack_size_label ?? "NA",
                'short_composition1' => $request->short_composition1 ?? "NA",
                'short_composition2' => $request->short_composition2 ?? "NA",
            ]);
            return redirect()->route('medicine-master')->with('success', 'Medicine updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to Update Medicine: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified medicine
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $medicine = MedMaster::findOrFail($id);
            $medicine->delete();

            DB::commit();

            return redirect()->route('medicine-master')
                ->with('success', 'Medicine deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error deleting Medicine: ' . $e->getMessage());
        }
    }
}
