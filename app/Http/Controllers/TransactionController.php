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


       Transaction::create([
    'transaction_date' => $request->date,
    'amount'           => $request->amount,
    'payment_mode'     => $request->payment_mode,
    'note'             => $request->note,

    'ipd_id'           => $request->ipd_id,
    'patient_id'       => $request->patient_id,
    'type'             => $request->type,      // payment
    'section'          => $request->section,   // ipd

    'created_by'       => auth()->id(),
]);


        return redirect()->back()->with('success', 'Payment added successfully');
    }
}
