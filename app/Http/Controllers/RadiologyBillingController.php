<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RadiologyBilling;
use App\Models\RadiologyReport;
use App\Models\Radio;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\CaseReference;
use App\Models\OpdDetail;
use App\Models\Organisation;
use App\Models\OrganisationsCharge;
use App\Services\BillingTpaHelper;
use App\Services\InsuranceBillingRateResolver;
use App\Models\IpdPrescription;
use App\Models\IpdDetail;
use App\Models\IpdPrescriptionTest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RadiologyBillingController extends Controller
{
    /**
     * Display a listing of radiology bills
     */
    public function index(Request $request)
    {

    $perPage = (int) $request->input('perPage', 10);
    if ($perPage <= 0) {
        $perPage = 10;
    }

    $search = $request->input('search');
    $query = RadiologyBilling::with(['patient', 'doctor', 'organisation'])
        ->orderByDesc('date')
        ->orderByDesc('id');

    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('doctor_name', 'like', "%{$search}%");
        });
    }

    $bills = $query->paginate($perPage);

    //     return response()->json([
    //     'status' => true,
    //     'message' => 'Staff list fetched successfully',
    //     'data' => $bills
    // ]);
       return view('admin.radiology.billing.index', compact('bills'));
    }

    /**
     * Show the form for creating a new radiology bill.
     * OPD and non-discharged IPD patients are allowed.
     */
    public function create()
    {
        $patients = Patient::select('id', 'patient_name', 'mobileno')->get();
        $doctors = Doctor::select('id', 'name', 'surname', 'doctor_id')
            ->where(function($query) {
                $query->where('is_active', true)
                      ->orWhere('is_active', 1)
                      ->orWhere('is_active', 'yes');
            })
            ->get();
        $tests = Radio::with(['radiologyCategory'])->get();
        
        return view('admin.radiology.billing.create', compact('patients', 'doctors', 'tests'));
    }

    /**
     * Store a newly created radiology bill
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            // Case reference must be selected (generic case / prescription ID)
            'case_reference_id' => 'required',
            'doctor_id' => 'nullable|exists:doctor,id',
            'doctor_name' => 'nullable|string|max:100',
            'date' => 'required|date',
            'total' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
            'tax' => 'nullable|numeric|min:0',
            'net_amount' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'organisation_id' => 'nullable|exists:organisation,id',
            'activate_tpa' => 'nullable',
            'tests' => 'required|array|min:1',
            'tests.*.report_id' => 'nullable|integer|exists:radiology_report,id',
            'tests.*.radiology_id' => 'required|exists:radio,id',
            'tests.*.prescription_test_id' => 'nullable|exists:ipd_prescription_test,id',
            'tests.*.report_days' => 'required|integer|min:0',
            'tests.*.report_date' => 'required|date',
            'tests.*.tax_percentage' => 'nullable|numeric|min:0',
            'tests.*.amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        
        try {
            $user = Auth::user();
            
            // Get doctor name if doctor_id is provided
            $doctorName = $validated['doctor_name'] ?? null;
            if (!$doctorName && $validated['doctor_id']) {
                $doctor = Doctor::find($validated['doctor_id']);
                if ($doctor) {
                    $name = trim($doctor->name . ' ' . ($doctor->surname ?? ''));
                    // Only add "Dr." prefix if it's not already there
                    $doctorName = (stripos($name, 'Dr.') === 0) ? $name : 'Dr. ' . $name;
                }
            }
            
            // Truncate doctor name to fit database column (limit to 8 chars - column appears to be very short)
            // Store truncated version for reports
            $truncatedDoctorName = $doctorName ? mb_substr(trim($doctorName), 0, 8) : '';
            
            // Create radiology bill
            $bill = RadiologyBilling::create([
                'date' => $validated['date'],
                'patient_id' => $validated['patient_id'],
                'case_reference_id' => $validated['case_reference_id'] ?? null,
                'doctor_id' => $validated['doctor_id'] ?? null,
                'doctor_name' => $doctorName ?? '',
                'total' => $validated['total'],
                'discount_percentage' => $validated['discount_percentage'] ?? 0,
                'discount' => $validated['discount'] ?? 0,
                'tax_percentage' => $validated['tax_percentage'] ?? 0,
                'tax' => $validated['tax'] ?? 0,
                'net_amount' => $validated['net_amount'],
                'note' => $validated['note'] ?? null,
                'organisation_id' => ($request->has('activate_tpa') && $request->activate_tpa) ? ($validated['organisation_id'] ?? null) : null,
                'generated_by' => Auth::id(),
            ] + RadiologyBilling::billVisibilityFromRequest($request));

            // Create radiology reports with instance tracking
            foreach ($validated['tests'] as $test) {
                $prescriptionTestInstance = null;
                $instanceNumber = null;
                $customerType = 'OPD';
                
                // If prescription_test_id is provided, link to it
                if (!empty($test['prescription_test_id'])) {
                    $prescriptionTestInstance = \App\Models\IpdPrescriptionTest::find($test['prescription_test_id']);
                    if ($prescriptionTestInstance) {
                        $instanceNumber = $prescriptionTestInstance->instance_number;
                        $customerType = 'IPD'; // If linked to prescription, it's IPD
                    }
                }
                
                RadiologyReport::create([
                    'radiology_bill_id' => $bill->id,
                    'radiology_id' => $test['radiology_id'],
                    'ipd_prescription_test_id' => $prescriptionTestInstance?->id,
                    'instance_number' => $instanceNumber,
                    'patient_id' => $validated['patient_id'],
                    'reporting_date' => $test['report_date'],
                    'tax_percentage' => $test['tax_percentage'] ?? 0,
                    'apply_charge' => $test['amount'],
                    'customer_type' => $customerType,
                    'consultant_doctor' => $truncatedDoctorName,
                    'radiology_center' => '', // Required field, set to empty string
                ]);
            }

            DB::commit();
            
            return redirect()->route('radiology.billing.index')
                ->with('success', 'Radiology bill created successfully!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            \Log::error('Validation error creating radiology bill: ' . json_encode($e->errors()));
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Please check the form for errors.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating radiology bill: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Request data: ' . json_encode($request->all()));
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating radiology bill: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified radiology bill
     */
    public function show($id)
    {
        $bill = RadiologyBilling::with(['patient', 'doctor', 'reports.radiology', 'organisation'])
            ->findOrFail($id);
        
        return view('admin.radiology.billing.show', compact('bill'));
    }

    /**
     * Show the form for editing the specified radiology bill
     */
    public function edit($id)
    {
        $bill = RadiologyBilling::with([
            'patient.organisation',
            'doctor',
            'reports.radiology',
            'reports.prescriptionTestInstance.prescription',
            'organisation',
            'prescription',
        ])->findOrFail($id);
        $patients = Patient::select('id', 'patient_name', 'mobileno')->get();
        $doctors = Doctor::select('id', 'name', 'surname', 'doctor_id')
            ->where(function($query) {
                $query->where('is_active', true)
                      ->orWhere('is_active', 1)
                      ->orWhere('is_active', 'yes');
            })
            ->get();
        $tests = Radio::with(['radiologyCategory'])->get();
        
        $linkedPrescription = $bill->prescription
            ?? collect($bill->reports)->first(fn ($report) => $report->prescriptionTestInstance?->prescription)
                ?->prescriptionTestInstance?->prescription
            ?? $this->inferLegacyPrescription($bill);
        $caseReferenceId = $bill->case_reference_id ?? $linkedPrescription?->id;
        $prescriptionNumber = $linkedPrescription
            ? ($linkedPrescription->prescription_number ?? 'IPDP' . str_pad($linkedPrescription->id, 4, '0', STR_PAD_LEFT))
            : (OpdDetail::find($caseReferenceId)?->opd_no ?? '');
        
        return view('admin.radiology.billing.edit', compact(
            'bill',
            'patients',
            'doctors',
            'tests',
            'prescriptionNumber',
            'caseReferenceId'
        ));
    }

    /**
     * Recover an unambiguous prescription link for legacy bills.
     */
    private function inferLegacyPrescription(RadiologyBilling $bill): ?IpdPrescription
    {
        $reportDates = collect($bill->reports)
            ->pluck('reporting_date')
            ->filter()
            ->map(fn ($date) => date('Y-m-d', strtotime($date)))
            ->unique()
            ->values();
        $testIds = collect($bill->reports)->pluck('radiology_id')->filter()->unique()->values();

        if ($reportDates->count() !== 1 || $testIds->isEmpty()) {
            return null;
        }

        $query = IpdPrescription::whereHas(
            'ipd',
            fn ($ipdQuery) => $ipdQuery->where('patient_id', $bill->patient_id)
        )->where('date', $reportDates->first());

        foreach ($testIds as $testId) {
            $query->whereHas('tests', fn ($testQuery) => $testQuery->where('radiology_id', $testId));
        }

        $candidates = $query->limit(2)->get();

        return $candidates->count() === 1 ? $candidates->first() : null;
    }

    /**
     * Update the specified radiology bill
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            // Case reference must be selected (generic case / prescription ID)
            'case_reference_id' => 'required',
            'doctor_id' => 'nullable|exists:doctor,id',
            'doctor_name' => 'nullable|string|max:100',
            'date' => 'required|date',
            'total' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
            'tax' => 'nullable|numeric|min:0',
            'net_amount' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'organisation_id' => 'nullable|exists:organisation,id',
            'activate_tpa' => 'nullable',
            'tests' => 'required|array|min:1',
            'tests.*.report_id' => 'nullable|integer|exists:radiology_report,id',
            'tests.*.radiology_id' => 'required|exists:radio,id',
            'tests.*.prescription_test_id' => 'nullable|exists:ipd_prescription_test,id',
            'tests.*.report_days' => 'required|integer|min:0',
            'tests.*.report_date' => 'required|date',
            'tests.*.tax_percentage' => 'nullable|numeric|min:0',
            'tests.*.amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        
        try {
            $bill = RadiologyBilling::findOrFail($id);

            // Derive totals from submitted editable test amounts.
            $total = collect($validated['tests'])->sum(fn ($test) => (float) $test['amount']);
            $tax = collect($validated['tests'])->sum(function ($test) {
                return (float) $test['amount'] * (float) ($test['tax_percentage'] ?? 0) / 100;
            });
            $discountPercentage = (float) ($validated['discount_percentage'] ?? 0);
            $discount = $total * $discountPercentage / 100;
            $netAmount = $total - $discount + $tax;
            
            // Get doctor name if doctor_id is provided
            $doctorName = $validated['doctor_name'] ?? null;
            if (!$doctorName && $validated['doctor_id']) {
                $doctor = Doctor::find($validated['doctor_id']);
                if ($doctor) {
                    $name = trim($doctor->name . ' ' . ($doctor->surname ?? ''));
                    // Only add "Dr." prefix if it's not already there
                    $doctorName = (stripos($name, 'Dr.') === 0) ? $name : 'Dr. ' . $name;
                }
            }
            
            // Truncate doctor name to fit database column (limit to 8 chars - column appears to be very short)
            // Store truncated version for reports
            $truncatedDoctorName = $doctorName ? mb_substr(trim($doctorName), 0, 8) : '';
            
            // Update radiology bill
            $bill->update([
                'date' => $validated['date'],
                'patient_id' => $validated['patient_id'],
                'case_reference_id' => $validated['case_reference_id'] ?? null,
                'doctor_id' => $validated['doctor_id'] ?? null,
                'doctor_name' => $doctorName ?? '',
                'total' => round($total, 2),
                'discount_percentage' => $discountPercentage,
                'discount' => round($discount, 2),
                'tax_percentage' => $validated['tax_percentage'] ?? 0,
                'tax' => round($tax, 2),
                'net_amount' => round($netAmount, 2),
                'note' => $validated['note'] ?? null,
                'organisation_id' => ($request->has('activate_tpa') && $request->activate_tpa) ? ($validated['organisation_id'] ?? null) : null,
            ] + RadiologyBilling::billVisibilityFromRequest($request));

            // Update existing rows in place so report results/parameters remain linked.
            $retainedReportIds = [];
            foreach ($validated['tests'] as $test) {
                $report = !empty($test['report_id'])
                    ? RadiologyReport::where('radiology_bill_id', $bill->id)->find($test['report_id'])
                    : null;

                $prescriptionTestInstance = $report?->prescriptionTestInstance;
                if (!empty($test['prescription_test_id'])) {
                    $prescriptionTestInstance = IpdPrescriptionTest::find($test['prescription_test_id']);
                }

                $values = [
                    'radiology_bill_id' => $bill->id,
                    'radiology_id' => $test['radiology_id'],
                    'ipd_prescription_test_id' => $prescriptionTestInstance?->id,
                    'instance_number' => $prescriptionTestInstance?->instance_number ?? $report?->instance_number,
                    'patient_id' => $validated['patient_id'],
                    'reporting_date' => $test['report_date'],
                    'tax_percentage' => $test['tax_percentage'] ?? 0,
                    'apply_charge' => $test['amount'],
                    'customer_type' => $prescriptionTestInstance ? 'IPD' : ($report?->customer_type ?? 'OPD'),
                    'consultant_doctor' => $truncatedDoctorName,
                    'radiology_center' => '', // Required field, set to empty string
                ];

                if ($report) {
                    // Preserve an existing centre value while changing only billing fields.
                    unset($values['radiology_center']);
                    $report->update($values);
                } else {
                    $report = RadiologyReport::create($values);
                }
                $retainedReportIds[] = $report->id;
            }

            RadiologyReport::where('radiology_bill_id', $bill->id)
                ->whereNotIn('id', $retainedReportIds)
                ->delete();

            DB::commit();
            
            return redirect()->route('radiology.billing.index')
                ->with('success', 'Radiology bill updated successfully!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            \Log::error('Validation error updating radiology bill: ' . json_encode($e->errors()));
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Please check the form for errors.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating radiology bill: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Request data: ' . json_encode($request->all()));
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating radiology bill: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified radiology bill
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $bill = RadiologyBilling::findOrFail($id);
            
            // Delete related reports
            RadiologyReport::where('radiology_bill_id', $bill->id)->delete();
            
            // Delete bill
            $bill->delete();
            
            DB::commit();
            
            return redirect()->route('radiology.billing.index')
                ->with('success', 'Radiology bill deleted successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error deleting radiology bill: ' . $e->getMessage());
        }
    }

    /**
     * Quick toggle for a single IPD bill-visibility flag (radiology bill list).
     */
    public function updateBillVisibility(Request $request, $id)
    {
        $validated = $request->validate([
            'field' => 'required|in:show_on_approval_bill,show_on_approval_preview,show_on_final_preview,show_on_final_bill',
            'value' => 'required|boolean',
        ]);

        $bill = RadiologyBilling::findOrFail($id);
        $bill->update([
            $validated['field'] => (bool) $validated['value'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bill visibility updated.',
            'data' => [
                'id' => $bill->id,
                'field' => $validated['field'],
                'value' => (bool) $validated['value'],
            ],
        ]);
    }

    /**
     * API: Get patient prescriptions (OPD Visits and IPD Prescriptions)
     */
    public function getPatientPrescriptions($patientId)
    {
        try {
            \Log::info('Getting prescriptions for patient ID: ' . $patientId);

            // Case/prescription IDs already billed for this patient (do not show again)
            $billedCaseRefIds = RadiologyBilling::where('patient_id', $patientId)
                ->whereNotNull('case_reference_id')
                ->pluck('case_reference_id')
                ->map(function ($id) {
                    return (int) $id;
                })
                ->unique()
                ->values()
                ->all();
            // When editing, allow current bill's case to still appear in the list
            $showCaseRefId = request()->get('show_case_ref_id');
            if ($showCaseRefId !== null && $showCaseRefId !== '') {
                $showCaseRefId = (int) $showCaseRefId;
                $billedCaseRefIds = array_values(array_diff($billedCaseRefIds, [$showCaseRefId]));
            }
            
            $prescriptions = collect();
            
            // Get OPD Visits (exclude already billed). Older installations may not
            // have the OPD table yet, but IPD prescriptions must remain available.
            $opdVisits = collect();
            if (\Illuminate\Support\Facades\Schema::hasTable('opd_details')) {
                $opdQuery = OpdDetail::where('patient_id', $patientId);
                if (!empty($billedCaseRefIds)) {
                    $opdQuery->whereNotIn('id', $billedCaseRefIds);
                }
                $opdVisits = $opdQuery
                    ->with('doctor')
                    ->orderBy('appointment_date', 'desc')
                    ->get()
                    ->map(function($opd) {
                        return [
                            'id' => $opd->id,
                            'case_id' => $opd->opd_no ?? 'OPD' . str_pad($opd->id, 4, '0', STR_PAD_LEFT),
                            'date' => $opd->appointment_date ? date('Y-m-d', strtotime($opd->appointment_date)) : null,
                            'symptoms' => $opd->symptoms_description ?? $opd->symptoms_title ?? '',
                            'doctor' => $opd->doctor ? $opd->doctor->name : null,
                            'type' => 'opd',
                        ];
                    });
            }
            
            $prescriptions = $prescriptions->merge($opdVisits);
            \Log::info('OPD Visits found: ' . $opdVisits->count());
            
            // Get IPD Prescriptions (exclude already billed)
            $ipdQuery = IpdPrescription::join('ipd_details', 'ipd_prescription.ipd_id', '=', 'ipd_details.id')
                ->where('ipd_details.patient_id', $patientId);
            if (!empty($billedCaseRefIds)) {
                $ipdQuery->whereNotIn('ipd_prescription.id', $billedCaseRefIds);
            }
            $ipdPrescriptions = $ipdQuery
                ->select('ipd_prescription.*')
                ->with(['ipd', 'prescribedBy'])
                ->orderBy('ipd_prescription.date', 'desc')
                ->get()
                ->map(function($prescription) {
                    return [
                        'id' => $prescription->id,
                        'case_id' => $prescription->prescription_number ?? 'IPDP' . str_pad($prescription->id, 4, '0', STR_PAD_LEFT),
                        'date' => $prescription->date ? $prescription->date->format('Y-m-d') : null,
                        'symptoms' => $prescription->finding_description ?? '',
                        'doctor' => $prescription->prescribedBy ? $prescription->prescribedBy->name : null,
                        'type' => 'ipd',
                        'prescription_id' => $prescription->id,
                        'ipd_id' => $prescription->ipd_id,
                    ];
                });
            
            $prescriptions = $prescriptions->merge($ipdPrescriptions);
            \Log::info('IPD Prescriptions found: ' . $ipdPrescriptions->count());
            \Log::info('Total prescriptions: ' . $prescriptions->count());
            
            return response()->json($prescriptions->values());
        } catch (\Exception $e) {
            \Log::error('Error getting patient prescriptions: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * API: Get radiology test details
     */
    public function getTestDetails(Request $request)
    {
        $testId = $request->input('test_id');
        $customerType = $request->input('customer_type', 'OPD'); // Default to OPD if not provided
        
        $test = Radio::find($testId);
        
        if (!$test) {
            return response()->json(['error' => 'Test not found'], 404);
        }
        
        // Select the appropriate charge based on customer type
        $amount = ($customerType === 'IPD') ? ($test->standard_charge_ipd ?? 0) : ($test->standard_charge_opd ?? 0);
        
        return response()->json([
            'id' => $test->id,
            'test_name' => $test->test_name,
            'report_days' => $test->report_days,
            'tax_percentage' => 0, // Tax is now handled separately in billing
            'amount' => $amount,
            'standard_charge_ipd' => $test->standard_charge_ipd ?? 0,
            'standard_charge_opd' => $test->standard_charge_opd ?? 0,
        ]);
    }

    /**
     * API: Get TPA names for a patient from previous radiology bills, IPD records, and Patient record
     */
    /**
     * API: Get TPA names for a patient — prefers latest IPD admission TPA + insurance.
     */
    public function getPatientTpas($patientId, Request $request)
    {
        try {
            $ipdId = $request->query('ipd_id') ? (int) $request->query('ipd_id') : null;
            $uniqueTpas = BillingTpaHelper::collectPatientTpas((int) $patientId, 'radiology', $ipdId);

            return response()->json($uniqueTpas);
        } catch (\Exception $e) {
            \Log::error('Error getting patient TPAs: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * API: Get TPA charge for a specific test and TPA
     */
    public function getTpaCharge(Request $request, InsuranceBillingRateResolver $rateResolver)
    {
        $testId = $request->input('test_id');
        $organisationId = $request->input('organisation_id');
        $insuranceCompanyId = BillingTpaHelper::resolveInsuranceCompanyId(
            $organisationId ? (int) $organisationId : null,
            $request->input('insurance_company_id') ? (int) $request->input('insurance_company_id') : null
        );

        if (!$request->filled('insurance_company_id') && $request->filled('patient_id') && $organisationId) {
            $latestIpd = BillingTpaHelper::latestIpdForPatient(
                (int) $request->input('patient_id'),
                $request->input('ipd_id') ? (int) $request->input('ipd_id') : null
            );
            if ($latestIpd
                && (int) $latestIpd->organisation_id === (int) $organisationId
                && $latestIpd->insurance_company_id
            ) {
                $insuranceCompanyId = (int) $latestIpd->insurance_company_id;
            }
        }

        $customerType = $request->input('customer_type', 'OPD');

        if (!$testId) {
            return response()->json(['error' => 'Test ID is required'], 400);
        }

        if (!Radio::find($testId)) {
            return response()->json(['error' => 'Test not found'], 404);
        }

        $resolved = $rateResolver->resolveRadiology(
            (int) $testId,
            $insuranceCompanyId ? (int) $insuranceCompanyId : null,
            $organisationId ? (int) $organisationId : null,
            $customerType
        );

        $charge = (float) $resolved['rate'];
        $standardCharge = (float) ($resolved['standard_rate'] ?? 0);

        return response()->json([
            'tpa_charge_ipd' => ($customerType === 'IPD') ? $charge : null,
            'tpa_charge_opd' => ($customerType === 'OPD') ? $charge : null,
            'tpa_charge' => $charge,
            'standard_charge' => $standardCharge,
            'rate_source' => $resolved['source'],
            'insurer_test_name' => $resolved['insurer_test_name'],
            'insurance_test_rate_id' => $resolved['insurance_test_rate_id'],
        ]);
    }

    /**
     * API: Get prescription tests (radiology tests from IPD prescription)
     */
    public function getPrescriptionTests($prescriptionId)
    {
        $prescription = IpdPrescription::with([
            'tests.radiology',
            'ipd.patient.organisation.insuranceCompany',
            'ipd.organisation.insuranceCompany',
        ])
            ->find($prescriptionId);
        
        if (!$prescription) {
            return response()->json(['error' => 'Prescription not found'], 404);
        }
        
        // Get radiology tests from the prescription
        // Determine if this is IPD or OPD based on prescription type
        $isIpd = $prescription->ipd !== null;
        $customerType = $isIpd ? 'IPD' : 'OPD';
        
        $radiologyTests = $prescription->tests()
            ->whereNotNull('radiology_id')
            ->with('radiology')
            ->get()
            ->filter(function($test) {
                return $test->radiology !== null;
            })
            ->map(function($test) use ($isIpd) {
                $radiology = $test->radiology;
                // Use IPD charge for IPD prescriptions, OPD charge for OPD
                $standardCharge = $isIpd ? ($radiology->standard_charge_ipd ?? 0) : ($radiology->standard_charge_opd ?? 0);
                $amount = $test->amount ?? $standardCharge;
                
                // Get instance number and format display
                $instanceNumber = $test->instance_number ?? 1;
                $instanceSuffix = $instanceNumber > 1 
                    ? ($instanceNumber == 2 ? ' (2nd time)' : ($instanceNumber == 3 ? ' (3rd time)' : " ({$instanceNumber}th time)"))
                    : '';
                
                return [
                    'id' => $radiology->id,
                    'prescription_test_id' => $test->id, // Include prescription test instance ID
                    'instance_number' => $instanceNumber,
                    'test_name' => $radiology->test_name . $instanceSuffix,
                    'test_name_base' => $radiology->test_name, // Base name without instance suffix
                    'report_days' => $radiology->report_days ?? 0,
                    'tax_percentage' => 0, // Tax is handled separately in billing
                    'amount' => $amount,
                    'standard_charge_ipd' => $radiology->standard_charge_ipd ?? 0,
                    'standard_charge_opd' => $radiology->standard_charge_opd ?? 0,
                    'notes' => $test->notes ?? null,
                ];
            });
        
        // Prefer this IPD admission's TPA + insurance (not stale patient master / TPA default)
        $tpa = BillingTpaHelper::tpaFromPrescriptionIpd($prescription->ipd);
        
        // Get full doctor name with surname
        $doctorName = null;
        $doctorId = null;
        if ($prescription->prescribedBy) {
            $name = trim($prescription->prescribedBy->name . ' ' . ($prescription->prescribedBy->surname ?? ''));
            $doctorName = $name;
            $doctorId = $prescription->prescribedBy->id;
        }
        
        return response()->json([
            'prescription' => [
                'id' => $prescription->id,
                'prescription_number' => $prescription->prescription_number,
                'date' => $prescription->date ? $prescription->date->format('Y-m-d') : null,
                'doctor' => $doctorName,
                'doctor_id' => $doctorId,
            ],
            'tests' => $radiologyTests,
            'tpa' => $tpa,
        ]);
    }
}

