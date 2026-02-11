<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'date'        => 'required|date',
            'amount'      => 'required|numeric|min:1',
            'ipd_id'      => 'required|integer',
            'patient_id'  => 'required|integer',
            'type'        => 'required|string',
            'section'     => 'required|string',
        ]);

        // Generate receipt number based on payment date (financial year) and set receipt type to 'Current' for Add Payment
        $paymentDate = $request->date ?? now();
        $receiptNo = Transaction::generateReceiptNo($paymentDate);
        $receiptType = 'Current';

        // Get IPD to derive final bill number if needed
        $ipd = \App\Models\IpdDetail::find($request->ipd_id);
        $finalBillNo = $ipd ? $ipd->ipd_no : null;

        // Get hospital and branch IDs (use IPD's hospital_id/branch_id or default)
        $hospitalId = $ipd ? $ipd->hospital_id : '1';
        $branchId = $ipd ? ($ipd->branch_id ?? '1') : '1';

        Transaction::create([
            'hospital_id'     => $hospitalId,
            'branch_id'       => $branchId,
            'payment_date'   => $request->date ?? now(),
            'amount'         => $request->amount,
            'payment_mode'   => $request->payment_mode ?? 'Cash',
            'cheque_no'      => $request->cheque_no ?? null,
            'cheque_date'    => $request->cheque_date ?? null,
            'note'           => $request->note ?? null,

            'ipd_id'         => $request->ipd_id,
            'patient_id'     => $request->patient_id,
            'type'           => $request->type,      // payment
            'section'        => $request->section,    // ipd

            // Receipt fields - auto-generated for Add Payment
            'receipt_no'     => $receiptNo,
            'receipt_type'   => $receiptType,
            'final_bill_no'  => $finalBillNo,
            'received_by'    => auth()->id(),
            'created_at'     => now(),
        ]);

        return redirect()->back()->with('success', 'Payment added successfully. Receipt No: ' . $receiptNo);
    }
}
