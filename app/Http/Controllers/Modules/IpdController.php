<?php
namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Bed;
use App\Models\BedGroup;
use App\Models\Charge;
use App\Models\ChargeTypeMaster;
use App\Models\DischargeCard;
use App\Models\Doctor;
use App\Models\Finding;
use App\Models\IpdCharges;
use App\Models\IpdDaywiseBedCharge;
use App\Models\IpdDetail;
use App\Models\IpdMedicine;
use App\Models\IpdPatient;
use App\Models\IpdPrescription;
use App\Models\IpdPrescriptionTest;
use App\Models\MedicationReport;
use App\Models\NurseNote;
use App\Models\OperationTheatre;
use App\Models\Package;
use App\Models\Pathology;
use App\Models\PathologyBilling;
use App\Models\PathologyReport;
use App\Models\Patient;
use App\Models\PatientBedHistory;
use App\Models\Prefix;
use App\Models\Radio;
use App\Models\RadiologyBilling;
use App\Models\RadiologyReport;
use App\Models\Staff;
use App\Models\Symptom;
use App\Models\SymptomsClassification;
use App\Services\BedOccupancyService;
use App\Services\IpdPackageService;
use App\Services\PmsBridgeService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class IpdController extends Controller
{

    public function index(Request $request)
    {
        // Search term and pagination
        $search      = $request->get('search');
        $fromDate    = $request->get('from_date');
        $toDate      = $request->get('to_date');
        $draftFilter = $request->get('draft_filter');
        $perPage     = intval($request->input('per_page', 50));
        if ($perPage <= 0) {
            $perPage = 10;
        }
        // dd($draftFilter);
        // Determine tab
        $isIpdTab = $request->get('tab', 'ipd') == 'ipd';

        // Common data
        $doctors    = Doctor::all();
        $bedGroups  = BedGroup::with('floorDetail')->get();
        $chargeType = ChargeTypeMaster::all();
        $charges    = Charge::all();
        $references = ['Direct', 'Doctor', 'Marketer', 'Other'];

        if ($isIpdTab) {
            // Query for ongoing IPDs
            $query = IpdDetail::with('patient', 'ipdPatients', 'doctor', 'bedDetail', 'bedGroup.floorDetail')
                ->where('discharged', null)
                ->when($fromDate || $toDate, function ($query) use ($fromDate, $toDate) {
                    // IPD tab: filter by admission date stored in ipd_details.date
                    if ($fromDate) {
                        $query->whereDate('date', '>=', Carbon::parse($fromDate)->toDateString());
                    }
                    if ($toDate) {
                        $query->whereDate('date', '<=', Carbon::parse($toDate)->toDateString());
                    }
                })
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('ipd_no', 'LIKE', "%{$search}%")
                            ->orWhereHas('patient', function ($p) use ($search) {
                                $p->where('patient_name', 'LIKE', "%{$search}%")
                                    ->orWhere('mobileno', 'LIKE', "%{$search}%")
                                    ->orWhere('email', 'LIKE', "%{$search}%");
                            });
                    });
                });

            // Paginate
            $ipd = $query->paginate($perPage)->withQueryString();

            // Attach billing summary
            $billingController = app(\App\Http\Controllers\IpdBillingController::class);
            $ipd->getCollection()->transform(function ($ipdDetails) use ($billingController) {
                $summary                    = $billingController->getBillingSummaryForIpd($ipdDetails->id);
                $ipdDetails->total_payments = $summary['total_payments'];
                $ipdDetails->outstanding    = $summary['outstanding'];
                $ipdDetails->total_billing  = $summary['total_charges'];
                return $ipdDetails;
            });

        } else {
            // Query for discharged IPDs
            $query = IpdDetail::with('patient', 'ipdPatients', 'doctor')
                ->whereIn('discharged', ['yes', 'draft'])->orderByDesc('discharged_date')
                ->when($fromDate || $toDate, function ($query) use ($fromDate, $toDate) {
                    // Discharge tab: filter by discharged_date
                    if ($fromDate) {
                        $query->whereDate('discharged_date', '>=', Carbon::parse($fromDate)->toDateString());
                    }
                    if ($toDate) {
                        $query->whereDate('discharged_date', '<=', Carbon::parse($toDate)->toDateString());
                    }
                })
                ->when($search, function ($query) use ($search) {
                    $query->whereHas('patient', function ($p) use ($search) {
                        $p->where('patient_name', 'LIKE', "%{$search}%")
                            ->orWhere('mobileno', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    });
                })->when($draftFilter, function ($query) use ($draftFilter) {
                if ($draftFilter === 'yes') {
                    $query->where('discharged', 'yes');
                } elseif ($draftFilter === 'draft') {
                    $query->where('discharged', 'draft');
                }
            });

            // Paginate
            $ipd = $query->paginate($perPage)->withQueryString();
            // dd($ipd);
        }

        // Return view with paginated data
        return view("admin.ipd.index", compact(
            'ipd', 'doctors', 'isIpdTab', 'bedGroups', 'references'
        ));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'patient_id'           => 'nullable|exists:patients,id',
            'admission_date'       => 'date',
            'patient_type'         => 'string',
            'case'                 => 'nullable|numeric',
            'casualty'             => 'string',
            'reference'            => 'nullable|string',
            'doctor_id'            => 'nullable|exists:doctor,id',
            'doctor2_id'           => 'nullable|exists:doctor,id',
            'doctor3_id'           => 'nullable|exists:doctor,id',
            'doctor4_id'           => 'nullable|exists:doctor,id',
            'credit_limit'         => 'nullable|numeric|min:0',
            'live_consultation'    => 'nullable|string|max:100',
            'bed_group'            => 'nullable|exists:bed_group,id',
            'bed_number'           => 'nullable|exists:bed,id',
            'bed_charge'           => 'nullable|numeric|min:0',
            'package_id'           => 'nullable|exists:packages,id',
            'package_rate'         => 'nullable|numeric|min:0',
            'symptoms_type'        => 'nullable|array',
            'symptoms_type.*'      => 'string',
            'symptoms_title'       => 'array',
            'symptoms_title.*'     => 'string',
            'symptoms_description' => 'nullable|string',
            'note'                 => 'nullable|string',
            'apply_tpa'            => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            // dd($validator->errors()->all());
            return back()
                ->withErrors($validator)
                ->withInput();

        }

        DB::beginTransaction();
        $user = Auth::user();
        // dd($user);
        if (! $user || ! $user->hospital_id) {
            return redirect()->back()->with('error', 'User not authenticated or hospital ID missing.');
        }
        try {
            $symptomType          = array_filter($request->input('symptoms_type', []));
            $symptomTitle         = array_filter($request->input('symptoms_title', []));
            $symptomType          = array_filter($request->input('symptoms_type', []));
            $symptomTitle         = array_filter($request->input('symptoms_title', []));
            $implodedSymptomType  = implode(", ", $symptomType);
            $implodedSymptomTitle = implode(", ", $symptomTitle);

            $lastIpd = IpdDetail::orderBy('id', 'desc')->first();
            if ($lastIpd && preg_match('/IPDN(\d+)/', $lastIpd->ipd_no, $matches)) {
                $lastNumber = intval($matches[1]);
            } else {
                $lastNumber = 0;
            }
            $ipdPrefix  = Prefix::where("type", 'ipd_no')->firstOrFail();
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            $ipdNo      = $ipdPrefix->prefix . $nextNumber;
            // 🔹 Create IPD record
            $ipd        = new IpdDetail();
            $ipdPatient = new IpdPatient();
            $hasBed     = ! empty($request->bed_group) && ! empty($request->bed_number);
            $bedHistory = new PatientBedHistory();
            // dd($opd);
            $ipd->hospital_id = $user->hospital_id;
            // Patient Details
            $ipd->patient_id = $request->patient_id;
            // Doctor Details
            $ipd->cons_doctor  = $request->doctor_id;
            $ipd->cons_doctor2 = $request->doctor2_id;
            $ipd->cons_doctor3 = $request->doctor3_id;
            $ipd->cons_doctor4 = $request->doctor4_id;

            // Visit Details
            $ipd->bed_group_id = $request->bed_group;
            $ipd->bed          = $request->bed_number;
            $ipd->date         = $request->admission_date;
            $ipd->case_type    = $request->case;
            $ipd->patient_old  = $request->patient_type;
            $ipd->casualty     = $request->casualty;
            $ipd->refference   = $request->reference;

            // Billing / Payment
            $ipd->credit_limit = $request->credit_limit ?? 0;

            // Misc
            $ipd->live_consult         = $request->live_consultation;
            $ipd->symptoms_type        = $implodedSymptomType ?? "";
            $ipd->symptoms_title       = $implodedSymptomTitle ?? "";
            $ipd->symptoms_description = $request->symptoms_description;
            $ipd->note                 = $request->note;
            $ipd->generated_by         = Auth::user()->id ?? null;
            $ipd->ipd_no               = $ipdNo;
            // Save IPD Record
            $ipd->save();

            // dd($opd->id);
            $ipdPatient->patient_id = $request->patient_id ?? null;
            $ipdPatient->ipd_id     = $ipd->id ?? null;
            $ipdPatient->doctor_id  = $request->doctor_id ?? null;
            $ipdPatient->doctor2_id = $request->doctor2_id ?? null;
            $ipdPatient->doctor3_id = $request->doctor3_id ?? null;
            $ipdPatient->doctor4_id = $request->doctor4_id ?? null;

            $ipdPatient->save();

            // Patient bed history and bed occupancy (only when bed is selected; required for bed charges in billing)
            if ($hasBed) {
                $bedDetail = Bed::where('id', $request->bed_number)->first();
                if ($bedDetail) {
                    $bedHistory->hospital_id  = $ipd->hospital_id ?? $user->hospital_id ?? null;
                    $bedHistory->branch_id    = $ipd->branch_id ?? $user->branch_id ?? null;
                    $bedHistory->bed_group_id = $request->bed_group;
                    $bedHistory->ipd_id       = $ipd->id ?? null;
                    $bedHistory->bed_id       = $request->bed_number;
                    $bedHistory->from_date    = $request->admission_date;
                    $bedHistory->is_active    = 'yes';
                    $bedHistory->save();

                    $bedDetail->is_active = 'no';
                    $bedDetail->save();
                }
            }

            // Create initial bed charge entry for admission date (used by billing for estimate/final bill).
            // Bed charges are always created when a bed is selected, regardless of package selection.
            if ($hasBed && $request->admission_date) {
                $bedGroup = BedGroup::find($request->bed_group);
                // Use on-the-fly bed charge if provided, otherwise fall back to bed group master
                $bedChargeRate = $request->bed_charge !== null && $request->bed_charge !== ''
                    ? (float) $request->bed_charge
                    : (float) ($bedGroup->bed_cost ?? 0.00);

                // For a single-day entry, the daily rate and charge are the same
                $bedCharge = $bedChargeRate;

                // Always create daywise bed charge when a bed is selected (so estimate/final bill include bed charges)
                $admissionDate = Carbon::parse($request->admission_date);
                $chargeDay     = BedBillingPeriod::firstChargeCalendarDayFromAnchorDate($admissionDate);
                $chargeDate    = $chargeDay->format('Y-m-d');
                $periodDates   = BedBillingPeriod::periodStorageDatesForChargeDay($chargeDay, $admissionDate);
                $periodStartDate = $periodDates['period_start_date'];
                $periodEndDate   = $periodDates['period_end_date'];

                IpdDaywiseBedCharge::updateOrCreate(
                    [
                        'ipd_id'      => $ipd->id,
                        'charge_date' => $chargeDate,
                    ],
                    [
                        'hospital_id'       => $ipd->hospital_id,
                        'branch_id'         => $ipd->branch_id ?? null,
                        'case_reference_id' => $ipd->case_reference_id ?? null,
                        'patient_id'        => $ipd->patient_id,
                        'period_start_date' => $periodStartDate,
                        'period_end_date'   => $periodEndDate,
                        'bed_group_id'      => $request->bed_group,
                        'bed_id'            => $request->bed_number,
                        'bed_charge'        => $bedCharge,
                        'bed_charge_rate'   => $bedChargeRate,
                        'no_of_days'        => 1,
                        'is_active'         => 'yes',
                    ]
                );
            }

            // Apply package if selected during admission (with optional custom package amount)
            if ($request->package_id) {
                $packageService      = new IpdPackageService();
                $packageRateOverride = $request->filled('package_rate') ? (float) $request->package_rate : null;
                $packageResult       = $packageService->applyPackage(
                    $ipd->id,
                    $request->package_id,
                    $request->admission_date, // Apply package from admission date
                    'Applied during IPD admission',
                    $packageRateOverride
                );

                if (! $packageResult['success']) {
                    throw new \Exception('Failed to apply package: ' . $packageResult['message']);
                }
            }

            DB::commit();

            return redirect()->route('ipd')->with('success', 'IPD record created successfully . ')
                ->with('pdf_url', route('ipd.pdf', $ipdPatient->id));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->withInput()->with('error', 'Failed to save IPD record: ' . $e->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        $ipd = IpdDetail::with(['patient', 'doctor', 'bedDetail', 'bedGroup.floorDetail', 'ipdPackages.package'])
            ->where('id', $id)
            ->firstOrFail();
        $doctors = Doctor::all();

        $symptomTypeIds = array_filter(
            explode(', ', $ipd->symptoms_type),
            fn($id) => $id !== null && trim($id) !== ''
        );
        $symptomIds = array_filter(
            explode(', ', $ipd->symptoms_title),
            fn($id) => $id !== null && trim($id) !== ''
        );

        // Fetch symptoms related to this OPD
        // $symptomTypes = ! empty($symptomTypeIds)
        //     ? SymptomsClassification::whereIn('id', $symptomTypeIds)->get()
        //     : collect(); // return empty collection if no symptoms
        $symptoms = ! empty($symptomIds)
            ? Symptom::whereIn('id', $symptomIds)->get()
            : collect(); // return empty collection if no symptoms

        $allSymptomTypes = SymptomsClassification::all();

        $bedGroups  = BedGroup::with('floorDetail')->get();
        $bedNumbers = Bed::where('bed_group_id', $ipd->bed_group_id)->where('is_active', 'yes')->get();
        $patients   = Patient::with('organisation', 'bloodGroup')->get();
        // dd($patients);
        $appliedPackage = $ipd->ipdPackages()
            ->where('status', 'applied')
            ->orderByDesc('applied_date')
            ->orderByDesc('id')
            ->first();

        return view('admin.ipd.edit-ipd', compact(
            'ipd',
            'doctors',
            'symptomTypeIds',
            'allSymptomTypes',
            'symptoms',
            'bedGroups',
            'bedNumbers',
            'patients',
            'appliedPackage'
        ));

    }

    public function update(Request $request, $id, BedOccupancyService $bedOccupancyService)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'patient_id'     => 'required|exists:patients,id',
            'admission_date' => 'required|date',
            'old_patient'    => 'required|string',
            'casualty'       => 'required|string',
            'date'           => 'nullable|date',
            'package_id'     => 'nullable|exists:packages,id',
            'package_rate'   => 'nullable|numeric|min:0',
        ]);
        try {
            // $symptomTitle         = array_filter($request->symptoms_title, fn($title) => $title !== null && $title !== '');
            $symptomTitle         = array_filter($request->symptoms_title ?? [], fn($title) => $title !== null && $title !== '');
            $symptomType          = array_filter($request->symptoms_type ?? [], fn($type) => $type !== null && $type !== '');
            $implodedSymptomType  = implode(", ", $symptomType);
            $implodedSymptomTitle = implode(", ", $symptomTitle);
            // 🔹 Update OPD record
            $ipd        = IpdDetail::findOrFail($id);
            $allotedBed = $ipd->bed;
            //dd($id, IpdPatient::where('ipd_id', $id)->first());
            $ipdPatient = IpdPatient::where('ipd_id', $id)->firstOrFail();

            // Bed change or admission date change: validate occupancy for first bed period
            $newAdmissionDate = Carbon::parse($request->admission_date);
            $targetBedId      = $request->bed_number ?? $allotedBed;

            // Check availability of this bed at the new admission datetime
            // For admission edit we care about occupancy at that exact moment,
            // so use a zero-length window [admissionDate, admissionDate]
            $availability = $bedOccupancyService->checkAvailability(
                (int) $targetBedId,
                $newAdmissionDate,
                $newAdmissionDate->copy(),
                null,
                $ipd->id
            );
            if (! $availability['available']) {
                return redirect()->back()->with('error', $availability['message'])->withInput();
            }

            if ($request->bed_number != $allotedBed) {
                $newBedDetail             = Bed::where('id', $request->bed_number)->firstOrFail();
                $allotedBedDetail         = Bed::where('id', $allotedBed)->firstOrFail();
                $bedhistory               = PatientBedHistory::where('ipd_id', $id)->orderBy('from_date')->firstOrFail();
                $bedhistory->bed_group_id = $request->bed_group;
                $bedhistory->bed_id       = $request->bed_number;
                $bedhistory->from_date    = $newAdmissionDate;
                $bedhistory->save();
                $newBedDetail->is_active = 'no';
                $newBedDetail->save();
                $allotedBedDetail->is_active = 'yes';
                $allotedBedDetail->save();
            } else {
                // Same bed, just align first history from_date with edited admission date
                $bedhistory = PatientBedHistory::where('ipd_id', $id)->orderBy('from_date')->first();
                if ($bedhistory) {
                    $bedhistory->from_date = $newAdmissionDate;
                    $bedhistory->save();
                }
            }
            // dd($opd);
            // Doctor Details
            $ipd->patient_id   = $request->patient_id;
            $ipd->cons_doctor  = $request->consultant_doctor;
            $ipd->cons_doctor2 = $request->consultant_doctor2 ?? null;
            $ipd->cons_doctor3 = $request->consultant_doctor3 ?? null;
            $ipd->cons_doctor4 = $request->consultant_doctor4 ?? null;

            // Visit Details
            $ipd->date         = $request->admission_date;
            $ipd->bed_group_id = $request->bed_group;
            $ipd->bed          = $request->bed_number;
            $ipd->patient_old  = $request->old_patient;
            $ipd->casualty     = $request->casualty;
            $ipd->refference   = $request->reference;

            $ipd->date         = $request->admission_date ?? $request->date;
            $ipd->credit_limit = $request->credit_limit ?? 0;

            // Misc
            $ipd->symptoms_type        = $implodedSymptomType ?? "";
            $ipd->symptoms_title       = $implodedSymptomTitle ?? "";
            $ipd->symptoms_description = $request->symptoms_description;
            $ipd->note                 = $request->note;
            // Save IPD Record
            $ipd->save();

            $ipdPatient->patient_id = $request->patient_id ?? null;
            $ipdPatient->doctor_id  = $request->consultant_doctor ?? null;
            $ipdPatient->doctor2_id = $request->consultant_doctor2 ?? null;
            $ipdPatient->doctor3_id = $request->consultant_doctor3 ?? null;
            $ipdPatient->doctor4_id = $request->consultant_doctor4 ?? null;
            $ipdPatient->save();

            // Package update during admission edit:
            // - If there is an applied package, update its amount (per patient) when package_rate provided.
            // - If no applied package yet, apply selected package from admission date (with optional override).
            if ($request->filled('package_id')) {
                $packageService      = new IpdPackageService();
                $packageRateOverride = $request->filled('package_rate') ? (float) $request->package_rate : null;

                $existingApplied = \App\Models\IpdPackage::where('ipd_id', $id)
                    ->where('status', 'applied')
                    ->orderByDesc('applied_date')
                    ->orderByDesc('id')
                    ->first();

                if ($existingApplied) {
                    if ($packageRateOverride !== null) {
                        $res = $packageService->updatePackageAmount($id, $existingApplied->id, $packageRateOverride);
                        if (! ($res['success'] ?? false)) {
                            throw new \Exception('Failed to update package amount: ' . ($res['message'] ?? 'Unknown error'));
                        }
                    }
                } else {
                    $res = $packageService->applyPackage(
                        $id,
                        $request->package_id,
                        \Carbon\Carbon::parse($request->admission_date)->format('Y-m-d'),
                        'Applied during IPD admission edit',
                        $packageRateOverride
                    );
                    if (! ($res['success'] ?? false)) {
                        throw new \Exception('Failed to apply package: ' . ($res['message'] ?? 'Unknown error'));
                    }
                }
            }

            DB::commit();

            return redirect()->route('ipd')->with('success', 'IPD record Updated successfully . ');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->withInput()->with('error', 'Failed to save IPD record: ' . $e->getMessage());
        }
    }

    public function getBedGroups(Request $request)
    {
        try {
            $bedGroups = BedGroup::with('floorDetail')->get();
            // Build a plain array to avoid JSON encoding issues (e.g. invalid UTF-8, circular refs)
            $data = $bedGroups->map(function ($g) {
                $floor = $g->relationLoaded('floorDetail') ? $g->floorDetail : null;
                return [
                    'id'           => $g->id,
                    'name'         => $g->name ?? '',
                    'floor_detail' => $floor ? ['name' => $floor->name ?? ''] : null,
                ];
            })->values()->all();
            return response()->json($data, 200, [], JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR);
        } catch (\Throwable $e) {
            \Log::error('Error fetching bed groups: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
    public function getBedNumbers(Request $request, $id)
    {
        // dd($id);
        // $bedNumbers = Bed::where('bed_group_id', $id)->where('is_active', 'yes')->get();
        $bedNumbers = Bed::where('bed_group_id', $id)
            ->where('is_active', 'yes')
            ->whereDoesntHave('patientBedHistory', function ($query) {
                $query->where('is_active', 'yes'); // means currently occupied
            })
            ->get();
        // dd($bedNumbers);
        return response()->json($bedNumbers, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }

    public function getBedGroupCharge(Request $request, $id)
    {
        try {
            $bedGroup = BedGroup::findOrFail($id);
            return response()->json([
                'success'  => true,
                'bed_cost' => $bedGroup->bed_cost ?? 0.00,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success'  => false,
                'bed_cost' => 0.00,
                'message'  => 'Bed group not found',
            ], 404);
        }
    }

    public function showIpd(Request $request, $id)
    {
        $ipd        = IpdDetail::with('patient.bloodGroup', 'patient.organisation', 'doctor', 'bedDetail', 'bedGroup')->where('id', $id)->firstOrFail();
        $symptomIds = array_filter(
            explode(',', $ipd->symptoms_title),
            fn($id) => $id !== null && trim($id) !== ''
        );

        // Fetch symptoms related to this OPD
        $symptoms = ! empty($symptomIds)
            ? Symptom::whereIn('id', $symptomIds)->get()
            : collect();
        $nurseNotes        = NurseNote::with('staff')->where('ipd_id', $id)->get();
        $medicationReport  = MedicationReport::with('medicineDosage.unit', 'pharmacy', 'generatedBy.userRole')->where('ipd_id', $id)->get();
        $ipdCharges        = IpdCharges::with('ipd', 'charge.taxCategory', 'chargeCategory.chargeType')->where('ipd_id', $id)->get();
        $labInvestigations = PathologyReport::with('pathology')->where('patient_id', $ipd->patient->id)->get();
        $ipdPrescriptions  = IpdPrescription::where('ipd_id', $id)->get();
        $ipdFindings       = [];
        foreach ($ipdPrescriptions as $pres) {
            // Split comma-separated symptom IDs and clean up
            $findingIds = array_filter(
                explode(',', $pres->findings),
                fn($id) => $id !== null && trim($id) !== ''
            );

            // Fetch symptoms related to this OPD
            $findings = ! empty($findingIds)
                ? Finding::whereIn('id', $findingIds)->get()
                : collect();

            // Store in array using OPD number as key
            $ipdFindings[$pres->ipd_id] = $findings;
        }
        $bedHistories    = PatientBedHistory::with('bedGroup', 'bed')->where('ipd_id', $id)->get();
        $operationDetail = OperationTheatre::with('operation.category')->where('ipd_details_id', $id)->get();

        // Load pathology and radiology tests for prescription modal
        $pathologies = Pathology::all();
        $radiologies = Radio::all();

        // $opdCharges        = OpdCharges::with('opd', 'charge.taxCategory', 'chargeCategory.chargeType')->where('opd_id', $id)->get();
        // $opdSymptoms       = [];

        // Store in array using OPD number as key
        return view('admin.ipd.ipd_view', compact('ipd', 'symptoms', 'nurseNotes', 'medicationReport', 'labInvestigations', 'ipdPrescriptions', 'ipdFindings', 'bedHistories', 'operationDetail', 'ipdCharges', 'pathologies', 'radiologies'));
    }

    public function getNurses(Request $request)
    {
        $nurses = Staff::where('employee_id', 'LIKE', 'NUR%')->get();
        return response()->json($nurses, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }

    public function getIpdById(Request $request, $id)
    {
        $ipd = IpdDetail::with('patient.bloodGroup', 'doctor.department')->where('id', $id)->firstOrFail();
        return response()->json($ipd, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);

    }
    public function getIpdMedicineById(Request $request, $id)
    {
        $ipdMedicines = IpdMedicine::with('pharmacy', 'medicineDosage.unit', 'doseInterval', 'doseDuration')->where('prescription_id', $id)->get();
        return response()->json($ipdMedicines, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);

    }
    public function getIpdRadPathById($id)
    {
        $prescription = IpdPrescription::with('prescribedBy')->find($id);

        if (! $prescription) {
            return response()->json([
                'prescription' => null,
                'pathology'    => [],
                'radiology'    => [],
            ]);
        }

        // Convert "3,4" → [3,4]
        $pathologyIds = $prescription->pathology_id
            ? array_filter(explode(',', $prescription->pathology_id))
            : [];

        $radiologyIds = $prescription->radiology_id
            ? array_filter(explode(',', $prescription->radiology_id))
            : [];

        $pathology = Pathology::whereIn('id', $pathologyIds)->get();
        $radiology = Radio::whereIn('id', $radiologyIds)->get();

        return response()->json([
            'prescription' => $prescription,
            'pathology'    => $pathology,
            'radiology'    => $radiology,
        ]);
    }

    public function addNurseNote(Request $request)
    {
        // dd($request->all());
        try {
            //code...

            $request->validate([
                'ipd_id'  => 'required|exists:ipd_details,id',
                'date'    => 'required|date',
                'nurse'   => 'required|exists:staff,id',
                'note'    => 'required|string',
                'comment' => 'nullable|string',
            ]);

            NurseNote::create([
                'date'       => $request->date,
                'ipd_id'     => $request->ipd_id,
                'staff_id'   => $request->nurse,
                'note'       => $request->note,
                'comment'    => $request->comment,
                'created_by' => Auth::user()->id,
            ]);

            return redirect()->back()->with('success', 'Nurse Note Added!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
            //throw $th;
        }
    }

    public function storePrescription(Request $request)
    {
        // Debug: Log what we're receiving
        \Log::info('=== Prescription Form Data Received ===');
        \Log::info('All request data:', $request->all());
        \Log::info('Medicines:', $request->input('medicines', []));
        \Log::info('Dosages:', $request->input('dosages', []));
        \Log::info('Interval dosages:', $request->input('interval_dosages', []));
        \Log::info('Duration dosages:', $request->input('duration_dosages', []));
        \Log::info('Instructions:', $request->input('instructions', []));

        // Check types
        if ($request->has('medicines')) {
            $medicines = $request->input('medicines', []);
            foreach ($medicines as $index => $medicine) {
                \Log::info("Medicine[$index]: value = " . var_export($medicine, true) . ", type = " . gettype($medicine));
            }
        }

        // Pre-process request data to ensure all array values are strings
        $medicines       = $request->input('medicines', []);
        $dosages         = $request->input('dosages', []);
        $intervalDosages = $request->input('interval_dosages', []);
        $durationDosages = $request->input('duration_dosages', []);
        $instructions    = $request->input('instructions', []);

        // Convert all to strings explicitly
        $medicines = array_map(function ($val) {
            return $val !== null && $val !== '' ? (string) $val : '';
        }, $medicines);

        $dosages = array_map(function ($val) {
            return $val !== null && $val !== '' ? (string) $val : '';
        }, $dosages);

        $intervalDosages = array_map(function ($val) {
            return $val !== null && $val !== '' ? (string) $val : '';
        }, $intervalDosages);

        $durationDosages = array_map(function ($val) {
            return $val !== null && $val !== '' ? (string) $val : '';
        }, $durationDosages);

        $instructions = array_map(function ($val) {
            return $val !== null && $val !== '' ? (string) $val : '';
        }, $instructions);

        // Pre-process pathology and radiology arrays to ensure all values are strings
        $pathology = $request->input('pathology', []);
        $radiology = $request->input('radiology', []);

        $pathology = array_map(function ($val) {
            return $val !== null && $val !== '' ? (string) $val : '';
        }, $pathology);

        $radiology = array_map(function ($val) {
            return $val !== null && $val !== '' ? (string) $val : '';
        }, $radiology);

        // Pre-process finding_type and findings arrays to ensure all values are strings
        $findingType = $request->input('finding_type', []);
        $findings    = $request->input('findings', []);

        // Ensure they're arrays
        if (! is_array($findingType)) {
            $findingType = $findingType ? [$findingType] : [];
        }
        if (! is_array($findings)) {
            $findings = $findings ? [$findings] : [];
        }

        // Convert all values to strings and filter out empty values
        $findingType = array_filter(
            array_map(function ($val) {
                if ($val === null || $val === '') {
                    return null;
                }
                return (string) $val;
            }, $findingType),
            fn($val) => $val !== null && $val !== ''
        );

        $findings = array_filter(
            array_map(function ($val) {
                if ($val === null || $val === '') {
                    return null;
                }
                return (string) $val;
            }, $findings),
            fn($val) => $val !== null && $val !== ''
        );

        \Log::info('Pahtology Ids:', $pathology);
        \Log::info('Radiology Ids:', $radiology);
        \Log::info('Finding Types:', $findingType);
        \Log::info('Findings:', $findings);

        // Merge back into request
        $request->merge([
            'medicines'        => $medicines,
            'dosages'          => $dosages,
            'interval_dosages' => $intervalDosages,
            'duration_dosages' => $durationDosages,
            'instructions'     => $instructions,
            'pathology'        => $pathology,
            'radiology'        => $radiology,
            'finding_type'     => array_values($findingType), // Re-index array
            'findings'         => array_values($findings),    // Re-index array
        ]);

        \Log::info('After conversion - Medicines:', $medicines);
        \Log::info('After conversion - Types:', array_map('gettype', $medicines));

        // dd($request->all());
        try { $request->validate([
            'ipd_id'              => 'nullable|string',
            'date'                => 'nullable|date',
            'header_note'         => 'nullable|string',
            'footer_note'         => 'nullable|string',
            'advice'              => 'nullable|string',
            'finding_description' => 'nullable|string',
            'finding_print'       => 'nullable|string',
            'finding_type'        => 'nullable|array',
            'finding_type.*'      => 'string',
            'findings'            => 'nullable|array',
            'findings.*'          => 'string',
            'pathology'           => 'nullable|array',
            'pathology.*'         => 'string',
            'radiology'           => 'nullable|array',
            'radiology.*'         => 'string',
            'visible'             => 'nullable|array',
            'visible.*'           => 'string',
            'medicines'           => 'nullable|array',
            'medicines.*'         => 'string',
            'dosages'             => 'nullable|array',
            'dosages.*'           => 'string',
            'interval_dosages'    => 'nullable|array',
            'interval_dosages.*'  => 'string',
            'duration_dosages'    => 'nullable|array',
            'duration_dosages.*'  => 'string',
            'instructions'        => 'nullable|array',
            'instructions.*'      => 'string',
        ]);
            $findingTypes = array_filter($request->input('finding_type', []), fn($type) => $type !== null && $type !== '');
            // $findings             = array_filter($request->findings, fn($title) => $title !== null && $title !== '');
            $findings = array_filter($request->input('findings', []), fn($title) => $title !== null && $title !== '');
            // Get pathology and radiology IDs - handle both 'pathology' and 'pathology[]' formats
            $pathologyInput = $request->input('pathology', []);
            $radiologyInput = $request->input('radiology', []);

            // If empty, try alternative key names
            if (empty($pathologyInput)) {
                $pathologyInput = $request->input('pathology[]', []);
            }
            if (empty($radiologyInput)) {
                $radiologyInput = $request->input('radiology[]', []);
            }

            \Log::info('🔴 Pathology input received:', ['pathology' => $pathologyInput, 'pathology[]' => $request->input('pathology[]', [])]);
            \Log::info('🔴 Radiology input received:', ['radiology' => $radiologyInput, 'radiology[]' => $request->input('radiology[]', [])]);

            $pathology_ids = array_filter($pathologyInput, fn($pathology) => $pathology !== null && $pathology !== '');
            $radiology_ids = array_filter($radiologyInput, fn($radio) => $radio !== null && $radio !== '');

            \Log::info('🔴 Pathology IDs after filter:', $pathology_ids);
            \Log::info('🔴 Radiology IDs after filter:', $radiology_ids);
            $notification_to = array_filter($request->input('visible', []), fn($notify) => $notify !== null && $notify !== '');
            // $pathology_ids        = array_filter($request->pathology, fn($pathology) => $pathology !== null && $pathology !== '');
            // $radiology_ids        = array_filter($request->radiology, fn($radio) => $radio !== null && $radio !== '');
            // $notification_to      = array_filter($request->visible, fn($notify) => $notify !== null && $notify !== '');
            $implodedFindingTypes = implode(", ", $findingTypes);
            $implodedFindings     = implode(", ", $findings);
            $implodedVisibles     = implode(", ", $notification_to);

            // Handle file upload
            $attachment     = null;
            $attachmentName = null;
            if ($request->hasFile('document')) {
                $file           = $request->file('document');
                $attachmentName = $file->getClientOriginalName();
                $attachment     = $file->store('prescription_documents', 'public');
            }

            // Generate prescription number
            $lastPrescription = IpdPrescription::orderBy('id', 'desc')->first();
            if ($lastPrescription && preg_match('/IPDP(\d+)/', $lastPrescription->prescription_number, $matches)) {
                $lastNumber = intval($matches[1]);
            } else {
                $lastNumber = 0;
            }
            $prescriptionPrefix = Prefix::where("type", 'ipd_pre')->firstOrFail();
            $nextNumber         = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            $prescriptionNo     = $prescriptionPrefix->prefix . $nextNumber;

            // Get user info for hospital_id and branch_id
            $user = Auth::user();

            DB::beginTransaction();

            try {
                // Create prescription - use request date when provided (for back-dated prescriptions)
                $dateInput = $request->input('date') ?? $request->input('prescription_date') ?? $request->get('date');
                if (is_array($dateInput)) {
                    $dateInput = $dateInput[0] ?? null;
                }
                $dateInput = $dateInput !== null ? trim((string) $dateInput) : '';
                if ($dateInput !== '') {
                    try {
                        $prescriptionDate = Carbon::parse($dateInput)->toDateString();
                    } catch (\Exception $e) {
                        $prescriptionDate = Carbon::now()->toDateString();
                    }
                } else {
                    // Fallback: use IPD admission date when request date is empty, else today
                    $ipd              = $request->ipd_id ? IpdDetail::find($request->ipd_id) : null;
                    $prescriptionDate = ($ipd && $ipd->date)
                        ? Carbon::parse($ipd->date)->toDateString()
                        : Carbon::now()->toDateString();
                }

                $prescription = IpdPrescription::create([
                    'prescription_number' => $prescriptionNo,
                    'ipd_id'              => $request->ipd_id,
                    'prescribed_by'       => $request->prescribe_by, // NEW
                    'header_note'         => $request->header_note ?? null,
                    'footer_note'         => $request->footer_note ?? null,
                    'advice'              => $request->advice ?? null,
                    'finding_description' => $request->finding_description ?? null,
                    'is_finding_print'    => $request->finding_print ?? 'no',
                    'date'                => $prescriptionDate,
                    'finding_categories'  => $implodedFindingTypes,
                    'findings'            => $implodedFindings,
                    'pathology_id'        => ! empty($pathology_ids) ? implode(", ", $pathology_ids) : null, // Keep for backward compatibility
                    'radiology_id'        => ! empty($radiology_ids) ? implode(", ", $radiology_ids) : null, // Keep for backward compatibility
                    'notification_to'     => $implodedVisibles,
                    'attachment'          => $attachment,     // NEW
                    'attachment_name'     => $attachmentName, // NEW
                ]);

                // Store tests in normalized table with instance tracking
                $prescriptionTime = Carbon::now()->format('H:i:s');

                if (! empty($pathology_ids)) {
                    $pathologyNotes = $request->input('pathology_notes');
                    if (! is_array($pathologyNotes)) {
                        $pathologyNotes = [];
                    }
                    foreach ($pathology_ids as $index => $pathologyId) {
                        // Get instance number for this test on this date
                        $instanceNumber = IpdPrescriptionTest::getNextInstanceNumber(
                            (int) $pathologyId,
                            $prescriptionDate,
                            'pathology',
                            $prescription->id
                        );

                        // Notes: by index (per instance) or fallback to legacy per-id
                        $notes = $pathologyNotes[$index] ?? $request->input("pathology_notes_{$pathologyId}") ?? null;

                        IpdPrescriptionTest::create([
                            'ipd_prescription_id' => $prescription->id,
                            'pathology_id'        => (int) $pathologyId,
                            'radiology_id'        => null,
                            'instance_number'     => $instanceNumber,
                            'test_date'           => $prescriptionDate,
                            'prescription_time'   => $prescriptionTime,
                            'notes'               => $notes,
                            'hospital_id'         => $user->hospital_id ?? '00000001',
                            'branch_id'           => $user->branch_id ?? '00000001',
                        ]);
                    }
                }

                if (! empty($radiology_ids)) {
                    $radiologyNotes = $request->input('radiology_notes');
                    if (! is_array($radiologyNotes)) {
                        $radiologyNotes = [];
                    }
                    foreach ($radiology_ids as $index => $radiologyId) {
                        // Get instance number for this test on this date
                        $instanceNumber = IpdPrescriptionTest::getNextInstanceNumber(
                            (int) $radiologyId,
                            $prescriptionDate,
                            'radiology',
                            $prescription->id
                        );

                        // Notes: by index (per instance) or fallback to legacy per-id
                        $notes = $radiologyNotes[$index] ?? $request->input("radiology_notes_{$radiologyId}") ?? null;

                        IpdPrescriptionTest::create([
                            'ipd_prescription_id' => $prescription->id,
                            'pathology_id'        => null,
                            'radiology_id'        => (int) $radiologyId,
                            'instance_number'     => $instanceNumber,
                            'test_date'           => $prescriptionDate,
                            'prescription_time'   => $prescriptionTime,
                            'notes'               => $notes,
                            'hospital_id'         => $user->hospital_id ?? '00000001',
                            'branch_id'           => $user->branch_id ?? '00000001',
                        ]);
                    }
                }

                // Send pathology tests to PMS (if any and PMS is configured)
                if (! empty($pathology_ids) && config('services.pms.base_url')) {
                    try {
                        $pmsBridge = app(PmsBridgeService::class);
                        $pmsBridge->sendIpdPathologyOrder($prescription);
                    } catch (\Throwable $e) {
                        \Log::error('Error sending IPD pathology order to PMS: ' . $e->getMessage(), [
                            'prescription_id' => $prescription->id ?? null,
                        ]);
                    }
                }
                // Store medicines
                if (! empty($request->medicines)) {
                    foreach ($request->medicines as $i => $med) {
                        IpdMedicine::create([
                            "prescription_id"    => $prescription->id,
                            "pharmacy_id"        => intval($med),
                            "medicine_dosage_id" => intval($request->dosages[$i]),
                            "dose_interval_id"   => intval($request->interval_dosages[$i]),
                            "dose_duration_id"   => intval($request->duration_dosages[$i]),
                            "instruction"        => $request->instructions[$i] ?? null,
                        ]);
                    }
                }

                DB::commit();
                return response()->json([
                    'success'         => true,
                    'message'         => 'Prescription created successfully.',
                    'prescription_id' => $prescription->id,
                ], 200);
            } catch (Exception $e) {
                DB::rollBack();
                // Delete uploaded file if prescription creation failed
                if ($attachment) {
                    Storage::disk('public')->delete($attachment);
                }
                \Log::error('Prescription creation error: ' . $e->getMessage());
                \Log::error('Stack trace: ' . $e->getTraceAsString());
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong: ' . $e->getMessage(),
                ], 500);
            }} catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Prescription validation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            \Log::error('Prescription error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show prescription details
     */
    public function showPrescription($id)
    {
        $prescription = IpdPrescription::with([
            'ipd.patient',
            'prescribedBy',
            'tests.pathology',
            'tests.radiology',
            'medicines.pharmacy',
            'medicines.medicineDosage.unit',
            'medicines.doseInterval',
            'medicines.doseDuration',
        ])->findOrFail($id);

        return view('admin.ipd.prescription.show', compact('prescription'));
    }

    /**
     * Show edit prescription form
     */
    public function editPrescription($id)
    {
        $prescription = IpdPrescription::with([
            'tests' => function ($q) {
                $q->orderBy('id');
            },
            'tests.pathology',
            'tests.radiology',
            'medicines.pharmacy.medicineCategory',
            'medicines.medicineDosage.unit',
            'medicines.doseInterval',
            'medicines.doseDuration',
        ])->findOrFail($id);

        $doctors  = Doctor::all();
        $findings = Finding::all();
        // Use Eloquent so all tests (including newly added) are available; no extra scope
        $pathologies = Pathology::orderBy('test_name')->get();
        $radiologies = Radio::orderBy('test_name')->get();

        // Get selected test IDs in order (with notes) for selected-list UI
        $pathologyTestsForList = [];
        $radiologyTestsForList = [];
        foreach ($prescription->tests as $t) {
            if ($t->pathology_id) {
                $path                    = $t->pathology ?? \App\Models\Pathology::find($t->pathology_id);
                $pathologyTestsForList[] = [
                    'id'    => $t->pathology_id,
                    'name'  => $path ? ($path->test_name . ($path->short_name ? ' (' . $path->short_name . ')' : '')) : 'ID ' . $t->pathology_id,
                    'notes' => $t->notes ?? '',
                ];
            }
            if ($t->radiology_id) {
                $rad                     = $t->radiology ?? \App\Models\Radio::find($t->radiology_id);
                $radiologyTestsForList[] = [
                    'id'    => $t->radiology_id,
                    'name'  => $rad ? ($rad->test_name . ($rad->short_name ? ' (' . $rad->short_name . ')' : '')) : 'ID ' . $t->radiology_id,
                    'notes' => $t->notes ?? '',
                ];
            }
        }
        // Fallback: if no normalized tests but prescription has old comma-separated pathology_id/radiology_id
        if (empty($pathologyTestsForList) && ! empty($prescription->pathology_id)) {
            $ids = array_filter(array_map('trim', explode(',', $prescription->pathology_id)));
            foreach ($ids as $pathId) {
                $path                    = \App\Models\Pathology::find($pathId);
                $pathologyTestsForList[] = [
                    'id'    => (int) $pathId,
                    'name'  => $path ? ($path->test_name . ($path->short_name ? ' (' . $path->short_name . ')' : '')) : 'ID ' . $pathId,
                    'notes' => '',
                ];
            }
        }
        if (empty($radiologyTestsForList) && ! empty($prescription->radiology_id)) {
            $ids = array_filter(array_map('trim', explode(',', $prescription->radiology_id)));
            foreach ($ids as $radId) {
                $rad                     = \App\Models\Radio::find($radId);
                $radiologyTestsForList[] = [
                    'id'    => (int) $radId,
                    'name'  => $rad ? ($rad->test_name . ($rad->short_name ? ' (' . $rad->short_name . ')' : '')) : 'ID ' . $radId,
                    'notes' => '',
                ];
            }
        }
        $selectedPathologyIds = array_column($pathologyTestsForList, 'id');
        $selectedRadiologyIds = array_column($radiologyTestsForList, 'id');

        $prescriptionDate = $prescription->date ? $prescription->date->format('Y-m-d') : Carbon::now()->toDateString();
        $admissionDate    = null;
        if ($prescription->ipd_id) {
            $ipd           = IpdDetail::find($prescription->ipd_id);
            $admissionDate = $ipd && $ipd->date ? Carbon::parse($ipd->date)->format('Y-m-d') : null;
        }
        $pathologyInstanceCounts = [];
        $radiologyInstanceCounts = [];
        foreach (array_count_values($selectedPathologyIds) as $pathId => $count) {
            $pathologyInstanceCounts[$pathId] = $count;
        }
        foreach (array_count_values($selectedRadiologyIds) as $radId => $count) {
            $radiologyInstanceCounts[$radId] = $count;
        }

        return view('admin.ipd.prescription.edit', compact(
            'prescription',
            'doctors',
            'findings',
            'pathologies',
            'radiologies',
            'selectedPathologyIds',
            'selectedRadiologyIds',
            'pathologyInstanceCounts',
            'radiologyInstanceCounts',
            'prescriptionDate',
            'admissionDate',
            'pathologyTestsForList',
            'radiologyTestsForList'
        ));
    }

    /**
     * Update prescription
     */
    public function updatePrescription(Request $request, $id)
    {
        try {
            $request->validate([
                'ipd_id'              => 'required|exists:ipd_details,id',
                'prescribe_by'        => 'required|exists:doctor,id', // Table name is 'doctor' not 'doctors'
                'header_note'         => 'nullable|string',
                'footer_note'         => 'nullable|string',
                'advice'              => 'nullable|string',
                'finding_description' => 'nullable|string',
                'finding_print'       => 'nullable|string',
                'finding_type'        => 'nullable|array',
                'finding_type.*'      => 'nullable|string',
                'findings'            => 'nullable|array',
                'findings.*'          => 'nullable|string',
                'date'                => 'nullable|date',
                'pathology'           => 'nullable|array',
                'pathology.*'         => 'nullable|exists:pathology,id',
                'radiology'           => 'nullable|array',
                'radiology.*'         => 'nullable|exists:radio,id',
                'visible'             => 'nullable|array',
                'visible.*'           => 'nullable|string',
                'medicines'           => 'nullable|array',
                'medicines.*'         => 'nullable|integer',
                'dosages'             => 'nullable|array',
                'dosages.*'           => 'nullable|integer',
                'interval_dosages'    => 'nullable|array',
                'interval_dosages.*'  => 'nullable|integer',
                'duration_dosages'    => 'nullable|array',
                'duration_dosages.*'  => 'nullable|integer',
                'instructions'        => 'nullable|array',
                'instructions.*'      => 'nullable|string',
                'document'            => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            ]);

            $prescription = IpdPrescription::findOrFail($id);
            $user         = Auth::user();

            $findingTypes    = array_filter($request->finding_type ?? [], fn($type) => $type !== null && $type !== '');
            $findings        = array_filter($request->findings ?? [], fn($title) => $title !== null && $title !== '');
            $pathology_ids   = array_filter($request->pathology ?? [], fn($pathology) => $pathology !== null && $pathology !== '');
            $radiology_ids   = array_filter($request->radiology ?? [], fn($radio) => $radio !== null && $radio !== '');
            $notification_to = array_filter($request->visible ?? [], fn($notify) => $notify !== null && $notify !== '');

            $implodedFindingTypes = implode(", ", $findingTypes);
            $implodedFindings     = implode(", ", $findings);
            $implodedVisibles     = implode(", ", $notification_to);

            // Handle file upload
            $attachment     = $prescription->attachment;
            $attachmentName = $prescription->attachment_name;
            if ($request->hasFile('document')) {
                // Delete old file if exists
                if ($attachment) {
                    Storage::disk('public')->delete($attachment);
                }
                $file           = $request->file('document');
                $attachmentName = $file->getClientOriginalName();
                $attachment     = $file->store('prescription_documents', 'public');
            }

            DB::beginTransaction();

            try {
                // Update prescription - ensure prescribe_by is properly set
                $prescribeBy = $request->prescribe_by ?? $prescription->prescribed_by;

                // Resolve prescription date: request date > existing prescription date > today (for back-dated support)
                $dateInput = $request->input('date') ?? $request->input('prescription_date') ?? $request->get('date');
                if (is_array($dateInput)) {
                    $dateInput = $dateInput[0] ?? null;
                }
                $dateInput = $dateInput !== null ? trim((string) $dateInput) : '';
                if ($dateInput !== '') {
                    try {
                        $prescriptionDateForUpdate = Carbon::parse($dateInput)->toDateString();
                    } catch (\Exception $e) {
                        $prescriptionDateForUpdate = $prescription->date
                            ? Carbon::parse($prescription->date)->toDateString()
                            : Carbon::now()->toDateString();
                    }
                } else {
                    $prescriptionDateForUpdate = $prescription->date
                        ? Carbon::parse($prescription->date)->toDateString()
                        : Carbon::now()->toDateString();
                }

                $prescription->update([
                    'ipd_id'              => $request->ipd_id,
                    'prescribed_by'       => $prescribeBy,
                    'header_note'         => $request->header_note ?? null,
                    'footer_note'         => $request->footer_note ?? null,
                    'advice'              => $request->advice ?? null,
                    'finding_description' => $request->finding_description ?? null,
                    'is_finding_print'    => $request->finding_print ?? 'no',
                    'finding_categories'  => $implodedFindingTypes,
                    'findings'            => $implodedFindings,
                    'pathology_id'        => ! empty($pathology_ids) ? implode(", ", $pathology_ids) : null,
                    'radiology_id'        => ! empty($radiology_ids) ? implode(", ", $radiology_ids) : null,
                    'notification_to'     => $implodedVisibles,
                    'date'                => $prescriptionDateForUpdate,
                    'attachment'          => $attachment,
                    'attachment_name'     => $attachmentName,
                ]);

                // Diff: only remove from billing and delete tests that are NO LONGER in the prescription.
                // Tests that remain in the request are kept (preserving billing linkage).
                $newPathologyCounts = array_count_values(array_map('intval', $pathology_ids));
                $newRadiologyCounts = array_count_values(array_map('intval', $radiology_ids));

                $existingPathology = IpdPrescriptionTest::where('ipd_prescription_id', $prescription->id)
                    ->whereNotNull('pathology_id')
                    ->orderBy('id')
                    ->get();
                $existingRadiology = IpdPrescriptionTest::where('ipd_prescription_id', $prescription->id)
                    ->whereNotNull('radiology_id')
                    ->orderBy('id')
                    ->get();

                $pathologyKept = [];
                foreach ($existingPathology as $test) {
                    $pid     = (int) $test->pathology_id;
                    $maxKeep = $newPathologyCounts[$pid] ?? 0;
                    $keptNow = $pathologyKept[$pid] ?? 0;
                    if ($keptNow < $maxKeep) {
                        $pathologyKept[$pid] = $keptNow + 1;
                    } else {
                        $this->removePrescriptionTestFromBilling($test);
                        $test->delete();
                    }
                }

                $radiologyKept = [];
                foreach ($existingRadiology as $test) {
                    $rid     = (int) $test->radiology_id;
                    $maxKeep = $newRadiologyCounts[$rid] ?? 0;
                    $keptNow = $radiologyKept[$rid] ?? 0;
                    if ($keptNow < $maxKeep) {
                        $radiologyKept[$rid] = $keptNow + 1;
                    } else {
                        $this->removePrescriptionTestFromBilling($test);
                        $test->delete();
                    }
                }

                // Count how many of each test we already have (kept from existing)
                $pathologyToAdd = [];
                foreach ($pathology_ids as $pid) {
                    $pid                  = (int) $pid;
                    $pathologyToAdd[$pid] = ($pathologyToAdd[$pid] ?? 0) + 1;
                }
                foreach ($pathologyKept as $pid => $count) {
                    $pathologyToAdd[$pid] = ($pathologyToAdd[$pid] ?? 0) - $count;
                }
                $radiologyToAdd = [];
                foreach ($radiology_ids as $rid) {
                    $rid                  = (int) $rid;
                    $radiologyToAdd[$rid] = ($radiologyToAdd[$rid] ?? 0) + 1;
                }
                foreach ($radiologyKept as $rid => $count) {
                    $radiologyToAdd[$rid] = ($radiologyToAdd[$rid] ?? 0) - $count;
                }

                // Store NEW tests (only those not already kept from existing) - use same date as prescription update
                $prescriptionDate = $prescriptionDateForUpdate;
                $prescriptionTime = Carbon::now()->format('H:i:s');
                $pathologyNotes   = is_array($request->input('pathology_notes')) ? $request->input('pathology_notes') : [];
                $radiologyNotes   = is_array($request->input('radiology_notes')) ? $request->input('radiology_notes') : [];
                $pathologyIdsList = array_values(array_map('intval', $pathology_ids));
                $radiologyIdsList = array_values(array_map('intval', $radiology_ids));

                foreach ($pathologyToAdd as $pathologyId => $count) {
                    if ($count <= 0) {
                        continue;
                    }

                    for ($i = 0; $i < $count; $i++) {
                        $instanceNumber = IpdPrescriptionTest::getNextInstanceNumber(
                            $pathologyId,
                            $prescriptionDate,
                            'pathology',
                            $prescription->id
                        );
                        $notesIdx = array_search($pathologyId, $pathologyIdsList);
                        $notes    = ($notesIdx !== false && isset($pathologyNotes[$notesIdx])) ? $pathologyNotes[$notesIdx] : null;
                        IpdPrescriptionTest::create([
                            'ipd_prescription_id' => $prescription->id,
                            'pathology_id'        => $pathologyId,
                            'radiology_id'        => null,
                            'instance_number'     => $instanceNumber,
                            'test_date'           => $prescriptionDate,
                            'prescription_time'   => $prescriptionTime,
                            'notes'               => $notes,
                            'hospital_id'         => $user->hospital_id ?? '00000001',
                            'branch_id'           => $user->branch_id ?? '00000001',
                        ]);
                    }
                }

                foreach ($radiologyToAdd as $radiologyId => $count) {
                    if ($count <= 0) {
                        continue;
                    }

                    for ($i = 0; $i < $count; $i++) {
                        $instanceNumber = IpdPrescriptionTest::getNextInstanceNumber(
                            $radiologyId,
                            $prescriptionDate,
                            'radiology',
                            $prescription->id
                        );
                        $notesIdx = array_search($radiologyId, $radiologyIdsList);
                        $notes    = ($notesIdx !== false && isset($radiologyNotes[$notesIdx])) ? $radiologyNotes[$notesIdx] : null;
                        IpdPrescriptionTest::create([
                            'ipd_prescription_id' => $prescription->id,
                            'pathology_id'        => null,
                            'radiology_id'        => $radiologyId,
                            'instance_number'     => $instanceNumber,
                            'test_date'           => $prescriptionDate,
                            'prescription_time'   => $prescriptionTime,
                            'notes'               => $notes,
                            'hospital_id'         => $user->hospital_id ?? '00000001',
                            'branch_id'           => $user->branch_id ?? '00000001',
                        ]);
                    }
                }

                // Delete existing medicines
                IpdMedicine::where('prescription_id', $prescription->id)->delete();

                // Store new medicines
                if (! empty($request->medicines)) {
                    foreach ($request->medicines as $i => $med) {
                        IpdMedicine::create([
                            "prescription_id"    => $prescription->id,
                            "pharmacy_id"        => intval($med),
                            "medicine_dosage_id" => intval($request->dosages[$i]),
                            "dose_interval_id"   => intval($request->interval_dosages[$i]),
                            "dose_duration_id"   => intval($request->duration_dosages[$i]),
                            "instruction"        => $request->instructions[$i] ?? null,
                        ]);
                    }
                }

                DB::commit();
                return response()->json([
                    'success'         => true,
                    'message'         => 'Prescription updated successfully.',
                    'prescription_id' => $prescription->id,
                ], 200);
            } catch (Exception $e) {
                DB::rollBack();
                \Log::error('Prescription update error: ' . $e->getMessage());
                \Log::error('Stack trace: ' . $e->getTraceAsString());
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong: ' . $e->getMessage(),
                ], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Prescription validation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            \Log::error('Prescription update error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Remove a prescription test from pathology/radiology billing when test is removed from prescription.
     * Deletes linked PathologyReport/RadiologyReport and recalculates parent bill net_amount.
     * Includes fallback for reports without ipd_prescription_test_id (legacy or manually created bills).
     *
     * @param IpdPrescriptionTest $test
     */
    private function removePrescriptionTestFromBilling(IpdPrescriptionTest $test)
    {
        $prescription  = $test->prescription;
        $ipd           = $prescription ? IpdDetail::find($prescription->ipd_id) : null;
        $patientId     = $ipd ? $ipd->patient_id : null;
        $admissionDate = $ipd && $ipd->date ? Carbon::parse($ipd->date)->format('Y-m-d') : null;

        // Pathology: remove report(s) linked to this prescription test
        $pathologyReports         = PathologyReport::where('ipd_prescription_test_id', $test->id)->get();
        $pathologyBillIdsToRecalc = [];
        foreach ($pathologyReports as $report) {
            $pathologyBillIdsToRecalc[$report->pathology_bill_id] = true;
            $report->delete();
        }
        // Fallback: reports without prescription_test link (legacy/manual bills)
        if ($pathologyReports->isEmpty() && $test->pathology_id && $patientId && $admissionDate) {
            $fallbackReport = PathologyReport::where('pathology_id', $test->pathology_id)
                ->where('patient_id', $patientId)
                ->whereNull('ipd_prescription_test_id')
                ->whereIn('pathology_bill_id', PathologyBilling::where('patient_id', $patientId)
                        ->whereRaw('DATE(date) >= ?', [$admissionDate])
                        ->pluck('id'))
                ->orderBy('id')
                ->first();
            if ($fallbackReport) {
                $pathologyBillIdsToRecalc[$fallbackReport->pathology_bill_id] = true;
                $fallbackReport->delete();
            }
        }
        foreach (array_keys($pathologyBillIdsToRecalc) as $billId) {
            $this->recalculatePathologyBilling($billId);
        }

        // Radiology: remove report(s) linked to this prescription test
        $radiologyReports         = RadiologyReport::where('ipd_prescription_test_id', $test->id)->get();
        $radiologyBillIdsToRecalc = [];
        foreach ($radiologyReports as $report) {
            $radiologyBillIdsToRecalc[$report->radiology_bill_id] = true;
            $report->delete();
        }
        // Fallback: reports without prescription_test link (legacy/manual bills)
        if ($radiologyReports->isEmpty() && $test->radiology_id && $patientId && $admissionDate) {
            $fallbackReport = RadiologyReport::where('radiology_id', $test->radiology_id)
                ->where('patient_id', $patientId)
                ->whereNull('ipd_prescription_test_id')
                ->whereIn('radiology_bill_id', RadiologyBilling::where('patient_id', $patientId)
                        ->whereRaw('DATE(date) >= ?', [$admissionDate])
                        ->pluck('id'))
                ->orderBy('id')
                ->first();
            if ($fallbackReport) {
                $radiologyBillIdsToRecalc[$fallbackReport->radiology_bill_id] = true;
                $fallbackReport->delete();
            }
        }
        foreach (array_keys($radiologyBillIdsToRecalc) as $billId) {
            $this->recalculateRadiologyBilling($billId);
        }
    }

    /**
     * Recalculate pathology bill net_amount from remaining reports. Delete bill if empty.
     */
    private function recalculatePathologyBilling($billId)
    {
        $bill = PathologyBilling::find($billId);
        if (! $bill) {
            return;
        }

        $remainingTotal = PathologyReport::where('pathology_bill_id', $billId)->sum('apply_charge');
        if ($remainingTotal <= 0) {
            PathologyReport::where('pathology_bill_id', $billId)->delete();
            $bill->delete();
        } else {
            $bill->update(['net_amount' => $remainingTotal, 'total' => $remainingTotal]);
        }
    }

    /**
     * Recalculate radiology bill net_amount from remaining reports. Delete bill if empty.
     */
    private function recalculateRadiologyBilling($billId)
    {
        $bill = RadiologyBilling::find($billId);
        if (! $bill) {
            return;
        }

        $remainingTotal = RadiologyReport::where('radiology_bill_id', $billId)->sum('apply_charge');
        if ($remainingTotal <= 0) {
            RadiologyReport::where('radiology_bill_id', $billId)->delete();
            $bill->delete();
        } else {
            $bill->update(['net_amount' => $remainingTotal, 'total' => $remainingTotal]);
        }
    }

    /**
     * Delete prescription
     */
    public function deletePrescription($id)
    {
        try {
            $prescription = IpdPrescription::findOrFail($id);

            // Delete associated file
            if ($prescription->attachment) {
                Storage::disk('public')->delete($prescription->attachment);
            }

            // Remove prescription tests from pathology/radiology billing before deleting tests
            $existingTests = IpdPrescriptionTest::where('ipd_prescription_id', $prescription->id)->get();
            foreach ($existingTests as $test) {
                $this->removePrescriptionTestFromBilling($test);
                $test->delete();
            }
            IpdMedicine::where('prescription_id', $prescription->id)->delete();

            $ipdId = $prescription->ipd_id;
            $prescription->delete();

            return redirect()->route('ipd.show', $ipdId)
                ->with('success', 'Prescription deleted successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Print prescription
     */
    public function printPrescription($id)
    {
        $prescription = IpdPrescription::with(['ipd', 'prescribedBy', 'tests.pathology', 'tests.radiology', 'medicines'])
            ->findOrFail($id);

        return view('admin.ipd.prescription.print', compact('prescription'));
    }

    public function addIpdCharge(Request $request)
    {
                                               // dd($request->charge_category);         // dd($request->all());
        $count = count($request->charge_type); // Number of rows

        for ($i = 0; $i < $count; $i++) {

            IpdCharges::create([
                'ipd_id'              => $request->ipd_id ?? null,
                'charge_type_id'      => $request->charge_type[$i],
                'charge_category_id'  => $request->charge_category[$i],
                'charge_id'           => $request->charge_id[$i],
                'standard_charge'     => $request->standard_charge[$i],
                'tpa_charge'          => $request->tpa_charge[$i],
                'qty'                 => $request->qty[$i],
                'total'               => $request->total[$i],
                'discount_percentage' => $request->discount_percentage[$i],
                'tax'                 => $request->tax[$i],
                'net_amount'          => $request->net_amount[$i],
                'charge_note'         => $request->charge_note[$i],
                'date'                => $request->charge_date[$i],
            ]);
        }

        return redirect()->back()->with('success', 'Charges saved successfully!');
    }

    /**
     * Fetch a single IPD charge for editing.
     */
    public function getIpdCharge(IpdCharges $charge)
    {
        $charge->load(['charge.taxCategory', 'chargeCategory.chargeType']);

        return response()->json([
            'success' => true,
            'data'    => $charge,
        ]);
    }

    /**
     * Update a single IPD charge row (used by the IPD view "Edit Charges" modal).
     */
    public function updateIpdCharge(Request $request, IpdCharges $charge)
    {
        // Map existing form field names from the Add Charges modal
        $validated = $request->validate([
            'charge_type'                => 'required|integer|exists:charge_type_master,id',
            'charge_category2'           => 'required|integer|exists:charge_categories,id',
            'charge_id'                  => 'required|integer|exists:charges,id',
            'standard_charge'            => 'required|numeric',
            'schedule_charge'            => 'nullable|numeric',
            'qty'                        => 'required|numeric|min:1',
            'apply_charge'               => 'required|numeric',
            'discount_percentage_amount' => 'nullable|numeric|min:0',
            'tax'                        => 'nullable|numeric|min:0',
            'amount'                     => 'required|numeric',
            'note'                       => 'nullable|string',
            'date'                       => 'required|date',
        ]);

        $charge->update([
            'charge_type_id'      => $validated['charge_type'],
            'charge_category_id'  => $validated['charge_category2'],
            'charge_id'           => $validated['charge_id'],
            'standard_charge'     => $validated['standard_charge'],
            'tpa_charge'          => $validated['schedule_charge'] ?? 0,
            'qty'                 => $validated['qty'],
            'total'               => $validated['apply_charge'],
            // discount_percentage column stores discount amount in INR
            'discount_percentage' => $validated['discount_percentage_amount'] ?? 0,
            'tax'                 => $validated['tax'] ?? 0,
            'net_amount'          => $validated['amount'],
            'charge_note'         => $validated['note'] ?? null,
            'date'                => $validated['date'],
        ]);

        // Behaviour same as Add Charges: go back with flash message
        return redirect()
            ->back()
            ->with('success', 'Charge updated successfully!');
    }

    /**
     * Delete a single IPD charge row.
     */
    public function deleteIpdCharge(IpdCharges $charge)
    {
        $charge->delete();

        return redirect()
            ->back()
            ->with('success', 'Charge deleted successfully!');
    }

    public function getAvailableBeds(Request $request)
    {
        $bedGroupId   = $request->bed_group_id;
        $includeBedId = $request->include_bed_id; // For edit: include current bed even if occupied

        $occupiedBeds = PatientBedHistory::where('is_active', 'yes')
            ->pluck('bed_id')
            ->toArray();

        $query = Bed::where('bed_group_id', $bedGroupId);

        if ($includeBedId) {
            // For edit: show all free & active beds, plus the currently selected bed (even if inactive/occupied)
            $query->where(function ($q) use ($occupiedBeds, $includeBedId) {
                $q->where(function ($qq) use ($occupiedBeds) {
                    $qq->where('is_active', 'yes')
                        ->whereNotIn('id', $occupiedBeds);
                })->orWhere('id', $includeBedId);
            });
        } else {
            // For add: only active and not currently occupied beds
            $query->where('is_active', 'yes')
                ->whereNotIn('id', $occupiedBeds);
        }
        $beds = $query->get();
        return response()->json($beds);
    }
    public function assignNewBed(Request $request)
    {
        $request->validate([
            'released_date' => 'required|date',
            'bed_group'     => 'required',
            'new_bed'       => 'required',
            'bed_charge'    => 'required|numeric|min:0',
        ]);

        $ipd = IpdDetail::findOrFail($request->ipd_id);

        // --- Release old bed ---
        if ($ipd->bed) {

            // Mark old history inactive
            PatientBedHistory::where('ipd_id', $ipd->id)
                ->where('is_active', 'yes')
                ->update([
                    'is_active' => 'no',
                    'to_date'   => $request->released_date,
                ]);

            // Make old bed available
            Bed::where('id', $ipd->bed)->update(['is_active' => 'yes']);
        }

        // --- Assign new bed (hospital_id/branch_id required for billing consistency) ---
        PatientBedHistory::create([
            'hospital_id'  => $ipd->hospital_id ?? null,
            'branch_id'    => $ipd->branch_id ?? null,
            'ipd_id'       => $ipd->id,
            'bed_id'       => $request->new_bed,
            'bed_group_id' => $request->bed_group,
            'from_date'    => $request->released_date,
            'is_active'    => 'yes',
        ]);

        // Make new bed occupied
        Bed::where('id', $request->new_bed)->update(['is_active' => 'no']);

        // Update IPD record
        $ipd->bed          = $request->new_bed;
        $ipd->bed_group_id = $request->bed_group;
        $ipd->save();

        // --- Create bed charge entry for transfer date ---
        $transferDate = Carbon::parse($request->released_date);

        // Use on-the-fly bed charge if provided, otherwise fall back to bed group master
        $bedGroup      = BedGroup::find($request->bed_group);
        $bedChargeRate = $request->bed_charge !== null && $request->bed_charge !== ''
            ? (float) $request->bed_charge
            : (float) ($bedGroup->bed_cost ?? 0);

        $chargeDay = BedBillingPeriod::firstChargeCalendarDayFromAnchorDate($transferDate);
        $chargeDate = $chargeDay->format('Y-m-d');
        $periodDates = BedBillingPeriod::periodStorageDatesForChargeDay($chargeDay, $transferDate);
        $periodStartDate = $periodDates['period_start_date'];
        $periodEndDate   = $periodDates['period_end_date'];

        // Create or update bed charge entry for transfer date
        IpdDaywiseBedCharge::updateOrCreate(
            [
                'ipd_id'      => $ipd->id,
                'charge_date' => $chargeDate,
            ],
            [
                'hospital_id'       => $ipd->hospital_id,
                'branch_id'         => $ipd->branch_id ?? null,
                'case_reference_id' => $ipd->case_reference_id ?? null,
                'patient_id'        => $ipd->patient_id,
                'period_start_date' => $periodStartDate,
                'period_end_date'   => $periodEndDate,
                'bed_group_id'      => $request->bed_group,
                'bed_id'            => $request->new_bed,
                // Store the effective daily bed charge (custom or master) in both fields
                'bed_charge'        => $bedChargeRate,
                'bed_charge_rate'   => $bedChargeRate,
                'no_of_days'        => 1,
                'is_active'         => 'yes',
            ]
        );

        return redirect()->back()->with('success', 'Bed assigned successfully.');
    }

    /**
     * Update a bed history record (edit from Bed History table).
     * Affects: patient_bed_history, bed occupancy, ipd_details (if active), ipd_daywise_bed_charges.
     */
    public function updateBedHistory(Request $request, BedOccupancyService $bedOccupancyService)
    {
        $request->validate([
            'bed_history_id' => 'required|exists:patient_bed_history,id',
            'ipd_id'         => 'required|exists:ipd_details,id',
            'bed_group'      => 'required|exists:bed_group,id',
            'bed'            => 'required|exists:bed,id',
            'from_date'      => 'required|date',
            'to_date'        => 'nullable|date|after_or_equal:from_date',
            'bed_charge'     => 'nullable|numeric|min:0',
        ]);

        $history = PatientBedHistory::with(['bedGroup', 'bed'])->findOrFail($request->bed_history_id);
        if ((int) $history->ipd_id !== (int) $request->ipd_id) {
            return redirect()->back()->with('error', 'Invalid bed history for this IPD.');
        }

        $ipd      = IpdDetail::findOrFail($request->ipd_id);
        $oldBedId = $history->bed_id;
        $newBedId = (int) $request->bed;
        $isActive = $history->is_active === 'yes';

        // Check bed belongs to selected bed group
        $bed = Bed::findOrFail($newBedId);
        if ((int) $bed->bed_group_id !== (int) $request->bed_group) {
            return redirect()->back()->with('error', 'Selected bed must belong to the selected bed group.');
        }

        // Check for date overlaps with adjacent history records
        $fromDate = Carbon::parse($request->from_date);
        $toDate   = $request->to_date ? Carbon::parse($request->to_date) : null;

        // Use shared service to prevent overlaps / double-occupancy for this bed
        // Allow overlap with the same IPD's other history records; only block if some OTHER patient is on this bed
        $availability = $bedOccupancyService->checkAvailability(
            (int) $newBedId,
            $fromDate,
            $toDate,
            $history->id,
            $ipd->id
        );
        if (! $availability['available']) {
            return redirect()->back()->with('error', $availability['message']);
        }

        DB::beginTransaction();
        try {
            // 1. Update bed occupancy if bed changed
            if ((int) $oldBedId !== (int) $newBedId) {
                Bed::where('id', $oldBedId)->update(['is_active' => 'yes']); // Old bed available
                Bed::where('id', $newBedId)->update(['is_active' => 'no']);  // New bed occupied
            }

            // 2. Update patient_bed_history
            $history->bed_group_id = $request->bed_group;
            $history->bed_id       = $newBedId;
            $history->from_date    = $fromDate;
            $history->to_date      = $request->to_date ? $toDate : null;
            $history->save();

            // 3. If active record, update ipd_details
            if ($isActive) {
                $ipd->bed          = $newBedId;
                $ipd->bed_group_id = $request->bed_group;
                $ipd->save();
            }

            // 4. Update ipd_daywise_bed_charges for from_date (billing uses this)
            $bedChargeRate = $request->bed_charge !== null && $request->bed_charge !== ''
                ? (float) $request->bed_charge
                : (float) ($history->bedGroup->bed_cost ?? 0);

            $chargeDay = BedBillingPeriod::firstChargeCalendarDayFromAnchorDate($fromDate);
            $chargeDate = $chargeDay->format('Y-m-d');
            $periodDates = BedBillingPeriod::periodStorageDatesForChargeDay($chargeDay, $fromDate);

            IpdDaywiseBedCharge::updateOrCreate(
                [
                    'ipd_id'      => $ipd->id,
                    'charge_date' => $chargeDate,
                ],
                [
                    'hospital_id'       => $ipd->hospital_id,
                    'branch_id'         => $ipd->branch_id ?? null,
                    'case_reference_id' => $ipd->case_reference_id ?? null,
                    'patient_id'        => $ipd->patient_id,
                    'period_start_date' => $periodDates['period_start_date'],
                    'period_end_date'   => $periodDates['period_end_date'],
                    'bed_group_id'      => $request->bed_group,
                    'bed_id'            => $newBedId,
                    'bed_charge'        => $bedChargeRate,
                    'bed_charge_rate'   => $bedChargeRate,
                    'no_of_days'        => 1,
                    'is_active'         => 'yes',
                ]
            );

            DB::commit();
            return redirect()->back()->with('success', 'Bed history updated successfully. Estimate and final bill will reflect the changes.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update: ' . $e->getMessage());
        }
    }

    /**
     * Store a new bed history record from the Bed History popup (Add mode).
     * Uses the same modal UI as edit, without affecting existing edit behaviour.
     */
    public function storeBedHistory(Request $request, BedOccupancyService $bedOccupancyService)
    {
        $request->validate([
            'ipd_id'     => 'required|exists:ipd_details,id',
            'bed_group'  => 'required|exists:bed_group,id',
            'bed'        => 'required|exists:bed,id',
            'from_date'  => 'required|date',
            'to_date'    => 'nullable|date|after_or_equal:from_date',
            'bed_charge' => 'nullable|numeric|min:0',
        ]);

        $ipd = IpdDetail::findOrFail($request->ipd_id);

        $newBedId = (int) $request->bed;
        $bed      = Bed::findOrFail($newBedId);
        if ((int) $bed->bed_group_id !== (int) $request->bed_group) {
            return redirect()->back()->with('error', 'Selected bed must belong to the selected bed group.');
        }

        $fromDate = Carbon::parse($request->from_date);
        $toDate   = $request->to_date ? Carbon::parse($request->to_date) : null;

        // Prevent double-occupancy / overlaps for this bed via shared service
        // For add, also allow same-IPD history on this bed; only conflict with other patients
        $availability = $bedOccupancyService->checkAvailability(
            (int) $newBedId,
            $fromDate,
            $toDate,
            null,
            $ipd->id
        );
        if (! $availability['available']) {
            return redirect()->back()->with('error', $availability['message']);
        }

        DB::beginTransaction();
        try {
            // Create new bed history entry (non-active by default to avoid changing current bed unexpectedly)
            $history                    = new PatientBedHistory();
            $history->hospital_id       = $ipd->hospital_id;
            $history->branch_id         = $ipd->branch_id ?? null;
            $history->ipd_id            = $ipd->id;
            $history->case_reference_id = $ipd->case_reference_id ?? null;
            $history->bed_group_id      = $request->bed_group;
            $history->bed_id            = $newBedId;
            $history->from_date         = $fromDate;
            $history->to_date           = $request->to_date ? $toDate : null;
            $history->is_active         = 'no';
            $history->created_at        = now();
            $history->save();

            // Create / update daywise bed charge for the from_date so estimate/final bill consider it
            $bedChargeRate = $request->bed_charge !== null && $request->bed_charge !== ''
                ? (float) $request->bed_charge
                : (float) ($bed->bedGroup->bed_cost ?? 0);

            $chargeDay = BedBillingPeriod::firstChargeCalendarDayFromAnchorDate($fromDate);
            $chargeDate = $chargeDay->format('Y-m-d');
            $periodDates = BedBillingPeriod::periodStorageDatesForChargeDay($chargeDay, $fromDate);

            IpdDaywiseBedCharge::updateOrCreate(
                [
                    'ipd_id'      => $ipd->id,
                    'charge_date' => $chargeDate,
                ],
                [
                    'hospital_id'       => $ipd->hospital_id,
                    'branch_id'         => $ipd->branch_id ?? null,
                    'case_reference_id' => $ipd->case_reference_id ?? null,
                    'patient_id'        => $ipd->patient_id,
                    'period_start_date' => $periodStart->format('Y-m-d'),
                    'period_end_date'   => $periodEnd->format('Y-m-d'),
                    'bed_group_id'      => $request->bed_group,
                    'bed_id'            => $newBedId,
                    'bed_charge'        => $bedChargeRate,
                    'bed_charge_rate'   => $bedChargeRate,
                    'no_of_days'        => 1,
                    'is_active'         => 'yes',
                ]
            );

            DB::commit();
            return redirect()->back()->with('success', 'Bed history added successfully. Estimate and final bill will reflect the changes.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to add bed history: ' . $e->getMessage());
        }
    }

// public function storeDischarge(Request $request)
// {
//     // -------------------------------
//     // 🔹 Base validation rules
//     // -------------------------------
//     $rules = [
//         'ipd_details_id'   => ['nullable', 'integer', 'exists:ipd_details,id'],
//         'discharge_date'   => ['required', 'date'],
//         'discharge_status' => ['required', Rule::in(['death', 'referral', 'normal'])],
//         'note'             => ['nullable', 'string'],

//         'operation'        => ['nullable', 'string', 'max:255'],
//         'diagnosis'        => ['nullable', 'string', 'max:255'],
//         'investigation'    => ['nullable', 'string', 'max:255'],
//         'treatment_home'   => ['nullable', 'string', 'max:255'],
//     ];

//     // -------------------------------
//     // 🔹 Conditional validation
//     // -------------------------------
//     if ($request->discharge_status === 'death') {
//         $rules = array_merge($rules, [
//             'death_date'    => ['required', 'date'],
//             'guardian_name' => ['required', 'string', 'max:255'],
//             'attachment'    => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'],
//             'report'        => ['nullable', 'string'],
//         ]);
//     }

//     if ($request->discharge_status === 'referral') {
//         $rules = array_merge($rules, [
//             'referral_date'          => ['required', 'date'],
//             'referral_hospital_name' => ['required', 'string', 'max:255'],
//             'referral_reason'        => ['required', 'string', 'max:255'],
//         ]);
//     }

//     // -------------------------------
//     // 🔹 Validate request
//     // -------------------------------
//     $validated = $request->validate($rules);

//     // -------------------------------
//     DB::beginTransaction();
//     try {
//         // -------------------------------
//         // 🔹 Handle file upload (death case)
//         // -------------------------------
//         $attachmentPath = null;

//         if ($request->hasFile('attachment')) {
//             $attachmentPath = $request->file('attachment')
//                 ->store('discharge_attachments', 'public');
//         }

//         // -------------------------------
//         // 🔹 Create Discharge Card
//         // -------------------------------
//         // dd($validated);
//         $discharge = DischargeCard::create([
//             'hospital_id'         => Auth::user()->hospital_id ?? null,
//             'branch_id'           => Auth::user()->branch_id ?? null,
//             'case_reference_id'   => $request->case_reference_id ?? null,
//             'opd_details_id'      => $request->opd_details_id ?? null,
//             'ipd_details_id'      => intval($validated['ipd_details_id']),

//             'discharge_by'        => Auth::id(),
//             'discharge_date'      => $validated['discharge_date'],
//             'discharge_status'    => $validated['discharge_status'],

//             'death_date'          => $validated['death_date'] ?? null,
//             'refer_date'          => $validated['referral_date'] ?? null,
//             'refer_to_hospital'   => $validated['referral_hospital_name'] ?? null,
//             'reason_for_referral' => $validated['referral_reason'] ?? null,

//             'operation'           => $validated['operation'] ?? null,
//             'diagnosis'           => $validated['diagnosis'] ?? null,
//             'investigations'      => $validated['investigation'] ?? null,
//             'treatment_home'      => $validated['treatment_home'] ?? null,
//             'note'                => $validated['note'] ?? null,
//         ]);

//         IpdDetail::where('id', intval($validated['ipd_details_id']))
//             ->update(['discharged' => 'yes']);
//         // dd($discharge);

//         DB::commit();

//         return redirect()
//             ->back()
//             ->with('success', 'Patient discharged successfully.');

//     } catch (\Exception $e) {
//         DB::rollBack();

//         return redirect()
//             ->back()
//             ->withErrors(['error' => 'Something went wrong while saving discharge details.'])
//             ->withInput();
//     }
// }
    public function reports()
    {
        return view('admin.reports.ipd.index');
    }
    public function ipdReport(Request $request)
    {
        $perPage = (int) $request->input('perPage', 10);
        if ($perPage <= 0) {
            $perPage = 10;
        }
        $ipdReports = IpdDetail::query()

            ->with(['patient', 'doctor'])

            ->when($request->date_from && $request->date_to, function ($q) use ($request) {
                $q->whereBetween('admission_date', [
                    $request->date_from,
                    $request->date_to,
                ]);
            })

            ->when($request->gender, function ($q) use ($request) {
                $q->whereHas('patient', function ($sub) use ($request) {
                    $sub->where('gender', $request->gender);
                });
            })

            ->when($request->search, function ($q) use ($request) {
                $q->where('ipd_no', 'like', '%' . $request->search . '%');
            })

            ->orderByDesc('id')
            ->paginate($perPage)
            ->appends($request->all());
        //dd($ipdReports);

        return view('admin.reports.ipd.ipd_reports', compact('ipdReports'));
    }
    public function ipdBalanceReport(Request $request)
    {
        $perPage = (int) $request->input('perPage', 10);
        if ($perPage <= 0) {
            $perPage = 10;
        }
        $ipdReports = IpdDetail::query()

            ->with(['patient', 'visits'])

            ->withSum('charge as amount_charged', 'standard_charge')
            ->withSum('transactions as amount_paid', 'amount')

            ->when($request->date_from && $request->date_to, function ($q) use ($request) {
                $q->whereHas('visits', function ($sub) use ($request) {
                    $sub->whereBetween('admission_date', [
                        $request->date_from,
                        $request->date_to,
                    ]);
                });
            })

            ->when($request->gender, function ($q) use ($request) {
                $q->whereHas('patient', function ($sub) use ($request) {
                    $sub->where('gender', $request->gender);
                });
            })

            ->when($request->search, function ($q) use ($request) {
                $q->where('ipd_no', 'like', '%' . $request->search . '%');
            })

            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        // ✅ Replace map() with transform()
        $ipdReports->getCollection()->transform(function ($ipd) {
            $ipd->amount_charged = $ipd->amount_charged ?? 0;
            $ipd->amount_paid    = $ipd->amount_paid ?? 0;
            $ipd->balance        = $ipd->amount_charged - $ipd->amount_paid;
            return $ipd;
        });
        // ->get()
        // ->map(function ($ipd) {
        //     $ipd->amount_charged = $ipd->amount_charged ?? 0;
        //     $ipd->amount_paid = $ipd->amount_paid ?? 0;
        //     $ipd->balance = $ipd->amount_charged - $ipd->amount_paid;
        //     return $ipd;
        // });

        return view('admin.reports.ipd.ipd_balance_reports', compact('ipdReports'));
    }
    public function ipdDischargeReport(Request $request)
    {

        $perPage = (int) $request->input('perPage', 10);
        if ($perPage <= 0) {
            $perPage = 10;
        }

        $discharges = DischargeCard::query()

            ->with('ipdDetails') // optional if you need IPD info

            ->whereNotNull('ipd_details_id') // ✅ Only IPD patients

            ->when($request->date_from && $request->date_to, function ($q) use ($request) {
                $q->whereBetween('discharge_date', [
                    $request->date_from,
                    $request->date_to,
                ]);
            })

            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('patient_name', 'like', '%' . $request->search . '%')
                        ->orWhere('patient_id', 'like', '%' . $request->search . '%')
                        ->orWhere('discharge_number', 'like', '%' . $request->search . '%')
                        ->orWhere('admission_no', 'like', '%' . $request->search . '%');
                });
            })

            ->orderByDesc('discharge_date')
        // ->get();
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.reports.ipd.ipd_discharge_patient', compact('discharges'));
    }

    /**
     * Apply a package to an IPD patient
     * POST /ipd/{id}/apply-package
     */
    public function applyPackage(Request $request, $id)
    {
        $request->validate([
            'package_id'   => 'required|exists:packages,id',
            'applied_date' => 'nullable|date_format:Y-m-d',
            'notes'        => 'nullable|string|max:500',
            'package_rate' => 'nullable|numeric|min:0',
        ]);

        try {
            $packageService      = new IpdPackageService();
            $packageRateOverride = $request->filled('package_rate') ? (float) $request->package_rate : null;

            $result = $packageService->applyPackage(
                $id,
                $request->package_id,
                $request->applied_date,
                $request->notes,
                $packageRateOverride
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'data'    => $result['data'],
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], 422);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error applying package: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove a package from an IPD patient
     * DELETE /ipd/{id}/remove-package
     */
    public function removePackage(Request $request, $id)
    {
        $request->validate([
            'ipd_package_id' => 'required|exists:ipd_packages,id',
        ]);

        try {
            $packageService = new IpdPackageService();

            $result = $packageService->removePackage($id, $request->ipd_package_id);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], 422);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing package: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update package amount for an applied package (on-the-fly).
     * PATCH /ipd/{id}/packages/{ipdPackageId}
     */
    public function updatePackageAmount(Request $request, $id, $ipdPackageId)
    {
        $request->validate([
            'package_rate' => 'required|numeric|min:0',
        ]);

        try {
            $packageService = new IpdPackageService();
            $result         = $packageService->updatePackageAmount($id, $ipdPackageId, $request->package_rate);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'data'    => $result['data'] ?? null,
                ], 200);
            }
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating package amount: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get available packages and applied packages for an IPD patient
     * GET /ipd/{id}/packages
     */
    public function getIpdPackages(Request $request, $id)
    {
        try {
            $ipd = IpdDetail::findOrFail($id);

            // Get all active packages
            $availablePackages = Package::where('is_active', true)
                ->get(['id', 'name', 'package_rate', 'gst_amount', 'description']);

            // Get applied packages
            $packageService  = new IpdPackageService();
            $appliedPackages = $packageService->getAppliedPackages($id, 'applied');

            return response()->json([
                'success'            => true,
                'available_packages' => $availablePackages,
                'applied_packages'   => $appliedPackages,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching packages: ' . $e->getMessage(),
            ], 500);
        }
    }

}
