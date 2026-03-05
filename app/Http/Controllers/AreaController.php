<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\District;
use App\Models\State;

class AreaController extends Controller
{
    public function getDistrict($id)
    {
        $area = Area::with('district.state')->find($id);

        if (!$area) {
            return response()->json(['district' => null]);
        }

        return response()->json([
            'district' => $area->district->name ?? $area->district,
            'district_id' => $area->district_id ?? '',
            'state' => $area->district->state->name ?? '',
            'state_id' => $area->district->state_id ?? '',
        ]);
    }
    public function getState($id)
    {
        $district = District::with('state')->find($id);

        if (!$district) {
            return response()->json(['state' => null]);
        }

        return response()->json([
            'state' => $district->state->name ?? null
        ]);
    }
}
