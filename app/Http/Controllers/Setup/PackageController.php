<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\PackageCharge;
use App\Models\PackageExclude;
use App\Models\ChargeCategory;
use App\Models\Charge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = Package::with('charges')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.setup.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $chargeCategories = ChargeCategory::with('chargeType')->get();
        $charges = Charge::with('category')->get();
        $package = new Package(); // form expects $package for old() fallbacks
        
        return view('admin.setup.packages.form', compact('package', 'chargeCategories', 'charges'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'account_head' => 'nullable|string|max:100',
            'gst_amount' => 'nullable|numeric|min:0',
            'package_rate' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();

            $package = Package::create([
                // Legacy integer column `packageId` without default; keep it in a safe small range
                // This column is not used as primary key (the `id` column is), so we can use a fixed value.
                'packageId' => 1,
                'hospital_id' => $user->hospital_id ?? null,
                'branch_id' => $user->branch_id ?? null,
                'name' => $request->name,
                'account_head' => $request->account_head ?? null,
                'gst_amount' => $request->gst_amount ?? 0,
                'package_rate' => (float) $request->package_rate,
                'description' => $request->description ?? null,
                'status' => $request->status ?? 'active',
                'is_active' => ($request->status ?? 'active') === 'active',
                'created_by' => $user->id ?? null,
            ]);

            // Save package charges (normalize from request; charges array may be missing or have empty values)
            $charges = is_array($request->input('charges')) ? $request->input('charges') : [];
            $displayOrder = 0;
            foreach ($charges as $chargeData) {
                $chargeType = isset($chargeData['charge_type']) ? trim((string) $chargeData['charge_type']) : '';
                $amountRaw = $chargeData['amount'] ?? null;
                $amount = $amountRaw === null || $amountRaw === '' ? 0 : (float) $amountRaw;

                // Skip row only if both type and amount are empty
                if ($chargeType === '' && $amount == 0) {
                    continue;
                }

                PackageCharge::create([
                    'package_id' => $package->id,
                    'charge_type' => $chargeType !== '' ? $chargeType : 'Other',
                    'charge_category_id' => $chargeData['charge_category_id'] ?? null,
                    'charge_id' => $chargeData['charge_id'] ?? null,
                    'amount' => $amount,
                    'is_percentage' => !empty($chargeData['is_percentage']),
                    'display_order' => $displayOrder++,
                ]);
            }

            // Save package excludes
            if ($request->has('excludes') && is_array($request->excludes)) {
                foreach ($request->excludes as $excludeData) {
                    PackageExclude::create([
                        'package_id' => $package->id,
                        'charge_category_id' => $excludeData['charge_category_id'] ?? null,
                        'charge_id' => $excludeData['charge_id'] ?? null,
                        'description' => $excludeData['description'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('packages.index')
                ->with('success', 'Package created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating package: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating package: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $package = Package::with(['charges', 'excludes', 'ipdPackages.ipd.patient'])
            ->findOrFail($id);
        
        return view('admin.setup.packages.show', compact('package'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $package = Package::with(['charges', 'excludes'])->findOrFail($id);
        $chargeCategories = ChargeCategory::with('chargeType')->get();
        $charges = Charge::with('category')->get();
        
        return view('admin.setup.packages.form', compact('package', 'chargeCategories', 'charges'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'account_head' => 'nullable|string|max:100',
            'gst_amount' => 'nullable|numeric|min:0',
            'package_rate' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive',
        ]);

        try {
            DB::beginTransaction();

            $package = Package::findOrFail($id);
            
            $package->update([
                'name' => $request->name,
                'account_head' => $request->account_head ?? null,
                'gst_amount' => $request->gst_amount ?? 0,
                'package_rate' => (float) $request->package_rate,
                'description' => $request->description ?? null,
                'status' => $request->status ?? 'active',
                'is_active' => ($request->status ?? 'active') === 'active',
            ]);

            PackageCharge::where('package_id', $package->id)->delete();

            $charges = is_array($request->input('charges')) ? $request->input('charges') : [];
            $displayOrder = 0;
            foreach ($charges as $chargeData) {
                $chargeType = isset($chargeData['charge_type']) ? trim((string) $chargeData['charge_type']) : '';
                $amountRaw = $chargeData['amount'] ?? null;
                $amount = $amountRaw === null || $amountRaw === '' ? 0 : (float) $amountRaw;

                if ($chargeType === '' && $amount == 0) {
                    continue;
                }

                PackageCharge::create([
                    'package_id' => $package->id,
                    'charge_type' => $chargeType !== '' ? $chargeType : 'Other',
                    'charge_category_id' => $chargeData['charge_category_id'] ?? null,
                    'charge_id' => $chargeData['charge_id'] ?? null,
                    'amount' => $amount,
                    'is_percentage' => !empty($chargeData['is_percentage']),
                    'display_order' => $displayOrder++,
                ]);
            }

            // Delete existing excludes
            PackageExclude::where('package_id', $package->id)->delete();

            // Save new package excludes
            if ($request->has('excludes') && is_array($request->excludes)) {
                foreach ($request->excludes as $excludeData) {
                    PackageExclude::create([
                        'package_id' => $package->id,
                        'charge_category_id' => $excludeData['charge_category_id'] ?? null,
                        'charge_id' => $excludeData['charge_id'] ?? null,
                        'description' => $excludeData['description'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('packages.index')
                ->with('success', 'Package updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating package: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating package: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $package = Package::findOrFail($id);
            
            // Check if package is used in any IPD
            $ipdCount = $package->ipdPackages()->count();
            if ($ipdCount > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete package. It is currently applied to ' . $ipdCount . ' IPD patient(s).');
            }

            DB::beginTransaction();
            
            // Delete related records
            PackageCharge::where('package_id', $package->id)->delete();
            PackageExclude::where('package_id', $package->id)->delete();
            $package->delete();

            DB::commit();

            return redirect()->route('packages.index')
                ->with('success', 'Package deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting package: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error deleting package: ' . $e->getMessage());
        }
    }

    /**
     * Get package charges as JSON (API endpoint)
     */
    public function getPackageCharges($id)
    {
        $package = Package::with('charges')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'package' => $package,
            'charges' => $package->charges,
        ]);
    }

    /**
     * Get all active packages for API (used in admission form)
     */
    public function getActivePackagesApi()
    {
        // Backward-compatible: treat legacy rows without is_active/status as active
        $packages = Package::query()
            ->where(function ($q) {
                $q->where('is_active', true)
                  ->orWhereNull('is_active');
            })
            ->where(function ($q) {
                $q->where('status', 'active')
                  ->orWhereNull('status');
            })
            ->select('id', 'name', 'package_rate', 'gst_amount', 'description')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'packages' => $packages,
        ]);
    }
}