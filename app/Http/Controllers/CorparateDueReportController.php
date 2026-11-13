<?php

namespace App\Http\Controllers;

use App\Models\DischargeCard;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CorparateDueReportController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d',
        ]);

        if (
            $validator->passes()
            && $request->filled('start_date')
            && $request->filled('end_date')
            && $request->input('end_date') < $request->input('start_date')
        ) {
            $validator->errors()->add('end_date', 'The end date must be on or after the start date.');
        }

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $discharges = DischargeCard::query()
            ->whereNotNull('ipd_details_id')
            ->when($request->filled('start_date'), function ($query) use ($request) {
                $query->whereDate('discharge_date', '>=', $request->input('start_date'));
            })
            ->when($request->filled('end_date'), function ($query) use ($request) {
                $query->whereDate('discharge_date', '<=', $request->input('end_date'));
            })
            ->whereHas('ipdDetails', function ($query) {
                $query->where('final_approval_amount', '>', 0)
                    ->where(function ($query) {
                        $query->whereNotNull('organisation_id')
                            ->orWhereNotNull('insurance_company_id')
                            ->orWhere('is_cashless', true);
                    });
            })
            ->with([
                'ipdDetails:id,patient_id,ipd_no,organisation_id,insurance_company_id,final_approval_amount',
                'ipdDetails.patient' => static function ($query) {
                    $query->withTrashed()->select('id', 'patient_name');
                },
                'ipdDetails.organisation:id,organisation_name',
                'ipdDetails.insuranceCompany:id,name',
            ])
            ->orderByDesc('discharge_date')
            ->orderByDesc('id')
            ->get(['id', 'ipd_details_id', 'discharge_date']);

        $ipds = $discharges->pluck('ipdDetails')->filter();
        $ipdIds = $ipds->pluck('id')->unique()->values();
        $ipdNumbers = $ipds->pluck('ipd_no')->filter()->unique()->values();

        $paymentFilters = static function ($query) {
            $query->whereNotNull('receipt_no')
                ->where('receipt_no', '!=', '')
                ->whereNull('deleted_at')
                ->whereRaw('LOWER(TRIM(receipt_type)) = ?', ['corporate due']);
        };

        $paidByIpdId = $ipdIds->isEmpty()
            ? collect()
            : Transaction::query()
                ->selectRaw('ipd_id, SUM(amount) AS paid_amount')
                ->whereIn('ipd_id', $ipdIds)
                ->where($paymentFilters)
                ->groupBy('ipd_id')
                ->pluck('paid_amount', 'ipd_id');

        $paidByIpdNumber = $ipdNumbers->isEmpty()
            ? collect()
            : Transaction::query()
                ->selectRaw('final_bill_no, SUM(amount) AS paid_amount')
                ->whereNull('ipd_id')
                ->whereIn('final_bill_no', $ipdNumbers)
                ->where($paymentFilters)
                ->groupBy('final_bill_no')
                ->pluck('paid_amount', 'final_bill_no');

        $corporateDues = $discharges->map(function ($discharge) use ($paidByIpdId, $paidByIpdNumber) {
            $ipd = $discharge->ipdDetails;
            $ipdId = (int) $ipd->id;
            $ipdNumber = $ipd->ipd_no;
            $finalApprovalAmount = round((float) ($ipd->final_approval_amount ?? 0), 2);
            $paidAmount = round(
                (float) ($paidByIpdId->get($ipdId, 0))
                + (float) ($ipdNumber ? $paidByIpdNumber->get($ipdNumber, 0) : 0),
                2
            );
            $dueAmount = round(max(0, $finalApprovalAmount - $paidAmount), 2);
            $tpaName = trim((string) ($ipd->organisation->organisation_name ?? ''));

            if ($tpaName === '') {
                $tpaName = trim((string) ($ipd->insuranceCompany->name ?? ''));
            }

            return [
                'discharge_id' => $discharge->id,
                'ipd_id' => $ipdId,
                'ipd_no' => $ipdNumber,
                'patient_name' => $ipd->patient->patient_name ?? null,
                'tpa_name' => $tpaName !== '' ? $tpaName : null,
                'discharge_date' => $discharge->discharge_date,
                'final_approval_amount' => $finalApprovalAmount,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'payment_status' => $dueAmount > 0 ? 'Due' : 'Paid',
            ];
        })->values();

        return response()->json([
            'status' => true,
            'total' => $corporateDues->count(),
            'data' => $corporateDues,
        ]);
    }
}
