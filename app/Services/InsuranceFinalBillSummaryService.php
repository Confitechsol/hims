<?php

namespace App\Services;

use App\Models\IpdDetail;
use Illuminate\Support\Collection;

/**
 * Settlement totals for insurance Final Bill / Tax Invoice.
 *
 * Approval Amount is entered manually after insurer response.
 * Balance Amount (display) = Total Bill − Approval Amount.
 * Due on A/C TPA/Insurer = Approval Amount.
 */
class InsuranceFinalBillSummaryService
{
    /**
     * @param  array{total_charges?: float|int, total_payments?: float|int}  $breakup
     * @return array{
     *     total_bill: float,
     *     mou_discount: float,
     *     special_discount: float,
     *     total_discount: float,
     *     approval_amount: float,
     *     balance_amount: float,
     *     advance: float,
     *     due_patient_party: float,
     *     due_on_account: float,
     *     due_on_account_label: string,
     *     advance_receipts_text: string
     * }
     */
    public function build(IpdDetail $ipd, array $breakup, ?Collection $payments = null): array
    {
        $totalBill = round((float) ($breakup['total_charges'] ?? 0), 2);
        $advance = round((float) ($breakup['total_payments'] ?? 0), 2);
        $mouDiscount = round((float) ($ipd->mou_discount ?? 0), 2);
        $specialDiscount = round((float) ($ipd->special_discount ?? 0), 2);
        $approvalAmount = round((float) ($ipd->final_approval_amount ?? 0), 2);
        $duePatientParty = round((float) ($ipd->due_patient_party_amount ?? 0), 2);

        $balanceAmount = round(max(0, $totalBill - $approvalAmount), 2);
        $dueOnAccount = $approvalAmount;

        $tpaName = strtoupper(trim((string) ($ipd->organisation->organisation_name ?? '')));
        $insurerName = strtoupper(trim((string) ($ipd->insuranceCompany->name ?? '')));
        $accountName = $tpaName !== '' ? $tpaName : ($insurerName !== '' ? $insurerName : 'INSURANCE');

        return [
            'total_bill' => $totalBill,
            'mou_discount' => $mouDiscount,
            'special_discount' => $specialDiscount,
            'total_discount' => round($mouDiscount + $specialDiscount, 2),
            'approval_amount' => $approvalAmount,
            'balance_amount' => $balanceAmount,
            'advance' => $advance,
            'due_patient_party' => $duePatientParty,
            'due_on_account' => $dueOnAccount,
            'due_on_account_label' => 'Due Amount (On A/C ' . $accountName . ')',
            'advance_receipts_text' => $this->formatAdvanceReceipts($payments ?? collect()),
        ];
    }

    /**
     * Format payment refs like: R/001473(1798-D)-25/06/2026
     */
    public function formatAdvanceReceipts(Collection $payments): string
    {
        if ($payments->isEmpty()) {
            return '';
        }

        return $payments->map(function ($payment) {
            $id = (int) ($payment->id ?? 0);
            $amount = (float) ($payment->amount ?? 0);
            if (strcasecmp((string) ($payment->receipt_type ?? ''), 'Refund') === 0) {
                $amount = -abs($amount);
            }

            $typeCode = $this->receiptTypeCode($payment);
            $date = !empty($payment->payment_date)
                ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y')
                : (!empty($payment->created_at) ? \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') : '');

            $ref = 'R/' . str_pad((string) $id, 6, '0', STR_PAD_LEFT);
            $ref .= '(' . number_format(abs($amount), 0, '.', '') . '-' . $typeCode . ')';
            if ($date !== '') {
                $ref .= '-' . $date;
            }

            return $ref;
        })->implode(', ');
    }

    protected function receiptTypeCode($payment): string
    {
        $type = strtoupper(trim((string) ($payment->receipt_type ?? '')));
        if ($type === '') {
            $mode = $payment->payment_mode ?? null;
            if ($mode == 1 || $mode === '1' || strcasecmp((string) $mode, 'Cash') === 0) {
                return 'C';
            }

            return 'D';
        }

        return match (true) {
            str_contains($type, 'REFUND') => 'R',
            str_contains($type, 'CASH') => 'C',
            str_contains($type, 'CURRENT') => 'D',
            default => strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $type) ?: 'D', 0, 1)),
        };
    }

    /**
     * Whether an IPD charge looks like medicine / implant (package-excluded billables).
     */
    public function isMedicineOrImplantCharge($charge): bool
    {
        $category = strtoupper((string) ($charge->chargeCategory->name ?? ''));
        $name = strtoupper((string) ($charge->charge->name ?? $charge->standard_charge ?? ''));
        $haystack = $category . ' ' . $name;

        foreach (['MEDICINE', 'PHARMACY', 'DRUG', 'IMPLANT', 'PROSTHES', 'STENT', 'PACEMAKER', 'IOL', 'MESH', 'STAPLER'] as $needle) {
            if (str_contains($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }
}
