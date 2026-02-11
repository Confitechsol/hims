<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function store(Request $request)
{
    //dd($request->all());
    $request->validate([
        'date'        => 'required|date',
        'amount'      => 'required|numeric|min:1',
        'ipd_id'      => 'nullable|integer',
        'opd_id'      => 'nullable|integer',
        'patient_id'  => 'required|integer',
        'type'        => 'required|string',
        'section'     => 'required|string',
    ]);

    $data = [
        'transaction_date' => $request->payment_date,
        'amount'           => $request->amount,
        'payment_mode'     => $request->payment_mode,
        'cheque_no'        => $request->cheque_no,
        'cheque_date'      => $request->cheque_date,
        'note'             => $request->note,
        'patient_id'       => $request->patient_id,
        'type'             => $request->type,
        'section'          => $request->section,
        'created_by'       => auth()->id(),
    ];

    if ($request->section == 'ipd') {
        $data['ipd_id'] = $request->ipd_id;
    } else {
        $data['opd_id'] = $request->opd_id;
    }

    Transaction::create($data);

    return redirect()->back()->with('success', 'Payment added successfully');
}

}
