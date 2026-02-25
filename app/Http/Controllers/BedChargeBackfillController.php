<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DaywiseBedChargeService;
use Illuminate\Support\Facades\Log;

class BedChargeBackfillController extends Controller
{
    protected $service;

    public function __construct(DaywiseBedChargeService $service)
    {
        $this->service = $service;
    }

    /**
     * HTTP endpoint to backfill daywise bed charges for an IPD and date range.
     * POST body: ipd_id, start_date (Y-m-d), end_date (Y-m-d)
     */
    public function backfill(Request $request)
    {
        $request->validate([
            'ipd_id' => 'required|integer',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d',
        ]);

        $ipdId = $request->input('ipd_id');
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        Log::info('Backfill requested', ['ipd_id' => $ipdId, 'start' => $start, 'end' => $end, 'by' => auth()->id() ?? 'console']);

        $result = $this->service->calculateChargesForDateRange($ipdId, $start, $end);

        return response()->json(['success' => true, 'result' => $result]);
    }
}
