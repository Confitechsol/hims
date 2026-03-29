<?php
namespace App\Http\Controllers;

use App\Models\Bed;
use App\Models\DischargeCard;
use App\Models\Doctor;
use App\Models\IpdDetail;
use App\Models\PatientBedHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;

class DischargeController extends Controller
{
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
            'discharge_contact'  => ['nullable', 'string'],
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
            'meds.*'             => ['nullable', 'string'],
            'med_types'          => ['nullable', 'array'],
            'med_types.*'        => ['nullable', 'string'],
            'med_interval'       => ['nullable', 'array'],
            'med_interval.*'     => ['nullable', 'string'],
            'med_duration'       => ['nullable', 'array'],
            'med_duration.*'     => ['nullable', 'string'],
            'med_date'           => ['nullable', 'array'],
            'med_date.*'         => ['nullable', 'date'],
            'doctor_advice'      => ['nullable', 'string'],
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
            // $meds        = array_filter((array) $request->meds, fn($med) => $med !== null && $med !== '');
            // $medTypes    = array_filter((array) $request->med_types, fn($type) => $type !== null && $type !== '');
            // $intervals   = array_filter((array) $request->med_interval, fn($interval) => $interval !== null && $interval !== '');
            // $durations   = array_filter((array) $request->med_duration, fn($duration) => $duration !== null && $duration !== '');

            $meds      = $request->meds ?? [];
            $types     = $request->med_types ?? [];
            $intervals = $request->med_interval ?? [];
            $durations = $request->med_duration ?? [];
            $dates     = $request->med_date ?? [];

            $finalMeds      = [];
            $finalTypes     = [];
            $finalIntervals = [];
            $finalDurations = [];
            $finalDates     = [];

            foreach ($meds as $i => $med) {

                // skip completely empty row
                if (empty($med)) {
                    continue;
                }

                $finalMeds[]      = $med;
                $finalTypes[]     = $types[$i] ?? "";
                $finalIntervals[] = $intervals[$i] ?? "";
                $finalDurations[] = $durations[$i] ?? "";
                $finalDates[]     = $dates[$i] ?? "";
            }

            $implodedMeds      = ! empty($finalMeds) ? implode(", ", $finalMeds) : null;
            $implodedMedTypes  = ! empty($finalTypes) ? implode(", ", $finalTypes) : null;
            $implodedIntervals = ! empty($finalIntervals) ? implode(", ", $finalIntervals) : null;
            $implodedDurations = ! empty($finalDurations) ? implode("||", $finalDurations) : null;
            $implodedDates     = ! empty($finalDates) ? implode("||", $finalDates) : null;
            // $durations         = array_filter((array) $request->med_duration, fn($d) => $d !== null && $d !== '');
            // $implodedMeds      = implode(", ", $meds);
            // $implodedMedTypes  = implode(", ", $medTypes);
            // $implodedIntervals = implode(", ", $intervals);
            // $implodedDurations = implode("||", $durations);
            // $durationsJson     = json_encode(array_values($durations));
            // dd($implodedDates);
            $barcodePayload = [
                'type'               => 'DISCHARGE',
                'discharge_no'       => $dischargeNo,
                'ipd_details_id'     => $validated['ipd_details_id'],
                'patient_name'       => $validated['patient_name'],
                'patient_id'         => $validated['patient_id'],
                'admission_no'       => $validated['admission_no'] ?? null,
                'discharge_contact'  => $validated['discharge_contact'] ?? null,
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
            // dd($validated);
            $discharge = DischargeCard::create([
                'hospital_id'        => Auth::user()->hospital_id ?? null,
                'branch_id'          => Auth::user()->branch_id ?? null,
                'ipd_details_id'     => $validated['ipd_details_id'],
                'discharge_number'   => $dischargeNo,
                'patient_name'       => $validated['patient_name'],
                'patient_id'         => $validated['patient_id'],
                'admission_no'       => $validated['admission_no'] ?? null,
                'discharge_contact'  => $validated['discharge_contact'] ?? null,
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
                'medicine_types'     => $implodedMedTypes ?? null,
                'intervals'          => $implodedIntervals ?? null,
                'durations'          => $implodedDurations ?? null,
                'med_dates'          => $implodedDates ?? null,

                'doctor_advice'      => $validated['doctor_advice'] ?? null,
                'discharged_by'      => $validated['discharged_by'] ?? null,
                'created_by'         => Auth::id(),
            ]);

            // -------------------------------
            // 🔹 Close bed history & release bed(s) (same pattern as IPD bed transfer)
            // -------------------------------
            $dischargeAt = $this->parseDischargeDateTimeForBedHistory(
                $validated['discharge_date'] ?? null,
                $validated['discharge_time'] ?? null
            );
            $this->releaseBedsAndCloseHistory((int) $validated['ipd_details_id'], $dischargeAt);

            // -------------------------------
            // 🔹 Mark IPD as Discharged
            // -------------------------------
            IpdDetail::where('id', $validated['ipd_details_id'])
                ->update([
                    'discharged'       => 'yes',
                    'discharged_date'  => $validated['discharge_date'],
                ]);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Patient discharged successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e);
            return back()
                ->with('error', 'Something went wrong while saving discharge details.')
                ->withInput();
        }

    }

    public function editDischarge(Request $request, $id)
    {
        $dischargeData               = DischargeCard::where('ipd_details_id', $id)->firstOrFail();
        $dischargeData->meds         = explode(',', $dischargeData->medicines ?? '');
        $dischargeData->med_types    = explode(',', $dischargeData->medicine_types ?? '');
        $dischargeData->med_interval = explode(',', $dischargeData->intervals ?? '');
        $dischargeData->med_dates    = explode('||', $dischargeData->med_dates ?? '');
        // $dischargeData->med_duration = explode("||", $dischargeData->durations ?? '');
        $dischargeData->ot_done_by = explode(",", $dischargeData->ot_done_by ?? '');
        $rawDurations              = $dischargeData->durations ?? '';

        if (str_contains($rawDurations, '||')) {
            // New format (correct one)
            $durations = explode('||', $rawDurations);
        } else {
            // Old format fallback
            // Split ONLY on comma followed by capital letter (new item indicator)
            $durations = preg_split('/,(?=[A-Z0-9])/', $rawDurations);
        }

        $dischargeData->med_duration = array_map(function ($dur) {
            return trim($dur) === '' ? '' : trim($dur);
        }, $durations);
        $medCount      = count($dischargeData->meds);
        $durationCount = count($dischargeData->med_duration);

        if (count($dischargeData->med_types) < $medCount) {
            $dischargeData->med_types = array_pad(
                $dischargeData->med_types,
                $medCount,
                ''
            );
        }
        if (count($dischargeData->med_interval) < $medCount) {
            $dischargeData->med_interval = array_pad(
                $dischargeData->med_interval,
                $medCount,
                ''
            );
        }
        if (count($dischargeData->med_dates) < $medCount) {
            $dischargeData->med_dates = array_pad(
                $dischargeData->med_dates,
                $medCount,
                ''
            );
        }
        if ($durationCount < $medCount) {
            $dischargeData->med_duration = array_pad(
                $dischargeData->med_duration,
                $medCount,
                ''
            );
        }
        $doctors = Doctor::all();
        // $dischargeData->med_duration = explode(',', $dischargeData->durations ?? '');

        return view("admin.ipd.edit-discharge", compact('dischargeData', 'doctors'));
    }

    public function updateDischarge(Request $request, $id)
    {
        // -------------------------------
        // 🔹 Validation Rules (Form-based)
        // -------------------------------
        // $request->merge([
        //     'meds'         => array_values(array_filter($request->meds ?? [])),
        //     'med_types'    => array_values(array_filter($request->med_types ?? [])),
        //     'med_interval' => array_values(array_filter($request->med_interval ?? [])),
        //     'med_duration' => array_values(array_filter($request->med_duration ?? [])),
        // ]);

        // dd($request->all());
        $validated = $request->validate([
            'ipd_details_id'     => ['required', 'integer', 'exists:ipd_details,id'],
            'patient_name'       => ['required', 'string', 'max:255'],
            'patient_id'         => ['nullable', 'integer'],
            'admission_no'       => ['nullable', 'string'],
            'discharge_contact'  => ['nullable', 'string'],
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
            'meds.*'             => ['nullable', 'string'],
            'med_types'          => ['nullable', 'array'],
            'med_types.*'        => ['nullable', 'string'],
            'med_interval'       => ['nullable', 'array'],
            'med_interval.*'     => ['nullable', 'string'],
            'med_duration'       => ['nullable', 'array'],
            'med_duration.*'     => ['nullable', 'string'],
            'med_date'           => ['nullable', 'array'],
            'med_date.*'         => ['nullable', 'date'],
            'doctor_advice'      => ['nullable', 'string'],
            'discharged_by'      => ['nullable', 'string'],
            'current_user'       => ['nullable', 'string'],
        ]);

        // dd($validated);
        DB::beginTransaction();

        try {
            // -------------------------------
            // 🔹 Update Discharge Card
            // -------------------------------
            // $meds      = array_filter((array) $request->meds, fn($med) => $med !== null && $med !== '');
            // $medTypes  = array_filter((array) $request->med_types, fn($type) => $type !== null && $type !== '');
            // $intervals = array_filter((array) $request->med_interval, fn($interval) => $interval !== null && $interval !== '');
            // $durations = array_filter((array) $request->med_duration, fn($duration) => $duration !== null && $duration !== '');
            // dd($durations);
            // $durations         = array_filter((array) $request->med_duration, fn($d) => $d !== null && $d !== '');

            $meds      = $request->meds ?? [];
            $types     = $request->med_types ?? [];
            $intervals = $request->med_interval ?? [];
            $durations = $request->med_duration ?? [];
            $medDates  = $request->med_date ?? [];

            $finalMeds      = [];
            $finalTypes     = [];
            $finalIntervals = [];
            $finalDurations = [];
            $finalDates     = [];

            foreach ($meds as $i => $med) {

                // skip completely empty row
                if (empty($med)) {
                    continue;
                }

                $finalMeds[]      = $med;
                $finalTypes[]     = $types[$i] ?? "";
                $finalIntervals[] = $intervals[$i] ?? "";
                $finalDurations[] = $durations[$i] ?? "";
                $finalDates[]     = $medDates[$i] ?? "";
            }

            $implodedMeds      = ! empty($finalMeds) ? implode(", ", $finalMeds) : null;
            $implodedMedTypes  = ! empty($finalTypes) ? implode(", ", $finalTypes) : null;
            $implodedIntervals = ! empty($finalIntervals) ? implode(", ", $finalIntervals) : null;
            $implodedDurations = ! empty($finalDurations) ? implode("||", $finalDurations) : null;
            $implodedDates     = ! empty($finalDates) ? implode("||", $finalDates) : null;
            // $durationsJson     = json_encode(array_values($durations));
            // dd($implodedMeds);
            $barcodePayload = [
                'type'               => 'DISCHARGE',
                'ipd_details_id'     => $validated['ipd_details_id'],
                'patient_name'       => $validated['patient_name'],
                'patient_id'         => $validated['patient_id'],
                'admission_no'       => $validated['admission_no'] ?? null,
                'discharge_contact'  => $validated['discharge_contact'] ?? null,
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
            // dd($validated);
            $discharge = DischargeCard::findOrFail($id);
            $oldDischargeAt = $this->parseDischargeDateTimeForBedHistory(
                $this->dischargeDateToString($discharge->discharge_date),
                $discharge->discharge_time
            );
            // dd($discharge);
            $discharge->update([
                'ipd_details_id'     => $validated['ipd_details_id'],
                'patient_name'       => $validated['patient_name'],
                'patient_id'         => $validated['patient_id'],
                'admission_no'       => $validated['admission_no'] ?? null,
                'discharge_contact'  => $validated['discharge_contact'] ?? null,
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
                'medicine_types'     => $implodedMedTypes ?? null,
                'intervals'          => $implodedIntervals ?? null,
                'durations'          => $implodedDurations ?? null,
                'med_dates'          => $implodedDates ?? null,
                'doctor_advice'      => $validated['doctor_advice'] ?? null,
            ]);

            $newDischargeAt = $this->parseDischargeDateTimeForBedHistory(
                $validated['discharge_date'],
                $validated['discharge_time'] ?? null
            );
            $this->syncBedHistoryToDateOnDischargeEdit(
                (int) $validated['ipd_details_id'],
                $oldDischargeAt,
                $newDischargeAt
            );

            IpdDetail::where('id', $validated['ipd_details_id'])
                ->update(['discharged_date' => $validated['discharge_date']]);

            DB::commit();

            return redirect()
                ->route('ipd')
                ->with('success', 'Discharge Updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e);
            return back()
                ->with('error', 'Something went wrong while saving discharge details.')
                ->withInput();
        }

    }

    /**
     * Normalize discharge_date from DB / model to Y-m-d for parsing.
     */
    private function dischargeDateToString($dischargeDate): ?string
    {
        if ($dischargeDate === null || $dischargeDate === '') {
            return null;
        }
        if ($dischargeDate instanceof \DateTimeInterface) {
            return $dischargeDate->format('Y-m-d');
        }

        return trim((string) $dischargeDate);
    }

    /**
     * Single datetime for bed history end / release, aligned with discharge card.
     */
    private function parseDischargeDateTimeForBedHistory(?string $dischargeDate, $dischargeTime): Carbon
    {
        $dischargeDate = $dischargeDate !== null ? trim($dischargeDate) : '';
        if ($dischargeDate === '') {
            return Carbon::now();
        }
        $timeStr = $dischargeTime !== null ? trim((string) $dischargeTime) : '';
        if ($timeStr === '') {
            return Carbon::parse($dischargeDate)->endOfDay();
        }
        try {
            return Carbon::parse($dischargeDate . ' ' . $timeStr);
        } catch (\Throwable $e) {
            return Carbon::parse($dischargeDate)->endOfDay();
        }
    }

    /**
     * End active patient_bed_history rows and mark beds available (Bed.is_active = yes).
     */
    private function releaseBedsAndCloseHistory(int $ipdDetailsId, Carbon $dischargeAt): void
    {
        $toDate = $dischargeAt->format('Y-m-d H:i:s');

        $bedIds = PatientBedHistory::where('ipd_id', $ipdDetailsId)
            ->where('is_active', 'yes')
            ->pluck('bed_id')
            ->filter()
            ->unique()
            ->values();

        PatientBedHistory::where('ipd_id', $ipdDetailsId)
            ->where('is_active', 'yes')
            ->update([
                'to_date'   => $toDate,
                'is_active' => 'no',
            ]);

        foreach ($bedIds as $bedId) {
            Bed::where('id', $bedId)->update(['is_active' => 'yes']);
        }
    }

    /**
     * When discharge date/time is edited, move matching closed history to_date to the new moment.
     */
    private function syncBedHistoryToDateOnDischargeEdit(int $ipdDetailsId, Carbon $oldDischargeAt, Carbon $newDischargeAt): void
    {
        if (abs($oldDischargeAt->diffInSeconds($newDischargeAt)) < 2) {
            return;
        }
        $from = $oldDischargeAt->copy()->subMinute()->format('Y-m-d H:i:s');
        $to   = $oldDischargeAt->copy()->addMinute()->format('Y-m-d H:i:s');

        PatientBedHistory::where('ipd_id', $ipdDetailsId)
            ->where('is_active', 'no')
            ->whereNotNull('to_date')
            ->whereBetween('to_date', [$from, $to])
            ->update(['to_date' => $newDischargeAt->format('Y-m-d H:i:s')]);
    }
}
