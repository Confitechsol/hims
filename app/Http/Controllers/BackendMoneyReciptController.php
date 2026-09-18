<?php

namespace App\Http\Controllers;

use App\Models\BackendBill;
use App\Models\BackentMoneyReceipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BackendMoneyReciptController extends Controller
{
        public function searchPatient(Request $request)
{
    $search = trim((string) $request->input('search', ''));
    $perPage = min(max((int) $request->input('perPage', 10), 10), 100);

    if (mb_strlen($search) < 3) {
        return response()->json([
            'success' => true,
            'message' => 'Enter at least 3 characters to search.',
            'data' => [],
        ]);
    }

    $bills = BackendBill::with('billItems')
        ->when($search !== '', function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('case_no', 'like', "%{$search}%")
                    ->orWhere('patient_name', 'like', "%{$search}%")
                    ->orWhere('patient_id', 'like', "%{$search}%")
                    ->orWhere('doctor_name', 'like', "%{$search}%")
                    ->orWhereHas('billItems', function ($query) use ($search) {
                        $query->where('bill_item', 'like', "%{$search}%")
                            ->orWhere('amount', 'like', "%{$search}%");
                    });
            });
        })
        ->orderByDesc('id')
        ->paginate($perPage)
        ->withQueryString();

    $bills->getCollection()->transform(function (BackendBill $bill) {
        $bill->display_total_amount = (float) (
            $bill->total_amount ?: $bill->billItems->sum('amount')
        );

        return $bill;
    });

    return response()->json([
        'success' => true,
        'message' => 'Bills fetched successfully.',
        'data' => $bills->items(),
        'pagination' => [
            'current_page' => $bills->currentPage(),
            'last_page' => $bills->lastPage(),
            'per_page' => $bills->perPage(),
            'total' => $bills->total(),
            'from' => $bills->firstItem(),
            'to' => $bills->lastItem(),
        ],
    ]);
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'backend_bill_id' => 'nullable|exists:backend_bill,id',
            'patient_name' => 'required_without:backend_bill_id|string|max:255',
            'age' => 'nullable|integer|min:0',
            'bill_amount' => 'required_without:backend_bill_id|numeric|min:0',
            'date' => 'required|date',
            'receipt_type' => 'required|string|max:50',
            'received_amount' => 'required|numeric|min:0.01',
            'payment_mode' => 'nullable|string|max:50',
        ]);

        $bill = !empty($validated['backend_bill_id'])
            ? BackendBill::with('billItems')->findOrFail($validated['backend_bill_id'])
            : BackendBill::with('billItems')
                ->where('patient_name', $validated['patient_name'])
                ->latest('id')
                ->get()
                ->first(function (BackendBill $candidate) use ($validated) {
                    $amount = (float) ($candidate->total_amount ?: $candidate->billItems->sum('amount'));

                    return abs($amount - (float) $validated['bill_amount']) < 0.01;
                });

        if (!$bill) {
            return back()->withErrors(['patient_name' => 'No matching backend bill was found for this patient and bill amount.'])->withInput();
        }

        $totalAmount = (float) ($bill->total_amount ?: $bill->billItems->sum('amount'));
        $receivedAmount = (float) $validated['received_amount'];
        $previouslyReceived = (float) BackentMoneyReceipt::where('backend_bill_id', $bill->id)->sum('received_amount');

        if ($previouslyReceived + $receivedAmount > $totalAmount) {
            return back()->withErrors(['received_amount' => 'Received amount cannot be greater than the due amount.'])->withInput();
        }

        BackentMoneyReceipt::create([
            'backend_bill_id' => $bill->id,
            'case_no' => $bill->case_no,
            'patient_name' => $bill->patient_name,
            'age' => $bill->age,
            'receipt_no' => 'BMR-' . now()->format('YmdHis') . '-' . $bill->id,
            'receipt_type' => $validated['receipt_type'],
            'received_amount' => $receivedAmount,
            'payment_mode' => $validated['payment_mode'] ?? 'Cash',
            'received_by' => auth()->id(),
                'received_date' => $validated['date'],
        ]);

        return back()->with('success', 'Backend money receipt saved successfully.');
    }

   public function index(Request $request)
{
    $perPage = min(max((int) $request->get('per_page', 10), 10), 100);
    $search = $request->get('search');

    $bills = BackentMoneyReceipt::query()
        ->when($search, function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('case_no', 'like', "%{$search}%")
                    ->orWhere('patient_name', 'like', "%{$search}%")
                    ->orWhere('receipt_no', 'like', "%{$search}%")
                    ->orWhere('receipt_type', 'like', "%{$search}%")
                    ->orWhere('payment_mode', 'like', "%{$search}%");
            });
        })
        ->paginate($perPage);

    $response = [
        'success' => true,
        'message' => 'Money receipts fetched successfully',
        'data' => $bills->items(),
        'pagination' => [
            'current_page' => $bills->currentPage(),
            'per_page' => $bills->perPage(),
            'total' => $bills->total(),
            'last_page' => $bills->lastPage(),
        ],
    ];

    if ($request->expectsJson()) {
        return response()->json($response);
    }

    return view('admin.backedbill.backend-money-receipt', [
        'receipts' => $bills,
        'perPage' => $perPage,
        'editReceipt' => null,
    ]);
}

public function editReceipt(int $id)
{
    return view('admin.backedbill.backend-money-receipt', [
        'receipts' => BackentMoneyReceipt::latest('id')->paginate(10),
        'perPage' => 10,
        'editReceipt' => BackentMoneyReceipt::findOrFail($id),
    ]);
}

public function updateReceipt(Request $request, int $id)
{
    $receipt = BackentMoneyReceipt::findOrFail($id);
    $validated = $request->validate([
        'date' => 'required|date',
        'receipt_type' => 'required|string|max:50',
        'received_amount' => 'required|numeric|min:0.01',
    ]);

    $receipt->update([
        'received_date' => $validated['date'],
        'receipt_type' => $validated['receipt_type'],
        'received_amount' => $validated['received_amount'],
    ]);

    return redirect()->route('moneyreceipt')->with('success', 'Backend money receipt updated successfully.');
}

public function destroyReceipt(int $id)
{
    BackentMoneyReceipt::findOrFail($id)->delete();

    return redirect()->route('moneyreceipt')->with('success', 'Backend money receipt deleted successfully.');
}

public function downloadPdf(int $id)
{
    $receipt = BackentMoneyReceipt::findOrFail($id);
    $bill = $receipt->backend_bill_id
        ? BackendBill::with('billItems')->find($receipt->backend_bill_id)
        : BackendBill::with('billItems')->where('case_no', $receipt->case_no)->latest('id')->first();
    $billAmount = $bill
        ? (float) ($bill->total_amount ?: $bill->billItems->sum('amount'))
        : 0;
    $totalReceived = (float) BackentMoneyReceipt::query()
        ->when($receipt->backend_bill_id, function ($query) use ($receipt) {
            $query->where('backend_bill_id', $receipt->backend_bill_id);
        }, function ($query) use ($receipt) {
            $query->where('case_no', $receipt->case_no);
        })
        ->sum('received_amount');
    $dueAmount = max(0, $billAmount - $totalReceived);

    $pdf = Pdf::loadView('admin.backedbill.backend-money-receipt-pdf', compact('receipt', 'bill', 'billAmount', 'totalReceived', 'dueAmount'));
    $pdf->setPaper('a4', 'portrait');

     return $pdf->stream('Backend_Money_Receipt_' . $receipt->receipt_no . '.pdf');
    // return response()->json([
    //     'status' => true,
    //     'message' => 'Money receipt fetched successfully.',
    //     'data' => [
    //         'receipt' => $receipt,
    //         'bill' => $bill,
    //         'bill_amount' => $billAmount,
    //         'total_received' => $totalReceived,
    //         'due_amount' => $dueAmount,
    //     ],
    // ], 200);
}



}
