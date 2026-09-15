<?php

namespace App\Http\Controllers;
use App\Models\IpdDetail;
use App\Models\BackendBill;
use App\Models\OpdDetail;
use App\Models\BackendBillItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class BackendBillController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $bills = BackendBill::with('billItems')
            ->withSum('moneyReceipts', 'received_amount')
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        $bills->getCollection()->transform(function (BackendBill $bill) {
            $bill->display_total_amount = (float) ($bill->total_amount ?: $bill->billItems->sum('amount'));
            $bill->received_amount = (float) ($bill->money_receipts_sum_received_amount ?? 0);
            $bill->due_amount = max(0, $bill->display_total_amount - $bill->received_amount);

            return $bill;
        });

        return view('admin.backedbill.backendbill', compact('bills'));
    }


public function store(Request $request)
{
    $validatedData = $request->validate([
    'doctor_id'     => 'nullable',
    'doctor_name'   => 'nullable|string',

    'patient_id'    => 'nullable',
    'bill_amount'   => 'nullable|numeric',
    'case_no'       => 'nullable|string',

    'patient_name'  => 'nullable|string',
    'age'           => 'nullable|integer',
    'gender'        => 'nullable|string',
    'address'       => 'nullable|string',
    'mobileno'      => 'nullable|string',
    'patienttype'   => 'nullable|string',
    'date'          => 'nullable|date',

    'bill_type'     => 'nullable|array',
    'bill_type.*'   => 'nullable|string',

    'amount'        => 'nullable|array',
    'amount.*'      => 'nullable|numeric',
    'adjustment_type' => 'required|in:add,discount',
    'adjustment_amount' => 'nullable|numeric|min:0',
]);



    DB::transaction(function () use ($validatedData) {



         // =========================
    // AUTO GENERATE CASE NO
    // BA001, BA002, BA003...
    // =========================
    $lastCaseNo = BackendBill::where('case_no', 'like', 'BA%')
        ->orderByRaw("CAST(SUBSTRING(case_no, 3) AS UNSIGNED) DESC")
        ->value('case_no');

    if ($lastCaseNo && preg_match('/^BA(\d+)$/', $lastCaseNo, $matches)) {
        $nextNumber = (int) $matches[1] + 1;
    } else {
        $nextNumber = 1;
    }

    $caseNo = 'BA' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // =========================
        // FIRST TABLE
        // =========================
        $bill = BackendBill::create([
            'doctor_name' => $validatedData['doctor_name'] ?? null,
            'doctor_id' => $validatedData['doctor_id'] ?? null,
            'patient_id'      => $validatedData['patient_id'] ?? null,
            'bill_amount'  => $validatedData['bill_amount'] ?? null,
            'patient_name' => $validatedData['patient_name'] ?? null,
            'age'          => $validatedData['age'] ?? null,
            'gender'       => $validatedData['gender'] ?? null,
            'address'      => $validatedData['address'] ?? null,
            'mobileno'     => $validatedData['mobileno'] ?? null,
            'patienttype'  => $validatedData['patienttype'] ?? null,
            'date'         => $validatedData['date'] ?? null,
            'case_no'      => $caseNo,
            
        ]);

        // =========================
        // SECOND TABLE
        // =========================
        $billTypes = $validatedData['bill_type'] ?? [];
        $amounts   = $validatedData['amount'] ?? [];
        $subtotal = 0;

        foreach ($billTypes as $index => $billType) {

            if (empty($billType)) {
                continue;
            }

            BackendBillItem::create([
                'bill_item'  => $billType,
                'amount'     => $amounts[$index] ?? 0,
                'patient_id' => $validatedData['patient_id'] ?? null,
                'case_no'      => $caseNo,
            ]);

            $subtotal += (float) ($amounts[$index] ?? 0);
        }

        $adjustmentAmount = (float) ($validatedData['adjustment_amount'] ?? 0);
        $totalAmount = $validatedData['adjustment_type'] === 'discount'
            ? max(0, $subtotal - $adjustmentAmount)
            : $subtotal + $adjustmentAmount;

        $bill->update([
            'total_amount' => round($totalAmount, 2),
            'adjustment_type' => $validatedData['adjustment_type'],
            'adjustment_amount' => round($adjustmentAmount, 2),
        ]);
    });

    return redirect()
        ->route('backend')
        ->with('success', 'Backend bill created successfully.');
}


public function update(Request $request)
{
    $request->validate([
        'id' => 'required|exists:backend_bill,id',
        'patient_name' => 'required',
        'gender' => 'required',
        'date' => 'required|date',
        'bill_type' => 'nullable|array',
        'bill_type.*' => 'nullable|string',
        'amount' => 'nullable|array',
        'amount.*' => 'nullable|numeric',
        'adjustment_type' => 'required|in:add,discount',
        'adjustment_amount' => 'nullable|numeric|min:0',
    ]);

    DB::transaction(function () use ($request) {
        $bill = BackendBill::findOrFail($request->id);

        $bill->patient_name = $request->patient_name;
        $bill->age = $request->age;
        $bill->gender = $request->gender;
        $bill->doctor_id = $request->doctor_id;
        $bill->doctor_name = $request->doctor_name;
        $bill->address = $request->address;
        $bill->shift = $request->shift;
        $bill->date = $request->date;
        $bill->patienttype = $request->patienttype;
        $bill->discount_percentage = $request->discount_percentage;
        $bill->save();

        BackendBillItem::where('case_no', $bill->case_no)->delete();

        $subtotal = 0;

        foreach ($request->input('bill_type', []) as $index => $billType) {
            if (blank($billType)) {
                continue;
            }

            BackendBillItem::create([
                'bill_item' => $billType,
                'amount' => $request->input("amount.$index", 0),
                'patient_id' => $bill->patient_id,
                'case_no' => $bill->case_no,
            ]);

            $subtotal += (float) $request->input("amount.$index", 0);
        }

        $adjustmentAmount = (float) ($request->input('adjustment_amount') ?? 0);
        $bill->update([
            'total_amount' => round($request->input('adjustment_type') === 'discount'
                ? max(0, $subtotal - $adjustmentAmount)
                : $subtotal + $adjustmentAmount, 2),
            'adjustment_type' => $request->input('adjustment_type'),
            'adjustment_amount' => round($adjustmentAmount, 2),
        ]);
    });

    return redirect()
        ->back()
        ->with('success', 'Backend Bill updated successfully.');
}



public function edit($id)
{
    $bill = BackendBill::with('billItems')->findOrFail($id);

    return response()->json([
        'bill' => [
            'id' => $bill->id,
            'patient_name' => $bill->patient_name,
            'age' => $bill->age,
            'gender' => $bill->gender,
            'doctor_id' => $bill->doctor_id,
            'doctor_name' => $bill->doctor_name,
            'address' => $bill->address,
            'shift' => $bill->shift,
            'date' => $bill->date,
            'patienttype' => $bill->patienttype,
            'discount_percentage' => $bill->discount_percentage,
            'total_amount' => $bill->total_amount,
            'adjustment_type' => $bill->adjustment_type,
            'adjustment_amount' => $bill->adjustment_amount,

            'bill_items' => $bill->billItems
        ]
    ]);
}

public function downloadPdf($id)
{
    $bill = BackendBill::with('billItems')->findOrFail($id);

    $pdf = Pdf::loadView('admin.backedbill.backendbill-pdf', [
        'bill' => $bill,
    ]);

    return $pdf->stream('backend-bill-' . $bill->case_no . '.pdf');
}



public function destroy($id)
{
    DB::transaction(function () use ($id) {
        $bill = BackendBill::findOrFail($id);

        BackendBillItem::where('case_no', $bill->case_no)->delete();
        $bill->forceDelete();
    });

    return redirect()->back()->with('success', 'Bill permanently deleted successfully.');
}







  public function searchIpdPatient(Request $request)
{
    $search = trim($request->get('search'));

    if (strlen($search) < 3) {
        return response()->json([]);
    }

   $ipdPatients = IpdDetail::query()
    ->join('patients', 'patients.id', '=', 'ipd_details.patient_id')
    ->leftJoin('doctor', 'doctor.id', '=', 'ipd_details.cons_doctor')
    ->where(function ($query) use ($search) {
        $query->where('patients.patient_name', 'LIKE', "%{$search}%")
            ->orWhere('patients.mobileno', 'LIKE', "%{$search}%")
            ->orWhere('patients.email', 'LIKE', "%{$search}%");
    })
    ->select(
        'ipd_details.id',
        'ipd_details.ipd_no',
        'ipd_details.patient_id',
        'ipd_details.cons_doctor',
        'doctor.id as doctor_id',
        'doctor.name',
        'patients.patient_name',
        'patients.mobileno',
        'patients.email',
        'patients.gender',
        'patients.age',
        'patients.address'
    )
    ->limit(10)
    ->get();


    return response()->json($ipdPatients->map(function ($ipd) {
        return [
            'id'           => $ipd->id,
            'patient_id'   => $ipd->patient_id,
            'ipd_no'       => $ipd->ipd_no,
            'patient_name' => $ipd->patient_name,
            'mobile'        => $ipd->mobileno,
            'email'        => $ipd->email,
            'gender'       => $ipd->gender,
            'age'          => $ipd->age,
            'address'      => $ipd->address,
            'doctor_id'    => $ipd->doctor_id,
            'doctor_name'  => $ipd->name,
        ];
    }));
}


  public function searchOpdPatient(Request $request)
{
    $search = trim($request->get('search'));

    if (strlen($search) < 3) {
        return response()->json([]);
    }

    $opdPatients = OpdDetail::query()
        ->join('patients', 'patients.id', '=', 'opd_details.patient_id')
        ->leftJoin('doctor', 'doctor.id', '=', 'opd_details.doctor_id')
        ->where(function ($query) use ($search) {
            $query->where('patients.patient_name', 'LIKE', "%{$search}%")
                ->orWhere('patients.mobileno', 'LIKE', "%{$search}%")
                ->orWhere('patients.email', 'LIKE', "%{$search}%");
        })
        ->select(
            'opd_details.id',
            'opd_details.opd_no',
            'opd_details.patient_id',
            'opd_details.doctor_id',
            'doctor.id as doctor_id',
            'doctor.name',
            'patients.patient_name',
            'patients.mobileno',
            'patients.email',
            'patients.gender',
            'patients.age',
            'patients.address'
        )
        ->limit(10)
        ->get();

    return response()->json($opdPatients->map(function ($opd) {
        return [
            'id'           => $opd->id,
            'patient_id'   => $opd->patient_id,
            'ipd_no'       => $opd->ipd_no,
            'patient_name' => $opd->patient_name,
            'mobile'        => $opd->mobileno,
            'email'        => $opd->email,
            'gender'       => $opd->gender,
            'age'          => $opd->age,
            'address'      => $opd->address,
            'doctor_id'    => $opd->doctor_id,
            'doctor_name'  => $opd->name,
        ];
    }));
}


}
