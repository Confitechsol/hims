<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\BedGroup;
use App\Models\Charge;
use App\Models\ChargeCategory;
use App\Models\InsuranceCompany;
use App\Models\InsuranceRatePanel;
use App\Models\Package;
use App\Models\PackageCharge;
use App\Models\PackageExclude;
use App\Models\PackageRoomRate;
use App\Services\PackageInsuranceRateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class PackageController extends Controller
{
    public function __construct(
        protected PackageInsuranceRateService $rateService
    ) {
    }

    public function index(Request $request)
    {
        $query = Package::with(['insuranceCompany', 'insuranceRatePanel', 'roomRates'])
            ->withCount('roomRates')
            ->orderByDesc('created_at');

        if ($request->filled('package_type')) {
            $query->where('package_type', $request->package_type);
        }
        if ($request->filled('insurance_rate_panel_id')) {
            $query->where('insurance_rate_panel_id', $request->insurance_rate_panel_id);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('insurer_procedure_code', 'like', "%{$s}%")
                    ->orWhere('speciality', 'like', "%{$s}%");
            });
        }

        $packages = $query->get();
        $ratePanels = InsuranceRatePanel::where('is_active', true)->orderBy('name')->get(['id', 'name', 'code']);

        return view('admin.setup.packages.index', compact('packages', 'ratePanels'));
    }

    public function create()
    {
        return view('admin.setup.packages.form', $this->formData(new Package()));
    }

    public function store(Request $request)
    {
        $request->validate($this->validationRules($request));

        try {
            DB::beginTransaction();
            $user = Auth::user();
            $package = Package::create($this->packageAttributes($request, $user));
            $this->syncCharges($package, $request);
            $this->syncExcludes($package, $request);
            $this->syncRoomRates($package, $request);
            $this->rateService->syncDefaultPackageRate($package->fresh());
            DB::commit();

            return redirect()->route('packages.index')->with('success', 'Package created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating package: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Error creating package: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $package = Package::with([
            'charges',
            'excludes',
            'roomRates.bedGroup',
            'insuranceCompany',
            'insuranceRatePanel',
            'ipdPackages.ipd.patient',
        ])->findOrFail($id);

        return view('admin.setup.packages.show', compact('package'));
    }

    public function edit(string $id)
    {
        $package = Package::with(['charges', 'excludes', 'roomRates'])->findOrFail($id);

        return view('admin.setup.packages.form', $this->formData($package));
    }

    public function update(Request $request, string $id)
    {
        $request->validate($this->validationRules($request));

        try {
            DB::beginTransaction();
            $package = Package::findOrFail($id);
            $package->update($this->packageAttributes($request, null, $package));
            PackageCharge::where('package_id', $package->id)->delete();
            $this->syncCharges($package, $request);
            PackageExclude::where('package_id', $package->id)->delete();
            $this->syncExcludes($package, $request);
            $this->syncRoomRates($package, $request);
            $this->rateService->syncDefaultPackageRate($package->fresh());
            DB::commit();

            return redirect()->route('packages.index')->with('success', 'Package updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating package: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Error updating package: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $package = Package::findOrFail($id);
            $ipdCount = $package->ipdPackages()->count();
            if ($ipdCount > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete package. It is applied to ' . $ipdCount . ' IPD patient(s).');
            }

            DB::beginTransaction();
            PackageCharge::where('package_id', $package->id)->delete();
            PackageExclude::where('package_id', $package->id)->delete();
            PackageRoomRate::where('package_id', $package->id)->delete();
            $package->delete();
            DB::commit();

            return redirect()->route('packages.index')->with('success', 'Package deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting package: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error deleting package: ' . $e->getMessage());
        }
    }

    public function getPackageCharges($id)
    {
        $package = Package::with(['charges', 'roomRates.bedGroup'])->findOrFail($id);
        $bedGroupId = request()->integer('bed_group_id') ?: null;
        $resolvedRate = $this->rateService->resolveRate($package, $bedGroupId);

        return response()->json([
            'success' => true,
            'package' => $package,
            'charges' => $package->charges,
            'resolved_rate' => $resolvedRate,
            'room_rates' => $package->roomRates,
        ]);
    }

    public function getActivePackagesApi(Request $request)
    {
        $bedGroupId = $request->integer('bed_group_id') ?: null;
        $insuranceCompanyId = $request->integer('insurance_company_id') ?: null;
        $panelId = $request->integer('insurance_rate_panel_id') ?: null;

        $packages = Package::query()
            ->active()
            ->forInsuranceContext($insuranceCompanyId, $panelId)
            ->with(['roomRates', 'insuranceRatePanel'])
            ->orderBy('name')
            ->get()
            ->map(function (Package $package) use ($bedGroupId) {
                $resolvedRate = $this->rateService->resolveRate($package, $bedGroupId);

                return [
                    'id' => $package->id,
                    'name' => $package->name,
                    'package_type' => $package->package_type ?? Package::TYPE_HOSPITAL,
                    'package_rate' => $resolvedRate,
                    'base_package_rate' => (float) $package->package_rate,
                    'gst_amount' => (float) $package->gst_amount,
                    'description' => $package->description,
                    'insurer_procedure_code' => $package->insurer_procedure_code,
                    'speciality' => $package->speciality,
                    'insurance_rate_panel_id' => $package->insurance_rate_panel_id,
                    'panel_name' => $package->insuranceRatePanel?->name,
                    'has_room_rates' => $package->roomRates->isNotEmpty(),
                ];
            });

        return response()->json([
            'success' => true,
            'packages' => $packages,
        ]);
    }

    protected function formData(Package $package): array
    {
        return [
            'package' => $package,
            'chargeCategories' => ChargeCategory::with('chargeType')->get(),
            'charges' => Charge::with('category')->get(),
            'bedGroups' => BedGroup::orderBy('name')->get(['id', 'name', 'floor', 'bed_cost']),
            'insuranceCompanies' => InsuranceCompany::orderBy('name')->get(['id', 'name', 'code']),
            'ratePanels' => InsuranceRatePanel::where('is_active', true)->orderBy('name')->get(['id', 'name', 'code']),
        ];
    }

    protected function validationRules(Request $request): array
    {
        return [
            'name' => 'required|string|max:255',
            'package_type' => ['required', Rule::in([Package::TYPE_HOSPITAL, Package::TYPE_INSURANCE])],
            'account_head' => 'nullable|string|max:100',
            'gst_amount' => 'nullable|numeric|min:0',
            'package_rate' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive',
            'insurance_company_id' => 'nullable|exists:insurance_companies,id',
            'insurance_rate_panel_id' => 'nullable|exists:insurance_rate_panels,id',
            'insurer_procedure_code' => 'nullable|string|max:50',
            'speciality' => 'nullable|string|max:100',
            'room_eligibility' => 'nullable|string|max:20',
            'inclusion_notes' => 'nullable|string',
            'effective_from' => 'nullable|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'contract_reference' => 'nullable|string|max:200',
            'room_rates' => 'nullable|array',
            'room_rates.*.bed_group_id' => 'nullable|integer',
            'room_rates.*.rate' => 'nullable|numeric|min:0',
        ];
    }

    protected function packageAttributes(Request $request, $user = null, ?Package $existing = null): array
    {
        $user = $user ?? Auth::user();
        $type = $request->input('package_type', Package::TYPE_HOSPITAL);

        $attrs = [
            'name' => $request->name,
            'package_type' => $type,
            'account_head' => $request->account_head ?? null,
            'gst_amount' => $request->gst_amount ?? 0,
            'package_rate' => (float) $request->package_rate,
            'description' => $request->description ?? null,
            'status' => $request->status ?? 'active',
            'is_active' => ($request->status ?? 'active') === 'active',
            'insurer_procedure_code' => $request->insurer_procedure_code ?? null,
            'speciality' => $request->speciality ?? null,
            'room_eligibility' => $request->room_eligibility ?? null,
            'inclusion_notes' => $request->inclusion_notes ?? null,
            'effective_from' => $request->effective_from ?: null,
            'effective_to' => $request->effective_to ?: null,
            'contract_reference' => $request->contract_reference ?? null,
            'insurance_company_id' => null,
            'insurance_rate_panel_id' => null,
        ];

        if ($type === Package::TYPE_INSURANCE) {
            $attrs['insurance_company_id'] = $request->insurance_company_id ?: null;
            $attrs['insurance_rate_panel_id'] = $request->insurance_rate_panel_id ?: null;
        }

        if (!$existing) {
            $attrs['hospital_id'] = $user->hospital_id ?? null;
            $attrs['branch_id'] = $user->branch_id ?? null;
            $attrs['created_by'] = $user->id ?? null;
        }

        return $attrs;
    }

    protected function syncCharges(Package $package, Request $request): void
    {
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
    }

    protected function syncExcludes(Package $package, Request $request): void
    {
        if (!$request->has('excludes') || !is_array($request->excludes)) {
            return;
        }
        foreach ($request->excludes as $excludeData) {
            if (empty($excludeData['description']) && empty($excludeData['charge_id'])) {
                continue;
            }
            PackageExclude::create([
                'package_id' => $package->id,
                'charge_category_id' => $excludeData['charge_category_id'] ?? null,
                'charge_id' => $excludeData['charge_id'] ?? null,
                'description' => $excludeData['description'] ?? null,
            ]);
        }
    }

    protected function syncRoomRates(Package $package, Request $request): void
    {
        PackageRoomRate::where('package_id', $package->id)->delete();
        $rows = is_array($request->input('room_rates')) ? $request->input('room_rates') : [];

        foreach ($rows as $row) {
            $bedGroupId = (int) ($row['bed_group_id'] ?? 0);
            $rate = $row['rate'] ?? null;
            if (!$bedGroupId || $rate === null || $rate === '') {
                continue;
            }
            PackageRoomRate::create([
                'package_id' => $package->id,
                'bed_group_id' => $bedGroupId,
                'insurer_room_code' => $row['insurer_room_code'] ?? null,
                'label' => $row['label'] ?? null,
                'rate' => (float) $rate,
            ]);
        }
    }
}
