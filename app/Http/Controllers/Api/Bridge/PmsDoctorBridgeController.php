<?php

namespace App\Http\Controllers\Api\Bridge;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\StaffDesignation;
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
}
