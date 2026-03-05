<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\IpdDetail;
use App\Models\OpdDetail;
use App\Models\OpdPrescription;
use App\Models\IpdPrescription;
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

        $receiptTypes = ['Current', 'Patient Due', 'Corporate Due', 'In Admissible', 'Booking', 'Refund', 'OPD Doctor Consultation', 'OPD Pathology', 'OPD Radiology', 'IPD Pathology', 'IPD Radiology'];

        return view('admin.money-receipt.index', compact('receipts', 'perPage', 'receiptTypes'));
    }

    /**
     * Show form for creating new money receipt
     */
    public function create()
    {
        $receiptTypes = ['Current', 'Patient Due', 'Corporate Due', 'In Admissible', 'Booking', 'Refund', 'OPD Doctor Consultation', 'OPD Pathology', 'OPD Radiology', 'IPD Pathology', 'IPD Radiology'];
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
            'receipt_type' => 'required|in:Current,Patient Due,Corporate Due,In Admissible,Booking,Refund,OPD Doctor Consultation,OPD Pathology,OPD Radiology,IPD Pathology,IPD Radiology',
            'amount' => 'required|numeric|min:0.01',
            'payment_mode' => 'required|string',
            'patient_id' => 'nullable|integer|exists:patients,id',
            'ipd_id' => 'nullable|integer|exists:ipd_details,id',
            'final_bill_no' => 'nullable|string',
        ]);

        // Generate receipt number based on payment date (financial year)
        $paymentDate = $request->payment_date ?? now();
        $receiptNo = Transaction::generateReceiptNo($paymentDate);

        // Determine if this is an OPD or IPD receipt type
        $isOpdReceiptType = in_array($request->receipt_type, ['OPD Doctor Consultation', 'OPD Pathology', 'OPD Radiology'], true);
        $isIpdReceiptType = in_array($request->receipt_type, ['IPD Pathology', 'IPD Radiology'], true);

        // Get IPD/OPD and final bill number
        $ipd = null;
        $opd = null;
        $finalBillNo = $request->final_bill_no;
        
        if ($request->ipd_id) {
            $ipd = IpdDetail::find($request->ipd_id);
            $finalBillNo = $ipd ? $ipd->ipd_no : $finalBillNo;
        } elseif ($request->opd_id) {
            $opd = OpdDetail::find($request->opd_id);
            $finalBillNo = $opd ? $opd->opd_no : $finalBillNo;
        } elseif ($finalBillNo) {
            // Try IPD first
            $ipd = IpdDetail::where('ipd_no', $finalBillNo)->first();
            if (!$ipd) {
                // Try OPD
                $opd = OpdDetail::where('opd_no', $finalBillNo)->first();
            }
        }

        // For receipt types that must be linked to a final bill/IPD, enforce linkage
        if (in_array($request->receipt_type, ['Patient Due', 'Refund'], true) && !$ipd) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['final_bill_no' => 'For Receipt Type "' . $request->receipt_type . '", an IPD / Final Bill must be selected.']);
        }

        // For OPD receipt types, require OPD linkage
        if ($isOpdReceiptType && !$opd && !$request->opd_id) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['final_bill_no' => 'For Receipt Type "' . $request->receipt_type . '", an OPD record must be selected.']);
        }

        // For IPD receipt types (Pathology/Radiology), require IPD linkage
        if ($isIpdReceiptType && !$ipd && !$request->ipd_id) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['final_bill_no' => 'For Receipt Type "' . $request->receipt_type . '", an IPD record must be selected.']);
        }

        // Get hospital and branch IDs
        $hospitalId = '1';
        $branchId = '1';
        if ($ipd) {
            $hospitalId = $ipd->hospital_id ?? '1';
            $branchId = $ipd->branch_id ?? '1';
        } elseif ($opd) {
            $hospitalId = $opd->hospital_id ?? '1';
            $branchId = $opd->branch_id ?? '1';
        }

        // Determine patient_id
        $patientId = $request->patient_id;
        if (!$patientId && $ipd) {
            $patientId = $ipd->patient_id;
        } elseif (!$patientId && $opd) {
            $patientId = $opd->patient_id;
        }

        // Determine section based on receipt type
        $section = 'money_receipt';
        if ($ipd || $isIpdReceiptType) {
            $section = 'ipd';
        } elseif ($opd || $isOpdReceiptType) {
            $section = 'opd';
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
            'payment_reference' => $request->payment_reference ?? null,
            'note' => $request->note ?? null,
            'patient_id' => $patientId,
            'ipd_id' => $ipd ? $ipd->id : null,
            'opd_id' => $opd ? $opd->id : null,
            'type' => 'payment',
            'section' => $section,
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
        $receipt = Transaction::with(['patient', 'ipd', 'opd', 'receiver', 'ipd.duePatientPartyDoctor'])
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
        $receipt = Transaction::with(['patient', 'ipd', 'opd'])
            ->whereNotNull('receipt_no')
            ->findOrFail($id);

        $receiptTypes = ['Current', 'Patient Due', 'Corporate Due', 'In Admissible', 'Booking', 'Refund', 'OPD Doctor Consultation', 'OPD Pathology', 'OPD Radiology', 'IPD Pathology', 'IPD Radiology'];
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
            'receipt_type' => 'required|in:Current,Patient Due,Corporate Due,In Admissible,Booking,Refund,OPD Doctor Consultation,OPD Pathology,OPD Radiology,IPD Pathology,IPD Radiology',
            'amount' => 'required|numeric|min:0.01',
            'payment_mode' => 'required|string',
            'patient_id' => 'nullable|integer|exists:patients,id',
            'ipd_id' => 'nullable|integer|exists:ipd_details,id',
            'final_bill_no' => 'nullable|string',
        ]);

        // Determine if this is an OPD or IPD receipt type
        $isOpdReceiptType = in_array($request->receipt_type, ['OPD Doctor Consultation', 'OPD Pathology', 'OPD Radiology'], true);
        $isIpdReceiptType = in_array($request->receipt_type, ['IPD Pathology', 'IPD Radiology'], true);

        // Get IPD/OPD and final bill number
        $ipd = null;
        $opd = null;
        $finalBillNo = $request->final_bill_no;
        
        if ($request->ipd_id) {
            $ipd = IpdDetail::find($request->ipd_id);
            $finalBillNo = $ipd ? $ipd->ipd_no : $finalBillNo;
        } elseif ($request->opd_id) {
            $opd = OpdDetail::find($request->opd_id);
            $finalBillNo = $opd ? $opd->opd_no : $finalBillNo;
        } elseif ($finalBillNo) {
            // Try IPD first
            $ipd = IpdDetail::where('ipd_no', $finalBillNo)->first();
            if (!$ipd) {
                // Try OPD
                $opd = OpdDetail::where('opd_no', $finalBillNo)->first();
            }
        }

        // Determine patient_id
        $patientId = $request->patient_id;
        if (!$patientId && $ipd) {
            $patientId = $ipd->patient_id;
        } elseif (!$patientId && $opd) {
            $patientId = $opd->patient_id;
        }

        // Determine section based on receipt type
        $section = 'money_receipt';
        if ($ipd || $isIpdReceiptType) {
            $section = 'ipd';
        } elseif ($opd || $isOpdReceiptType) {
            $section = 'opd';
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
            'payment_reference' => $request->payment_reference ?? null,
            'note' => $request->note ?? null,
            'patient_id' => $patientId,
            'ipd_id' => $ipd ? $ipd->id : null,
            'opd_id' => $opd ? $opd->id : null,
            'section' => $section,
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

        $isRefund = strcasecmp($receipt->receipt_type ?? '', 'Refund') === 0;
        $baseName = $isRefund ? 'Refund_Receipt_' : 'Money_Receipt_';
        $fileName = $baseName . $receipt->receipt_no . '.pdf';

        return $pdf->download($fileName);
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
     * API: Search patient (by IPD/OPD number, patient name, phone)
     */
    public function searchPatient(Request $request)
    {
        $search = $request->get('search', '');
        
        // Log for debugging
        \Log::info('Patient search called', ['search' => $search]);
        
        $results = [];
        
        if (!empty($search) && strlen($search) >= 2) {
            // Escape special characters for LIKE query
            $searchEscaped = str_replace(['%', '_'], ['\%', '\_'], $search);
            
            // Search IPD patients
            $ipds = IpdDetail::with('patient')
                ->where(function($q) use ($searchEscaped) {
                    $q->where('ipd_no', 'LIKE', "%{$searchEscaped}%")
                      ->orWhereHas('patient', function($subQ) use ($searchEscaped) {
                          $subQ->where('patient_name', 'LIKE', "%{$searchEscaped}%")
                               ->orWhere('mobileno', 'LIKE', "%{$searchEscaped}%");
                      });
                })
                ->orderBy('date', 'desc')
                ->limit(50)
                ->get();
            
            foreach ($ipds as $ipd) {
                if ($ipd->patient) {
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
                        'display_text' => ($ipd->ipd_no ?? 'N/A') . ' - ' . ($patient->patient_name ?? 'N/A') . ' (IPD)',
                    ];
                }
            }

            // Search OPD patients
            $opds = OpdDetail::with('patient')
                ->where(function($q) use ($searchEscaped) {
                    $q->where('opd_no', 'LIKE', "%{$searchEscaped}%")
                      ->orWhereHas('patient', function($subQ) use ($searchEscaped) {
                          $subQ->where('patient_name', 'LIKE', "%{$searchEscaped}%")
                               ->orWhere('mobileno', 'LIKE', "%{$searchEscaped}%");
                      });
                })
                ->orderBy('appointment_date', 'desc')
                ->limit(50)
                ->get();
            
            foreach ($opds as $opd) {
                if ($opd->patient) {
                    $patient = $opd->patient;
                    $results[] = [
                        'id' => $opd->patient_id,
                        'patient_name' => $patient->patient_name ?? 'N/A',
                        'phone' => $patient->mobileno ?? '',
                        'address' => $patient->address ?? '',
                        'age' => $patient->age ?? '',
                        'gender' => $patient->gender ?? '',
                        'marital_status' => $patient->marital_status ?? '',
                        'guardian_name' => $patient->guardian_name ?? '',
                        'area' => $patient->area ?? '',
                        'bill_type' => 'OPD',
                        'bill_no' => $opd->opd_no,
                        'bill_id' => $opd->id,
                        'display_text' => ($opd->opd_no ?? 'N/A') . ' - ' . ($patient->patient_name ?? 'N/A') . ' (OPD)',
                    ];
                }
            }

            // Also search patients directly (in case they don't have IPD/OPD records yet)
            $directPatients = Patient::where(function($q) use ($searchEscaped) {
                    $q->where('patient_name', 'LIKE', "%{$searchEscaped}%")
                      ->orWhere('mobileno', 'LIKE', "%{$searchEscaped}%");
                })
                ->orderBy('id', 'desc')
                ->limit(20)
                ->get();
            
            foreach ($directPatients as $patient) {
                // Check if this patient is already in results
                $exists = false;
                foreach ($results as $result) {
                    if ($result['id'] == $patient->id) {
                        $exists = true;
                        break;
                    }
                }
                
                if (!$exists) {
                    $results[] = [
                        'id' => $patient->id,
                        'patient_name' => $patient->patient_name ?? 'N/A',
                        'phone' => $patient->mobileno ?? '',
                        'address' => $patient->address ?? '',
                        'age' => $patient->age ?? '',
                        'gender' => $patient->gender ?? '',
                        'marital_status' => $patient->marital_status ?? '',
                        'guardian_name' => $patient->guardian_name ?? '',
                        'area' => $patient->area ?? '',
                        'bill_type' => '',
                        'bill_no' => '',
                        'bill_id' => '',
                        'display_text' => ($patient->patient_name ?? 'N/A') . ' (No IPD/OPD)',
                    ];
                }
            }
        }

        // Log results for debugging
        \Log::info('Patient search results', ['count' => count($results), 'search' => $search]);
        
        return response()->json(['data' => $results]);
    }

    /**
     * API: Get final bill number for a patient (IPD or OPD)
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

        // Check for OPD (latest OPD record)
        $opd = OpdDetail::with('doctor')
            ->where('patient_id', $patientId)
            ->orderBy('appointment_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();
        
        if ($opd) {
            return response()->json([
                'final_bill_no' => $opd->opd_no,
                'bill_type' => 'OPD',
                'bill_id' => $opd->id,
                'doctor_charges' => 0,
                'doctor_name' => $opd->doctor ? 
                    ($opd->doctor->name ?? '') . ' ' . ($opd->doctor->surname ?? '') : '',
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

    /**
     * API: Get case/prescription number based on receipt type and OPD/IPD ID
     */
    public function getCasePrescriptionNo(Request $request)
    {
        $receiptType = $request->get('receipt_type');
        $opdId = $request->get('opd_id');
        $ipdId = $request->get('ipd_id');
        
        $casePrescriptionNo = null;
        
        // Receipt types that need case/prescription number
        $opdReceiptTypes = ['OPD Doctor Consultation', 'OPD Pathology', 'OPD Radiology'];
        $ipdReceiptTypes = ['IPD Pathology', 'IPD Radiology'];
        
        if (in_array($receiptType, $opdReceiptTypes) && $opdId) {
            // For OPD Doctor Consultation, get latest prescription
            if ($receiptType === 'OPD Doctor Consultation') {
                $prescription = OpdPrescription::where('opd_id', $opdId)
                    ->orderBy('date', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();
            }
            // For OPD Pathology, get prescription with pathology_id
            elseif ($receiptType === 'OPD Pathology') {
                $prescription = OpdPrescription::where('opd_id', $opdId)
                    ->whereNotNull('pathology_id')
                    ->orderBy('date', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();
            }
            // For OPD Radiology, get prescription with radiology_id
            elseif ($receiptType === 'OPD Radiology') {
                $prescription = OpdPrescription::where('opd_id', $opdId)
                    ->whereNotNull('radiology_id')
                    ->orderBy('date', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();
            }
            
            if ($prescription && $prescription->prescription_number) {
                $casePrescriptionNo = $prescription->prescription_number;
            }
        }
        elseif (in_array($receiptType, $ipdReceiptTypes) && $ipdId) {
            // For IPD Pathology, get prescription with pathology tests
            if ($receiptType === 'IPD Pathology') {
                $prescription = IpdPrescription::where('ipd_id', $ipdId)
                    ->where(function($q) {
                        $q->whereNotNull('pathology_id')
                          ->orWhereHas('pathologyTests');
                    })
                    ->orderBy('date', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();
            }
            // For IPD Radiology, get prescription with radiology tests
            elseif ($receiptType === 'IPD Radiology') {
                $prescription = IpdPrescription::where('ipd_id', $ipdId)
                    ->where(function($q) {
                        $q->whereNotNull('radiology_id')
                          ->orWhereHas('radiologyTests');
                    })
                    ->orderBy('date', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();
            }
            
            if ($prescription && $prescription->prescription_number) {
                $casePrescriptionNo = $prescription->prescription_number;
            }
        }
        
        return response()->json(['case_prescription_no' => $casePrescriptionNo]);
    }
}
