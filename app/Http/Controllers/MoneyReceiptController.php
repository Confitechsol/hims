<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\IpdDetail;
use App\Models\Patient;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class MoneyReceiptController extends Controller
{
    /**
     * Display list of money receipts
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('perPage', 25);
        if ($perPage <= 0) {
            $perPage = 25;
        }

        $query = Transaction::with(['patient', 'ipd', 'receiver'])
            ->whereNotNull('receipt_no')
            ->orderBy('payment_date', 'desc')
            ->orderBy('receipt_no', 'desc');

        // Filter by receipt type
        if ($request->has('receipt_type') && $request->receipt_type != '') {
            $query->where('receipt_type', $request->receipt_type);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        // Search by receipt no, patient name, phone, final bill no
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('receipt_no', 'LIKE', "%{$search}%")
                  ->orWhere('final_bill_no', 'LIKE', "%{$search}%")
                  ->orWhere('slip_no', 'LIKE', "%{$search}%")
                  ->orWhere('booking_no', 'LIKE', "%{$search}%")
                  ->orWhereHas('patient', function($subQ) use ($search) {
                      $subQ->where('patient_name', 'LIKE', "%{$search}%")
                           ->orWhere('mobileno', 'LIKE', "%{$search}%");
                  });
            });
        }

        $receipts = $query->paginate($perPage);

        $receiptTypes = ['Current', 'Patient Due', 'Corporate Due', 'In Admissible', 'Booking', 'Refund'];

        return view('admin.money-receipt.index', compact('receipts', 'perPage', 'receiptTypes'));
    }

    /**
     * Show form for creating new money receipt
     */
    public function create()
    {
        $receiptTypes = ['Current', 'Patient Due', 'Corporate Due', 'In Admissible', 'Booking', 'Refund'];
        $paymentModes = ['Cash', 'Cheque', 'Card', 'UPI', 'Online', 'Transfer to Bank Account', 'Other'];
        
        // Get the next receipt number (latest + 1) for display
        $nextReceiptNo = Transaction::generateReceiptNo(now());
        
        return view('admin.money-receipt.create', compact('receiptTypes', 'paymentModes', 'nextReceiptNo'));
    }

    /**
     * Store a newly created money receipt
     */
    public function store(Request $request)
    {
        $request->validate([
            'payment_date' => 'required|date',
            'receipt_type' => 'required|in:Current,Patient Due,Corporate Due,In Admissible,Booking,Refund',
            'amount' => 'required|numeric|min:0.01',
            'payment_mode' => 'required|string',
            'patient_id' => 'nullable|integer|exists:patients,id',
            'ipd_id' => 'nullable|integer|exists:ipd_details,id',
            'final_bill_no' => 'nullable|string',
        ]);

        // Generate receipt number based on payment date (financial year)
        $paymentDate = $request->payment_date ?? now();
        $receiptNo = Transaction::generateReceiptNo($paymentDate);

        // Get IPD and final bill number
        $ipd = null;
        $finalBillNo = $request->final_bill_no;
        
        if ($request->ipd_id) {
            $ipd = IpdDetail::find($request->ipd_id);
            $finalBillNo = $ipd ? $ipd->ipd_no : $finalBillNo;
        } elseif ($finalBillNo) {
            $ipd = IpdDetail::where('ipd_no', $finalBillNo)->first();
        }

        // For receipt types that must be linked to a final bill/IPD, enforce IPD linkage
        if (in_array($request->receipt_type, ['Patient Due', 'Refund'], true) && !$ipd) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['final_bill_no' => 'For Receipt Type "' . $request->receipt_type . '", an IPD / Final Bill must be selected.']);
        }

        // Get hospital and branch IDs
        $hospitalId = '1';
        $branchId = '1';
        if ($ipd) {
            $hospitalId = $ipd->hospital_id ?? '1';
            $branchId = $ipd->branch_id ?? '1';
        }

        // Determine patient_id
        $patientId = $request->patient_id;
        if (!$patientId && $ipd) {
            $patientId = $ipd->patient_id;
        }

        // For Refund receipts we want this transaction to reduce the net payments.
        // We keep amount stored as positive for reporting, and indicate the nature
        // via receipt_type; IpdBillingController now subtracts Refund amounts when
        // calculating total payments.

        Transaction::create([
            'hospital_id' => $hospitalId,
            'branch_id' => $branchId,
            'receipt_no' => $receiptNo,
            'receipt_type' => $request->receipt_type,
            'slip_no' => $request->slip_no ?? null,
            'booking_no' => $request->booking_no ?? null,
            'final_bill_no' => $finalBillNo,
            'tds' => (float) ($request->tds ?? 0),
            'paid_by' => $request->paid_by ?? null,
            'narration' => $request->narration ?? null,
            'remarks' => $request->remarks ?? null,
            'bank_name' => $request->bank_name ?? null,
            'payment_date' => $request->payment_date ?? now(),
            'amount' => $request->amount,
            'payment_mode' => $request->payment_mode,
            'cheque_no' => $request->cheque_no ?? null,
            'cheque_date' => $request->cheque_date ?? null,
            'note' => $request->note ?? null,
            'patient_id' => $patientId,
            'ipd_id' => $ipd ? $ipd->id : null,
            'type' => 'payment',
            'section' => $ipd ? 'ipd' : 'money_receipt',
            'received_by' => Auth::id(),
            'created_at' => now(),
        ]);

        return redirect()->route('money-receipt.index')
            ->with('success', 'Money Receipt created successfully. Receipt No: ' . $receiptNo);
    }

    /**
     * Display the specified money receipt
     */
    public function show($id)
    {
        $receipt = Transaction::with(['patient', 'ipd', 'receiver', 'ipd.duePatientPartyDoctor'])
            ->whereNotNull('receipt_no')
            ->findOrFail($id);

        $hospital = Hospital::first();

        return view('admin.money-receipt.show', compact('receipt', 'hospital'));
    }

    /**
     * Show form for editing money receipt
     */
    public function edit($id)
    {
        $receipt = Transaction::with(['patient', 'ipd'])
            ->whereNotNull('receipt_no')
            ->findOrFail($id);

        $receiptTypes = ['Current', 'Patient Due', 'Corporate Due', 'In Admissible', 'Booking', 'Refund'];
        $paymentModes = ['Cash', 'Cheque', 'Card', 'UPI', 'Online', 'Transfer to Bank Account', 'Other'];

        return view('admin.money-receipt.edit', compact('receipt', 'receiptTypes', 'paymentModes'));
    }

    /**
     * Update the specified money receipt
     */
    public function update(Request $request, $id)
    {
        $receipt = Transaction::whereNotNull('receipt_no')->findOrFail($id);

        $request->validate([
            'payment_date' => 'required|date',
            'receipt_type' => 'required|in:Current,Patient Due,Corporate Due,In Admissible,Booking,Refund',
            'amount' => 'required|numeric|min:0.01',
            'payment_mode' => 'required|string',
            'patient_id' => 'nullable|integer|exists:patients,id',
            'ipd_id' => 'nullable|integer|exists:ipd_details,id',
            'final_bill_no' => 'nullable|string',
        ]);

        // Get IPD and final bill number
        $ipd = null;
        $finalBillNo = $request->final_bill_no;
        
        if ($request->ipd_id) {
            $ipd = IpdDetail::find($request->ipd_id);
            $finalBillNo = $ipd ? $ipd->ipd_no : $finalBillNo;
        } elseif ($finalBillNo) {
            $ipd = IpdDetail::where('ipd_no', $finalBillNo)->first();
        }

        // Determine patient_id
        $patientId = $request->patient_id;
        if (!$patientId && $ipd) {
            $patientId = $ipd->patient_id;
        }

        $receipt->update([
            'receipt_type' => $request->receipt_type,
            'slip_no' => $request->slip_no ?? null,
            'booking_no' => $request->booking_no ?? null,
            'final_bill_no' => $finalBillNo,
            'tds' => (float) ($request->tds ?? 0),
            'paid_by' => $request->paid_by ?? null,
            'narration' => $request->narration ?? null,
            'remarks' => $request->remarks ?? null,
            'bank_name' => $request->bank_name ?? null,
            'payment_date' => $request->payment_date ?? now(),
            'amount' => $request->amount,
            'payment_mode' => $request->payment_mode,
            'cheque_no' => $request->cheque_no ?? null,
            'cheque_date' => $request->cheque_date ?? null,
            'note' => $request->note ?? null,
            'patient_id' => $patientId,
            'ipd_id' => $ipd ? $ipd->id : null,
            'section' => $ipd ? 'ipd' : 'money_receipt',
        ]);

        return redirect()->route('money-receipt.index')
            ->with('success', 'Money Receipt updated successfully.');
    }

    /**
     * Remove the specified money receipt
     */
    public function destroy($id)
    {
        $receipt = Transaction::whereNotNull('receipt_no')->findOrFail($id);
        $receiptNo = $receipt->receipt_no;
        $receipt->delete();

        return redirect()->route('money-receipt.index')
            ->with('success', 'Money Receipt ' . $receiptNo . ' deleted successfully.');
    }

    /**
     * Print money receipt as PDF
     */
    public function print($id)
    {
        $receipt = Transaction::with([
            'patient', 
            'ipd', 
            'ipd.doctor',
            'ipd.duePatientPartyDoctor',
            'receiver'
        ])
            ->whereNotNull('receipt_no')
            ->findOrFail($id);

        $hospital = Hospital::first();

        $pdf = Pdf::loadView('admin.money-receipt.print', compact('receipt', 'hospital'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('Money_Receipt_' . $receipt->receipt_no . '.pdf');
    }

    /**
     * API: Search IPD by final bill number
     */
    public function searchIpd(Request $request)
    {
        $search = $request->get('search', '');
        
        $query = IpdDetail::with(['patient', 'doctor']);
        
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('ipd_no', 'LIKE', "%{$search}%")
                  ->orWhereHas('patient', function($subQ) use ($search) {
                      $subQ->where('patient_name', 'LIKE', "%{$search}%")
                           ->orWhere('mobileno', 'LIKE', "%{$search}%");
                  });
            });
        }

        $ipds = $query->orderBy('date', 'desc')->limit(50)->get();

        $data = $ipds->map(function($ipd) {
            return [
                'id' => $ipd->id,
                'ipd_no' => $ipd->ipd_no ?? 'N/A',
                'patient_name' => $ipd->patient->patient_name ?? 'N/A',
                'patient_id' => $ipd->patient_id,
                'phone' => $ipd->patient->mobileno ?? '',
                'display_text' => ($ipd->ipd_no ?? 'N/A') . ' - ' . ($ipd->patient->patient_name ?? 'N/A'),
            ];
        });

        return response()->json(['data' => $data]);
    }

    /**
     * API: Search patient (by IPD number, patient name, phone)
     */
    public function searchPatient(Request $request)
    {
        $search = $request->get('search', '');
        
        $results = [];
        
        if (!empty($search)) {
            // Search IPD patients
            $ipds = IpdDetail::with('patient')
                ->where(function($q) use ($search) {
                    $q->where('ipd_no', 'LIKE', "%{$search}%")
                      ->orWhereHas('patient', function($subQ) use ($search) {
                          $subQ->where('patient_name', 'LIKE', "%{$search}%")
                               ->orWhere('mobileno', 'LIKE', "%{$search}%");
                      });
                })
                ->orderBy('date', 'desc')
                ->limit(50)
                ->get();
            
            foreach ($ipds as $ipd) {
                $patient = $ipd->patient;
                $results[] = [
                    'id' => $ipd->patient_id,
                    'patient_name' => $patient->patient_name ?? 'N/A',
                    'phone' => $patient->mobileno ?? '',
                    'address' => $patient->address ?? '',
                    'age' => $patient->age ?? '',
                    'gender' => $patient->gender ?? '',
                    'marital_status' => $patient->marital_status ?? '',
                    'guardian_name' => $patient->guardian_name ?? '',
                    'area' => $patient->area ?? '',
                    'bill_type' => 'IPD',
                    'bill_no' => $ipd->ipd_no,
                    'bill_id' => $ipd->id,
                    'display_text' => ($ipd->ipd_no ?? 'N/A') . ' - ' . ($patient->patient_name ?? 'N/A'),
                ];
            }
        }

        return response()->json(['data' => $results]);
    }

    /**
     * API: Get final bill number for a patient (IPD only)
     */
    public function getPatientFinalBill(Request $request)
    {
        $patientId = $request->get('patient_id');
        
        if (!$patientId) {
            return response()->json(['final_bill_no' => null, 'bill_type' => null, 'bill_id' => null]);
        }
        
        // Check for IPD final bill (discharged IPD)
        $ipd = IpdDetail::with('duePatientPartyDoctor')
            ->where('patient_id', $patientId)
            ->where('discharged', 'yes')
            ->orderBy('discharged_date', 'desc')
            ->orderBy('date', 'desc')
            ->first();
        
        if ($ipd) {
            return response()->json([
                'final_bill_no' => $ipd->ipd_no,
                'bill_type' => 'IPD',
                'bill_id' => $ipd->id,
                'doctor_charges' => $ipd->due_patient_party_amount ?? 0,
                'doctor_name' => $ipd->duePatientPartyDoctor ? 
                    ($ipd->duePatientPartyDoctor->name ?? '') . ' ' . ($ipd->duePatientPartyDoctor->surname ?? '') : '',
            ]);
        }
        
        return response()->json(['final_bill_no' => null, 'bill_type' => null, 'bill_id' => null]);
    }

    /**
     * API: Get IPD details including doctor charges for due patient party
     */
    public function getIpdDetails($ipdId)
    {
        $ipd = IpdDetail::with(['patient', 'duePatientPartyDoctor'])
            ->findOrFail($ipdId);

        $doctorCharges = 0;
        $doctorName = '';
        if ($ipd->due_patient_party_amount > 0 && $ipd->duePatientPartyDoctor) {
            $doctorCharges = $ipd->due_patient_party_amount;
            $doctorName = ($ipd->duePatientPartyDoctor->name ?? '') . ' ' . ($ipd->duePatientPartyDoctor->surname ?? '');
        }

        return response()->json([
            'ipd_no' => $ipd->ipd_no,
            'patient_id' => $ipd->patient_id,
            'patient_name' => $ipd->patient->patient_name ?? '',
            'phone' => $ipd->patient->mobileno ?? '',
            'doctor_charges' => $doctorCharges,
            'doctor_name' => $doctorName,
        ]);
    }

    /**
     * API: Get next receipt number based on payment date
     */
    public function getNextReceiptNo(Request $request)
    {
        $paymentDate = $request->get('payment_date', now());
        $nextReceiptNo = Transaction::generateReceiptNo($paymentDate);
        
        return response()->json(['receipt_no' => $nextReceiptNo]);
    }
}
