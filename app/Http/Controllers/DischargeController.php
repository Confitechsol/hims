<?php
namespace App\Http\Controllers;

use App\Models\DischargeCard;
use App\Models\Doctor;
use App\Models\IpdDetail;
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
            'discharge_contact'  => ['required', 'string'],
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
            'med_types'          => ['nullable', 'array'],
            'med_types.*'        => ['string'],
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
            $meds        = array_filter((array) $request->meds, fn($med) => $med !== null && $med !== '');
            $medTypes    = array_filter((array) $request->med_types, fn($type) => $type !== null && $type !== '');
            $intervals   = array_filter((array) $request->med_interval, fn($interval) => $interval !== null && $interval !== '');
            $durations   = array_filter((array) $request->med_duration, fn($duration) => $duration !== null && $duration !== '');

            // $durations         = array_filter((array) $request->med_duration, fn($d) => $d !== null && $d !== '');
            $implodedMeds      = implode(", ", $meds);
            $implodedMedTypes  = implode(", ", $medTypes);
            $implodedIntervals = implode(", ", $intervals);
            $implodedDurations = implode("||", $durations);
            // $durationsJson     = json_encode(array_values($durations));

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

        $dischargeData->med_duration = array_filter(
            array_map('trim', $durations),
            fn($dur) => $dur !== ''
        );
        $medCount      = count($dischargeData->meds);
        $durationCount = count($dischargeData->med_duration);

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
        // dd($request->all());
        $validated = $request->validate([
            'ipd_details_id'     => ['required', 'integer', 'exists:ipd_details,id'],
            'patient_name'       => ['required', 'string', 'max:255'],
            'patient_id'         => ['nullable', 'integer'],
            'admission_no'       => ['nullable', 'string'],
            'discharge_contact'  => ['required', 'string'],
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
            'med_types'          => ['nullable', 'array'],
            'med_types.*'        => ['string'],
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
            // 🔹 Update Discharge Card
            // -------------------------------
            $meds      = array_filter((array) $request->meds, fn($med) => $med !== null && $med !== '');
            $medTypes  = array_filter((array) $request->med_types, fn($type) => $type !== null && $type !== '');
            $intervals = array_filter((array) $request->med_interval, fn($interval) => $interval !== null && $interval !== '');
            $durations = array_filter((array) $request->med_duration, fn($duration) => $duration !== null && $duration !== '');

            // $durations         = array_filter((array) $request->med_duration, fn($d) => $d !== null && $d !== '');
            $implodedMeds      = implode(", ", $meds);
            $implodedMedTypes  = implode(", ", $medTypes);
            $implodedIntervals = implode(", ", $intervals);
            $implodedDurations = implode("||", $durations);
            // $durationsJson     = json_encode(array_values($durations));

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
            ]);

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
}
