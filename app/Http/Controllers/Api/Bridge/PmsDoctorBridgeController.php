<?php

namespace App\Http\Controllers\Api\Bridge;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\IpdDetail;
use App\Models\IpdPatient;
use App\Models\IpdPrescription;
use App\Models\StaffDesignation;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Machine API for PMS to pull consultant / pathologist profiles for report footers.
 */
class PmsDoctorBridgeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Doctor::query();

        if (Schema::hasColumn('doctor', 'deleted_at')) {
            // Soft-deleted rows if column exists
            $query->whereNull('deleted_at');
        }

        if (Schema::hasColumn('doctor', 'is_active')) {
            // Include inactive unless explicitly requested
            if (! $request->boolean('include_inactive')) {
                $query->where(function ($q) {
                    $q->where('is_active', 1)->orWhereNull('is_active');
                });
            }
        }

        $hasStaffDesignationId = Schema::hasColumn('doctor', 'staff_designation_id');
        $hasDesignationId = Schema::hasColumn('doctor', 'designation_id');

        $designationMap = [];
        if (Schema::hasTable('staff_designation')) {
            $designationMap = StaffDesignation::query()
                ->get(['id', 'designation'])
                ->keyBy('id')
                ->map(fn ($row) => trim((string) $row->designation))
                ->all();
        }

        $doctors = $query
            ->orderBy('name')
            ->get();

        $data = $doctors->map(function (Doctor $doctor) use ($designationMap, $hasStaffDesignationId, $hasDesignationId) {
            $fullName = trim(($doctor->name ?? '').' '.($doctor->surname ?? ''));
            $degree = trim((string) ($doctor->qualification ?? ''));
            if ($degree === '') {
                $degree = trim((string) ($doctor->specialization ?? ''));
            }

            $designation = '';
            if ($hasStaffDesignationId && ! empty($doctor->staff_designation_id)) {
                $designation = $designationMap[(int) $doctor->staff_designation_id] ?? '';
            } elseif ($hasDesignationId && ! empty($doctor->designation_id)) {
                $designation = $designationMap[(int) $doctor->designation_id] ?? '';
            }
            if ($designation === '' && is_string($doctor->designation ?? null)) {
                $designation = trim((string) $doctor->designation);
            }

            $signatureFile = trim((string) ($doctor->signature ?? ''));
            $signatureUrl = null;
            $signatureExists = false;
            if ($signatureFile !== '') {
                $absolute = public_path('uploads/Doctor/signatures/'.$signatureFile);
                $signatureExists = is_file($absolute);
                $signatureUrl = url('uploads/Doctor/signatures/'.$signatureFile);
            }

            return [
                'external_id' => (string) $doctor->id,
                'doctor_code' => $doctor->doctor_id ?? null,
                'name' => $fullName !== '' ? $fullName : ('Doctor #'.$doctor->id),
                'degree' => $degree !== '' ? $degree : null,
                'qualification' => $degree !== '' ? $degree : null,
                'designation' => $designation !== '' ? $designation : null,
                'registration_no' => $doctor->registration_no ?? null,
                'signature_filename' => $signatureFile !== '' ? $signatureFile : null,
                'signature_url' => $signatureUrl,
                'signature_exists' => $signatureExists,
                'is_active' => (bool) ($doctor->is_active ?? true),
            ];
        })->values();

        return response()->json([
            'data' => $data,
            'count' => $data->count(),
        ]);
    }

    /**
     * Return one doctor (used by PMS for refresh of a selected slot).
     */
    public function show(string $id): JsonResponse
    {
        $request = request()->merge(['include_inactive' => true]);
        $all = $this->index($request)->getData(true);
        $rows = $all['data'] ?? [];
        foreach ($rows as $row) {
            if ((string) ($row['external_id'] ?? '') === (string) $id) {
                return response()->json(['data' => $row]);
            }
        }

        return response()->json(['message' => 'Doctor not found'], 404);
    }

    /**
     * Referring doctor for a PMS report that came from an IPD admission.
     * Prefers the admission consultant, then the doctor who wrote the prescription.
     */
    public function referredDoctor(Request $request): JsonResponse
    {
        $encounter = trim((string) $request->query('encounter', ''));
        $patientId = trim((string) $request->query('patient_id', ''));
        $orderDate = trim((string) $request->query('order_date', ''));

        $ipd = $this->findIpdForReferral($encounter, $patientId, $orderDate);
        $doctor = $ipd ? $this->referringDoctor($ipd) : null;
        $name = $doctor ? trim(($doctor->name ?? '').' '.($doctor->surname ?? '')) : '';

        if ($name === '') {
            return response()->json(['data' => null]);
        }

        return response()->json([
            'data' => [
                'external_doctor_id' => (string) $doctor->id,
                'name' => $name,
            ],
        ]);
    }

    private function findIpdForReferral(string $encounter, string $patientId, string $orderDate): ?IpdDetail
    {
        if ($encounter !== '') {
            $ipd = IpdDetail::with('doctor')
                ->where(function ($query) use ($encounter) {
                    $query->where('ipd_no', $encounter);
                    if (ctype_digit($encounter)) {
                        $query->orWhere('id', (int) $encounter);
                    }
                })
                ->orderByDesc('id')
                ->first();

            if ($ipd) {
                return $ipd;
            }
        }

        if ($patientId === '' || ! ctype_digit($patientId)) {
            return null;
        }

        $base = IpdDetail::with('doctor')->where('patient_id', (int) $patientId);

        if ($orderDate !== '') {
            try {
                $day = Carbon::parse($orderDate)->toDateString();
                $matched = (clone $base)
                    ->whereDate('date', '<=', $day)
                    ->orderByDesc('date')
                    ->orderByDesc('id')
                    ->first();
                if ($matched) {
                    return $matched;
                }
            } catch (\Throwable $e) {
                // Ignore a bad date and use the latest admission.
            }
        }

        return $base->orderByDesc('id')->first();
    }

    private function referringDoctor(IpdDetail $ipd): ?Doctor
    {
        if ($ipd->doctor) {
            return $ipd->doctor;
        }

        $prescription = IpdPrescription::query()
            ->with('prescribedBy')
            ->where('ipd_id', $ipd->id)
            ->whereNotNull('prescribed_by')
            ->orderByDesc('id')
            ->first();

        if ($prescription?->prescribedBy) {
            return $prescription->prescribedBy;
        }

        $link = IpdPatient::query()
            ->with('doctor')
            ->where('ipd_id', $ipd->id)
            ->whereNotNull('doctor_id')
            ->orderByDesc('id')
            ->first();

        return $link?->doctor;
    }
}
