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
use App\Models\Pathology;
use App\Models\PathologyReport;
use App\Models\Patient;
use App\Models\PatientBedHistory;
use App\Models\Prefix;
use App\Models\Radio;
use App\Models\Staff;
use App\Models\Symptom;
use App\Models\SymptomsClassification;
use App\Models\Package;
use App\Models\IpdPackage;
use App\Services\IpdPackageService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;

class IpdController extends Controller
{
    public function index(Request $request)
    {
        $search     = $request->get('search');
        $isIpdTab   = $request->get('tab', 'ipd') == 'ipd';
        $doctors    = Doctor::all();
        $bedGroups  = BedGroup::with('floorDetail')->get();
        $chargeType = ChargeTypeMaster::all();
        $charges    = Charge::all();
        $references = ['Direct', 'Doctor', 'Marketer', 'Other'];
        if ($isIpdTab) {
            $ipd = IpdDetail::with('patient', 'ipdPatients', 'doctor', 'bedDetail', 'bedGroup.floorDetail')->where('discharged', null)
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('ipd_no', 'LIKE', "%{$search}%")
                            ->orWhereHas('patient', function ($p) use ($search) {
                                $p->where('patient_name', 'LIKE', "%{$search}%")
                                    ->orWhere('mobileno', 'LIKE', "%{$search}%")
                                    ->orWhere('email', 'LIKE', "%{$search}%");
                            });

                        // Consultant (Doctor)
                        //     ->orWhereHas('doctor', function ($d) use ($search) {
                        //         $d->where('name', 'LIKE', "%{$search}%");
                        //     });
                    });
                })->get();

            // Attach billing summary (total payments, outstanding) for each IPD
            $billingController = app(\App\Http\Controllers\IpdBillingController::class);
            foreach ($ipd as $ipdDetails) {
                $summary = $billingController->getBillingSummaryForIpd($ipdDetails->id);
                $ipdDetails->total_payments = $summary['total_payments'];
                $ipdDetails->outstanding = $summary['outstanding'];
            }
        } else {
            // $patients = Patient::with(['ipds.doctor'])->get();
            $patients = IpdDetail::with('patient', 'ipdPatients', 'doctor')->where('discharged', 'yes')
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->whereHas('patient', function ($p) use ($search) {
                            $p->where('patient_name', 'LIKE', "%{$search}%")
                                ->orWhere('mobileno', 'LIKE', "%{$search}%")
                                ->orWhere('email', 'LIKE', "%{$search}%");
                        });
                    });
                })->get();
            $ipd = $patients;
        }
        return view("admin.ipd.index", compact("ipd", 'doctors', 'isIpdTab', 'bedGroups', 'references'));
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
            $hasBed     = !empty($request->bed_group) && !empty($request->bed_number);
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
                    $bedHistory->hospital_id   = $ipd->hospital_id ?? $user->hospital_id ?? null;
                    $bedHistory->branch_id     = $ipd->branch_id ?? $user->branch_id ?? null;
                    $bedHistory->bed_group_id  = $request->bed_group;
                    $bedHistory->ipd_id        = $ipd->id ?? null;
                    $bedHistory->bed_id        = $request->bed_number;
                    $bedHistory->from_date     = $request->admission_date;
                    $bedHistory->is_active     = 'yes';
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
                $chargeDate    = $admissionDate->format('Y-m-d');

                // Start: Previous day 10:00 AM
                $periodStart = $admissionDate->copy()->subDay()->setTime(10, 0, 0);
                // End: Current day 10:00 AM
                $periodEnd = $admissionDate->copy()->setTime(10, 0, 0);

                $periodStartDate = $periodStart->format('Y-m-d');
                $periodEndDate   = $periodEnd->format('Y-m-d');

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

            // Apply package if selected during admission
            if ($request->package_id) {
                $packageService = new IpdPackageService();
                $packageResult = $packageService->applyPackage(
                    $ipd->id,
                    $request->package_id,
                    $request->admission_date, // Apply package from admission date
                    'Applied during IPD admission'
                );

                if (!$packageResult['success']) {
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
        $ipd     = IpdDetail::with('patient', 'doctor', 'bedDetail', 'bedGroup.floorDetail')->where('id', $id)->firstOrFail();
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
        return view('admin.ipd.edit-ipd', compact('ipd', 'doctors', 'symptomTypeIds', 'allSymptomTypes', 'symptoms', 'bedGroups', 'bedNumbers', 'patients'));

    }

    public function update(Request $request, $id)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'patient_id'           => 'required|exists:patients,id',
            'admission_date'       => 'required|date',
            'old_patient'          => 'required|string',
            'casualty'             => 'required|string',
            'date'                => 'nullable|date',
        ]);
        try {
            $symptomTitle         = array_filter($request->symptoms_title, fn($title) => $title !== null && $title !== '');
            $implodedSymptomType  = implode(", ", $symptomType);
            $implodedSymptomTitle = implode(", ", $symptomTitle);
            // 🔹 Update OPD record
            $ipd        = IpdDetail::findOrFail($id);
            $allotedBed = $ipd->bed;
            //dd($id, IpdPatient::where('ipd_id', $id)->first());
            $ipdPatient = IpdPatient::where('ipd_id', $id)->firstOrFail();
            if ($request->bed_number != $allotedBed) {
                $newBedDetail          = Bed::where('id', $request->bed_number)->firstOrFail();
                $allotedBedDetail      = Bed::where('id', $allotedBed)->firstOrFail();
                $bedhistory            = PatientBedHistory::where('ipd_id', $id)->firstOrFail();
                $bedhistory->bed_group = $request->bed_group_id;
                $bedhistory->bed_id    = $request->bed_number;
                $bedhistory->save();
                $newBedDetail->is_active = 'no';
                $newBedDetail->save();
                $allotedBedDetail->is_active = 'yes';
                $allotedBedDetail->save();
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
            return response()->json($bedGroups, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
        } catch (\Exception $e) {
            \Log::error('Error fetching bed groups: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
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
        $findings = $request->input('findings', []);
        
        // Ensure they're arrays
        if (!is_array($findingType)) {
            $findingType = $findingType ? [$findingType] : [];
        }
        if (!is_array($findings)) {
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
            'findings'          => array_values($findings), // Re-index array
        ]);

        \Log::info('After conversion - Medicines:', $medicines);
        \Log::info('After conversion - Types:', array_map('gettype', $medicines));

        // dd($request->all());
        try { $request->validate([
            'ipd_id'              => 'nullable|string',
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
            $findings        = array_filter($request->input('findings', []), fn($title) => $title !== null && $title !== '');
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
            
            $pathology_ids   = array_filter($pathologyInput, fn($pathology) => $pathology !== null && $pathology !== '');
            $radiology_ids   = array_filter($radiologyInput, fn($radio) => $radio !== null && $radio !== '');
            
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
                // Create prescription
                $prescription = IpdPrescription::create([
                    'prescription_number' => $prescriptionNo,
                    'ipd_id'              => $request->ipd_id,
                    'prescribed_by'       => $request->prescribe_by, // NEW
                    'header_note'         => $request->header_note ?? null,
                    'footer_note'         => $request->footer_note ?? null,
                    'advice'              => $request->advice ?? null,
                    'finding_description' => $request->finding_description ?? null,
                    'is_finding_print'    => $request->finding_print ?? 'no',
                    'date'                => Carbon::now()->toDateString(),
                    'finding_categories'  => $implodedFindingTypes,
                    'findings'            => $implodedFindings,
                    'pathology_id'        => ! empty($pathology_ids) ? implode(", ", $pathology_ids) : null, // Keep for backward compatibility
                    'radiology_id'        => ! empty($radiology_ids) ? implode(", ", $radiology_ids) : null, // Keep for backward compatibility
                    'notification_to'     => $implodedVisibles,
                    'attachment'          => $attachment,     // NEW
                    'attachment_name'     => $attachmentName, // NEW
                ]);

                // Store tests in normalized table with instance tracking
                $prescriptionDate = $prescription->date ?? Carbon::now()->toDateString();
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
                            'instance_number'    => $instanceNumber,
                            'test_date'          => $prescriptionDate,
                            'prescription_time'  => $prescriptionTime,
                            'notes'              => $notes,
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
                            'instance_number'    => $instanceNumber,
                            'test_date'          => $prescriptionDate,
                            'prescription_time'  => $prescriptionTime,
                            'notes'              => $notes,
                            'hospital_id'         => $user->hospital_id ?? '00000001',
                            'branch_id'           => $user->branch_id ?? '00000001',
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
                    'success' => true,
                    'message' => 'Prescription created successfully.',
                    'prescription_id' => $prescription->id
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
                    'message' => 'Something went wrong: ' . $e->getMessage()
                ], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Prescription validation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            \Log::error('Prescription error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
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

        $doctors     = Doctor::all();
        $findings    = Finding::all();
        // Use Eloquent so all tests (including newly added) are available; no extra scope
        $pathologies = Pathology::orderBy('test_name')->get();
        $radiologies = Radio::orderBy('test_name')->get();

        // Get selected test IDs in order (with notes) for selected-list UI
        $pathologyTestsForList = [];
        $radiologyTestsForList = [];
        foreach ($prescription->tests as $t) {
            if ($t->pathology_id) {
                $path = $t->pathology ?? \App\Models\Pathology::find($t->pathology_id);
                $pathologyTestsForList[] = [
                    'id'    => $t->pathology_id,
                    'name'  => $path ? ($path->test_name . ($path->short_name ? ' (' . $path->short_name . ')' : '')) : 'ID ' . $t->pathology_id,
                    'notes' => $t->notes ?? '',
                ];
            }
            if ($t->radiology_id) {
                $rad = $t->radiology ?? \App\Models\Radio::find($t->radiology_id);
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
                $path = \App\Models\Pathology::find($pathId);
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
                $rad = \App\Models\Radio::find($radId);
                $radiologyTestsForList[] = [
                    'id'    => (int) $radId,
                    'name'  => $rad ? ($rad->test_name . ($rad->short_name ? ' (' . $rad->short_name . ')' : '')) : 'ID ' . $radId,
                    'notes' => '',
                ];
            }
        }
        $selectedPathologyIds = array_column($pathologyTestsForList, 'id');
        $selectedRadiologyIds = array_column($radiologyTestsForList, 'id');

        $prescriptionDate = $prescription->date ?? Carbon::now()->toDateString();
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
                    'date'                => $request->date ?? $prescription->date,
                    'attachment'          => $attachment,
                    'attachment_name'     => $attachmentName,
                ]);

                // Delete existing tests (but preserve billing links - only delete if not billed)
                // Check which tests are already billed before deleting
                $billedTestIds = \App\Models\PathologyReport::whereNotNull('ipd_prescription_test_id')
                    ->pluck('ipd_prescription_test_id')
                    ->merge(
                        \App\Models\RadiologyReport::whereNotNull('ipd_prescription_test_id')
                            ->pluck('ipd_prescription_test_id')
                    );
                
                // Only delete tests that haven't been billed
                IpdPrescriptionTest::where('ipd_prescription_id', $prescription->id)
                    ->whereNotIn('id', $billedTestIds)
                    ->delete();

                // Store new tests with instance tracking
                $prescriptionDate = $prescription->date ?? Carbon::now()->toDateString();
                $prescriptionTime = Carbon::now()->format('H:i:s');
                
                if (! empty($pathology_ids)) {
                    $pathologyNotes = $request->input('pathology_notes');
                    if (! is_array($pathologyNotes)) {
                        $pathologyNotes = [];
                    }
                    foreach ($pathology_ids as $index => $pathologyId) {
                        $instanceNumber = IpdPrescriptionTest::getNextInstanceNumber(
                            (int) $pathologyId,
                            $prescriptionDate,
                            'pathology',
                            $prescription->id
                        );
                        $notes = $pathologyNotes[$index] ?? $request->input("pathology_notes_{$pathologyId}") ?? null;
                        IpdPrescriptionTest::create([
                            'ipd_prescription_id' => $prescription->id,
                            'pathology_id'        => (int) $pathologyId,
                            'radiology_id'        => null,
                            'instance_number'    => $instanceNumber,
                            'test_date'          => $prescriptionDate,
                            'prescription_time'  => $prescriptionTime,
                            'notes'              => $notes,
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
                        $instanceNumber = IpdPrescriptionTest::getNextInstanceNumber(
                            (int) $radiologyId,
                            $prescriptionDate,
                            'radiology',
                            $prescription->id
                        );
                        $notes = $radiologyNotes[$index] ?? $request->input("radiology_notes_{$radiologyId}") ?? null;
                        IpdPrescriptionTest::create([
                            'ipd_prescription_id' => $prescription->id,
                            'pathology_id'        => null,
                            'radiology_id'        => (int) $radiologyId,
                            'instance_number'    => $instanceNumber,
                            'test_date'          => $prescriptionDate,
                            'prescription_time'  => $prescriptionTime,
                            'notes'              => $notes,
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
                    'success' => true,
                    'message' => 'Prescription updated successfully.',
                    'prescription_id' => $prescription->id
                ], 200);
            } catch (Exception $e) {
                DB::rollBack();
                \Log::error('Prescription update error: ' . $e->getMessage());
                \Log::error('Stack trace: ' . $e->getTraceAsString());
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong: ' . $e->getMessage()
                ], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Prescription validation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            \Log::error('Prescription update error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
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

            // Delete associated tests and medicines (cascade should handle this, but being explicit)
            IpdPrescriptionTest::where('ipd_prescription_id', $prescription->id)->delete();
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
            'charge_type'                 => 'required|integer|exists:charge_type_master,id',
            'charge_category2'            => 'required|integer|exists:charge_categories,id',
            'charge_id'                   => 'required|integer|exists:charges,id',
            'standard_charge'             => 'required|numeric',
            'schedule_charge'             => 'nullable|numeric',
            'qty'                         => 'required|numeric|min:1',
            'apply_charge'                => 'required|numeric',
            'discount_percentage_amount'  => 'nullable|numeric|min:0',
            'tax'                         => 'nullable|numeric|min:0',
            'amount'                      => 'required|numeric',
            'note'                        => 'nullable|string',
            'date'                        => 'required|date',
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

    public function getAvailableBeds(Request $request)
    {
        $bedGroupId = $request->bed_group_id;

        // Beds currently assigned (is_active = 1)
        $occupiedBeds = PatientBedHistory::where('is_active', 'yes')
            ->pluck('bed_id')
            ->toArray();

        // Fetch beds excluding occupied ones
        $availableBeds = Bed::where('bed_group_id', $bedGroupId)
            ->where('is_active', 'yes')
            ->whereNotIn('id', $occupiedBeds)
            ->get();

        return response()->json($availableBeds);
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
        $chargeDate   = $transferDate->format('Y-m-d');

        // Use on-the-fly bed charge if provided, otherwise fall back to bed group master
        $bedGroup = BedGroup::find($request->bed_group);
        $bedChargeRate = $request->bed_charge !== null && $request->bed_charge !== ''
            ? (float) $request->bed_charge
            : (float) ($bedGroup->bed_cost ?? 0);

        // Calculate period (10 AM to next 10 AM)
        // Start: Previous day 10:00 AM
        $periodStart = $transferDate->copy()->subDay()->setTime(10, 0, 0);
        // End: Current day 10:00 AM
        $periodEnd = $transferDate->copy()->setTime(10, 0, 0);

        $periodStartDate = $periodStart->format('Y-m-d');
        $periodEndDate   = $periodEnd->format('Y-m-d');

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

    public function storeDischarge(Request $request)
    {
        // -------------------------------
        // 🔹 Validation Rules (Form-based)
        // -------------------------------
        // dd($request->all());
        $validated = $request->validate([
            'ipd_details_id'     => ['required', 'integer', 'exists:ipd_details,id'],
            'patient_name'       => ['required', 'string', 'max:255'],
            'patient_id'         => ['nullable', 'integer'],
            'admission_no'       => ['nullable', 'string'],
            'discharge_date'     => ['required', 'date'],
            'discharge_time'     => ['nullable'],
            'admission_date'     => ['nullable', 'date'],
            'admit_time'         => ['nullable'],
            'bed'                => ['nullable', 'string'],
            'age'                => ['nullable', 'string'],
            'gender'             => ['nullable', 'string'],
            'phone'              => ['nullable', 'string'],
            'marital_status'     => ['nullable', 'string'],
            'address'            => ['nullable', 'string'],
            'guardian'           => ['nullable', 'string'],
            'relation'           => ['nullable', 'string'],
            'nationality'        => ['nullable', 'string'],
            'under_care_dr'      => ['nullable', 'string'],
            'registration_no'    => ['nullable', 'string'],
            'referral'           => ['nullable', 'string'],
            'corporate'          => ['nullable', 'string'],
            'reason_discharge'   => ['nullable', 'string'],
            'ot_date'            => ['nullable', 'date'],
            'ot_type'            => ['nullable', 'string'],
            'ot_name'            => ['nullable', 'string'],
            'ot_done'            => ['nullable', 'integer'],
            'ot_done_by'         => ['nullable', 'array'],
            'ot_done_by.*'       => ['string'],
            'diagnosis'          => ['nullable', 'string'],
            'ot_note'            => ['nullable', 'string'],
            'discharge_advice'   => ['nullable', 'string'],
            'investigation'      => ['nullable', 'string'],
            'urgent_care'        => ['nullable', 'string'],
            'diet_advice'        => ['nullable', 'string'],
            'course_in_hospital' => ['nullable', 'string'],
            'present_complaints' => ['nullable', 'string'],
            'remarks'            => ['nullable', 'string'],
            'meds'               => ['nullable', 'array'],
            'meds.*'             => ['string'],
            'med_interval'       => ['nullable', 'array'],
            'med_interval.*'     => ['string'],
            'med_duration'       => ['nullable', 'array'],
            'med_duration.*'     => ['string'],
            'discharged_by'      => ['nullable', 'string'],
            'current_user'       => ['nullable', 'string'],
        ]);

        // dd($validated);
        DB::beginTransaction();

        try {
            // -------------------------------
            // 🔹 Create Discharge Card
            // -------------------------------
            $lastDischarge = DischargeCard::orderBy('id', 'desc')->first();
            if ($lastDischarge && preg_match('/D-(\d+)/', $lastDischarge->discharge_number, $matches)) {
                $lastNumber = intval($matches[1]);
            } else {
                $lastNumber = 0;
            }
            $nextNumber  = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            $dischargeNo = 'D-' . $nextNumber;

            $meds              = array_filter($request->meds, fn($med) => $med !== null && $med !== '');
            $intervals         = array_filter($request->med_interval, fn($interval) => $interval !== null && $interval !== '');
            $durations         = array_filter($request->med_duration, fn($duration) => $duration !== null && $duration !== '');
            $implodedMeds      = implode(", ", $meds);
            $implodedIntervals = implode(", ", $intervals);
            $implodedDurations = implode(", ", $durations);

            $barcodePayload = [
                'type'               => 'DISCHARGE',
                'discharge_no'       => $dischargeNo,
                'ipd_details_id'     => $validated['ipd_details_id'],
                'patient_name'       => $validated['patient_name'],
                'patient_id'         => $validated['patient_id'],
                'admission_no'       => $validated['admission_no'] ?? null,
                'admission_date'     => $validated['admission_date'] ?? null,
                'admit_time'         => $validated['admit_time'] ?? null,
                'discharge_date'     => $validated['discharge_date'],
                'discharge_time'     => $validated['discharge_time'] ?? null,
                'bed'                => $validated['bed'] ?? null,
                'age'                => $validated['age'] ?? null,
                'gender'             => $validated['gender'] ?? null,
                'phone'              => $validated['phone'] ?? null,
                'marital_status'     => $validated['marital_status'] ?? null,
                'address'            => $validated['address'] ?? null,
                'guardian'           => $validated['guardian'] ?? null,
                'relation'           => $validated['relation'] ?? null,
                'nationality'        => $validated['nationality'] ?? null,
                'under_care_dr'      => $validated['under_care_dr'] ?? null,
                'registration_no'    => $validated['registration_no'] ?? null,
                'referral'           => $validated['referral'] ?? null,
                'corporate'          => $validated['corporate'] ?? null,
                'reason_discharge'   => $validated['reason_discharge'] ?? null,
                'ot_date'            => $validated['ot_date'] ?? null,
                'ot_type'            => $validated['ot_type'] ?? null,
                'ot_name'            => $validated['ot_name'] ?? null,
                'ot_done'            => $validated['ot_done'] ?? null,
                'ot_done_by'         => $request->ot_done_by ?? [],
                'diagnosis'          => $validated['diagnosis'] ?? null,
                'ot_note'            => $validated['ot_note'] ?? null,
                'discharge_advice'   => $validated['discharge_advice'] ?? null,
                'investigation'      => $validated['investigation'] ?? null,
                'urgent_care'        => $validated['urgent_care'] ?? null,
                'diet_advice'        => $validated['diet_advice'] ?? null,
                'course_in_hospital' => $validated['course_in_hospital'] ?? null,
                'present_complaints' => $validated['present_complaints'] ?? null,
                'remarks'            => $validated['remarks'] ?? null,
                'discharged_by'      => $validated['discharged_by'] ?? null,
            ];

            $json             = json_encode($barcodePayload, JSON_UNESCAPED_UNICODE);
            $compressed       = gzcompress($json, 9);
            $barcodeValue     = base64_encode($compressed);
            $barcodePngBase64 = DNS1D::getBarcodePNG(
                $barcodeValue,
                'C128',
                2,
                60
            );
            $barcodeBinary = base64_decode($barcodePngBase64);
            // dd($barcodeBinary);
            $discharge = DischargeCard::create([
                'hospital_id'        => Auth::user()->hospital_id ?? null,
                'branch_id'          => Auth::user()->branch_id ?? null,
                'ipd_details_id'     => $validated['ipd_details_id'],
                'discharge_number'   => $dischargeNo,
                'patient_name'       => $validated['patient_name'],
                'patient_id'         => $validated['patient_id'],
                'admission_no'       => $validated['admission_no'] ?? null,
                'barcode'            => $barcodeBinary,

                'discharge_date'     => $validated['discharge_date'],
                'discharge_time'     => $validated['discharge_time'] ?? null,
                'admission_date'     => $validated['admission_date'],
                'admit_time'         => $validated['admit_time'] ?? null,
                'bed'                => $validated['bed'] ?? null,

                'age'                => $validated['age'] ?? null,
                'gender'             => $validated['gender'] ?? null,
                'phone'              => $validated['phone'] ?? null,
                'marital_status'     => $validated['marital_status'] ?? null,
                'address'            => $validated['address'] ?? null,

                'guardian'           => $validated['guardian'] ?? null,
                'relation'           => $validated['relation'] ?? null,
                'nationality'        => $validated['nationality'] ?? null,

                'under_care_dr'      => $validated['under_care_dr'] ?? null,
                'registration_no'    => $validated['registration_no'] ?? null,
                'referral'           => $validated['referral'] ?? null,
                'corporate'          => $validated['corporate'] ?? null,

                'reason_discharge'   => $validated['reason_discharge'] ?? null,

                'ot_date'            => $validated['ot_date'] ?? null,
                'ot_type'            => $validated['ot_type'] ?? null,
                'ot_name'            => $validated['ot_name'] ?? null,
                'ot_done'            => $validated['ot_done'] ?? null,
                'ot_done_by'         => is_array($request->ot_done_by)
                    ? implode(',', $request->ot_done_by)
                    : null,

                'diagnosis'          => $validated['diagnosis'] ?? null,
                'ot_note'            => $validated['ot_note'] ?? null,
                'discharge_advice'   => $validated['discharge_advice'] ?? null,
                'investigation'      => $validated['investigation'] ?? null,
                'urgent_care'        => $validated['urgent_care'] ?? null,
                'diet_advice'        => $validated['diet_advice'] ?? null,
                'course_in_hospital' => $validated['course_in_hospital'] ?? null,
                'present_complaints' => $validated['present_complaints'] ?? null,
                'remarks'            => $validated['remarks'] ?? null,

                'medicines'          => $implodedMeds ?? null,
                'intervals'          => $implodedIntervals ?? null,
                'durations'          => $implodedDurations ?? null,

                'discharged_by'      => $validated['discharged_by'] ?? null,
                'created_by'         => Auth::id(),
            ]);

            // -------------------------------
            // 🔹 Mark IPD as Discharged
            // -------------------------------
            IpdDetail::where('id', $validated['ipd_details_id'])
                ->update(['discharged' => 'yes']);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Patient discharged successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            \Log::error($e);
            return back()
                ->with('error', 'Something went wrong while saving discharge details.')
                ->withInput();
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
                        $request->date_to
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
        $ipd->amount_paid = $ipd->amount_paid ?? 0;
        $ipd->balance = $ipd->amount_charged - $ipd->amount_paid;
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
                    $request->date_to
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
            'package_id' => 'required|exists:packages,id',
            'applied_date' => 'nullable|date_format:Y-m-d',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $packageService = new IpdPackageService();
            
            $result = $packageService->applyPackage(
                $id,
                $request->package_id,
                $request->applied_date,
                $request->notes
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'data' => $result['data'],
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
            $packageService = new IpdPackageService();
            $appliedPackages = $packageService->getAppliedPackages($id, 'applied');

            return response()->json([
                'success' => true,
                'available_packages' => $availablePackages,
                'applied_packages' => $appliedPackages,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching packages: ' . $e->getMessage(),
            ], 500);
        }
    }

}

