<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\BedGroup;
use App\Models\InsurerRoomMapping;
use App\Models\InsuranceCompany;
use App\Models\InsuranceRatePanel;
use App\Support\InsurerRoomTierPresets;
use Illuminate\Http\Request;

class PackageRoomMappingController extends Controller
{
    public function index(Request $request)
    {
        $panelId = $request->input('insurance_rate_panel_id');
        $mappings = InsurerRoomMapping::with(['bedGroup', 'ratePanel', 'insuranceCompany'])
            ->when($panelId, fn ($q) => $q->where('insurance_rate_panel_id', $panelId))
            ->orderBy('insurer_room_code')
            ->get();

        return view('admin.setup.packages.room_mappings', [
            'mappings' => $mappings,
            'bedGroups' => BedGroup::orderBy('name')->get(['id', 'name']),
            'ratePanels' => InsuranceRatePanel::where('is_active', true)->orderBy('name')->get(['id', 'name', 'code']),
            'insuranceCompanies' => InsuranceCompany::orderBy('name')->get(['id', 'name']),
            'selectedPanelId' => $panelId,
            'panelSchemesJson' => InsuranceRatePanel::where('is_active', true)->get(['id', 'name', 'code'])
                ->mapWithKeys(fn ($p) => [(string) $p->id => InsurerRoomTierPresets::forPanel($p)]),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'insurance_rate_panel_id' => 'nullable|exists:insurance_rate_panels,id',
            'insurance_company_id' => 'nullable|exists:insurance_companies,id',
            'insurer_room_code' => 'required|string|max:10',
            'bed_group_id' => 'required|integer',
            'label' => 'nullable|string|max:100',
        ]);

        InsurerRoomMapping::create($request->only([
            'insurance_rate_panel_id',
            'insurance_company_id',
            'insurer_room_code',
            'bed_group_id',
            'label',
        ]));

        return redirect()
            ->route('packages.room-mappings', ['insurance_rate_panel_id' => $request->insurance_rate_panel_id])
            ->with('success', 'Room mapping saved.');
    }

    public function destroy(Request $request, $id)
    {
        $mapping = InsurerRoomMapping::findOrFail($id);
        $panelId = $mapping->insurance_rate_panel_id;
        $mapping->delete();

        return redirect()
            ->route('packages.room-mappings', ['insurance_rate_panel_id' => $panelId])
            ->with('success', 'Mapping removed.');
    }
}
