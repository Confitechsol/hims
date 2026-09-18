<?php

namespace App\Services;

use App\Models\IpdPrescription;
use App\Models\IpdPrescriptionTest;
use App\Models\Pathology;
use App\Models\PathologyParameterDetail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PmsBridgeService
{
    /**
     * Send IPD pathology prescription tests to PMS as an order.
     *
     * This uses the mapping and payload shape described in hims-pms-integration-plan.md.
     * It ONLY sends pathology tests (not radiology/pharmacy).
     *
     * @param IpdPrescription $prescription
     * @return array ['success' => bool, 'message' => string, 'data' => mixed|null]
     */
    public function sendIpdPathologyOrder(IpdPrescription $prescription): array
    {
        try {
            $ipd = $prescription->ipd()->with('patient', 'bedGroup', 'bedDetail', 'doctor')->first();
            if (!$ipd || !$ipd->patient) {
                return [
                    'success' => false,
                    'message' => 'IPD or patient not found for prescription '.$prescription->id,
                    'data' => null,
                ];
            }

            // Collect pathology test IDs from normalized tests table
            $pathologyTestIds = IpdPrescriptionTest::where('ipd_prescription_id', $prescription->id)
                ->whereNotNull('pathology_id')
                ->pluck('pathology_id')
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values()
                ->all();

            if (empty($pathologyTestIds)) {
                return [
                    'success' => false,
                    'message' => 'No pathology tests to send for prescription '.$prescription->id,
                    'data' => null,
                ];
            }

            // Include names as well so PMS does not need to rely only on ID mapping.
            $pathologyMap = Pathology::whereIn('id', $pathologyTestIds)
                ->get(['id', 'test_name', 'short_name'])
                ->keyBy('id');

            // Fetch parameter metadata (reference range + unit) for each pathology test.
            $parameterDetailsByPathology = PathologyParameterDetail::with(['parameter.unitRelation'])
                ->whereIn('pathology_id', $pathologyTestIds)
                ->get()
                ->groupBy('pathology_id');

            $testsPayload = collect($pathologyTestIds)->map(function ($testId) use ($pathologyMap, $parameterDetailsByPathology) {
                $pathology = $pathologyMap->get((int) $testId);
                $parameterRows = collect();
                if (isset($parameterDetailsByPathology[(int) $testId])) {
                    $parameterRows = collect($parameterDetailsByPathology[(int) $testId])->map(function ($detail) {
                        $parameter = $detail->parameter;
                        return [
                            'parameter_name' => $parameter->parameter_name ?? null,
                            'reference_interval' => $parameter->reference_range ?? null,
                            'unit' => $parameter->unitRelation->unit_name ?? null,
                        ];
                    })->filter(function ($row) {
                        return !empty($row['parameter_name']) || !empty($row['reference_interval']) || !empty($row['unit']);
                    })->values();
                }

                $referenceIntervals = $parameterRows->pluck('reference_interval')->filter()->unique()->values()->all();
                $units = $parameterRows->pluck('unit')->filter()->unique()->values()->all();

                return [
                    'external_test_id' => (string) $testId,
                    'external_test_name' => $pathology->test_name ?? ('HIMS test '.$testId),
                    'external_test_short_name' => $pathology->short_name ?? null,
                    // Test-level convenience fields for PMS UI.
                    'reference_interval' => !empty($referenceIntervals) ? implode(', ', $referenceIntervals) : null,
                    'unit' => !empty($units) ? implode(', ', $units) : null,
                    // Parameter-wise metadata for structured entry in PMS.
                    'parameters' => $parameterRows->all(),
                ];
            })->values()->all();

            $patient = $ipd->patient;
            $doctor = $ipd->doctor;

            $payload = [
                'external_system' => 'HIMS',
                'external_patient_id' => (string) ($patient->id ?? ''),
                'patient' => [
                    'name' => $patient->patient_name ?? '',
                    'gender' => strtolower($patient->gender ?? ''),
                    'dob' => $patient->dob ?? null,
                    'mobile' => $patient->mobileno ?? null,
                    'address' => $patient->address ?? null,
                ],
                'encounter' => [
                    'ipd_admission_id' => $ipd->ipd_no ?? (string) $ipd->id,
                    'ward' => $ipd->bedGroup->name ?? null,
                    'bed' => $ipd->bedDetail->name ?? null,
                ],
                'doctor' => [
                    'external_doctor_id' => $doctor ? (string) $doctor->id : null,
                    'name' => $doctor ? trim(($doctor->name ?? '').' '.($doctor->surname ?? '')) : null,
                ],
                'tests' => $testsPayload,
            ];

            $baseUrl = rtrim(config('services.pms.base_url', env('PMS_BASE_URL')), '/');
            $token = config('services.pms.token', env('PMS_BRIDGE_TOKEN'));

            if (!$baseUrl) {
                return [
                    'success' => false,
                    'message' => 'PMS_BASE_URL is not configured',
                    'data' => null,
                ];
            }

            $url = $baseUrl.'/api/bridge/hims/ipd-orders';

            $response = Http::withToken($token)
                ->acceptJson()
                ->post($url, $payload);

            Log::info('PMS bridge request sent', [
                'url' => $url,
                'prescription_id' => $prescription->id ?? null,
                'tests' => $testsPayload,
                'status' => $response->status(),
            ]);

            if (!$response->successful()) {
                Log::error('PMS bridge error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'tests_payload' => $testsPayload,
                ]);

                return [
                    'success' => false,
                    'message' => 'PMS returned HTTP '.$response->status(),
                    'data' => $response->json(),
                ];
            }

            $data = $response->json();

            // Optionally: persist PMS order number on prescription for later correlation
            if (is_array($data) && isset($data['pms_order_no'])) {
                $prescription->pms_order_no = $data['pms_order_no'];
                $prescription->save();
            }

            return [
                'success' => true,
                'message' => 'PMS order created',
                'data' => $data,
            ];
        } catch (\Throwable $e) {
            Log::error('Error sending IPD pathology order to PMS', [
                'prescription_id' => $prescription->id ?? null,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Push a single doctor profile to PMS (report signatory cache).
     *
     * @param \App\Models\Doctor $doctor
     * @return array{success: bool, message: string, data: mixed}
     */
    public function pushDoctor(\App\Models\Doctor $doctor): array
    {
        try {
            $baseUrl = rtrim((string) config('services.pms.base_url', env('PMS_BASE_URL')), '/');
            $token = (string) config('services.pms.token', env('PMS_BRIDGE_TOKEN'));

            if ($baseUrl === '') {
                return ['success' => false, 'message' => 'PMS_BASE_URL is not configured', 'data' => null];
            }

            $degree = trim((string) ($doctor->qualification ?? ''));
            if ($degree === '') {
                $degree = trim((string) ($doctor->specialization ?? ''));
            }

            $designation = '';
            if (! empty($doctor->staff_designation_id)) {
                $sd = \App\Models\StaffDesignation::find($doctor->staff_designation_id);
                $designation = $sd ? trim((string) $sd->designation) : '';
            }
            if ($designation === '' && is_string($doctor->designation ?? null)) {
                $designation = trim((string) $doctor->designation);
            }

            $signatureFile = trim((string) ($doctor->signature ?? ''));
            $signatureUrl = $signatureFile !== ''
                ? url('uploads/Doctor/signatures/'.$signatureFile)
                : null;

            $payload = [
                'external_id' => (string) $doctor->id,
                'doctor_code' => $doctor->doctor_id ?? null,
                'name' => trim(($doctor->name ?? '').' '.($doctor->surname ?? '')),
                'degree' => $degree !== '' ? $degree : null,
                'qualification' => $degree !== '' ? $degree : null,
                'designation' => $designation !== '' ? $designation : null,
                'registration_no' => $doctor->registration_no ?? null,
                'signature_filename' => $signatureFile !== '' ? $signatureFile : null,
                'signature_url' => $signatureUrl,
                'is_active' => (bool) ($doctor->is_active ?? true),
            ];

            $url = $baseUrl.'/api/bridge/hims/doctors';
            $response = Http::withToken($token)->acceptJson()->timeout(30)->post($url, $payload);

            if (! $response->successful()) {
                Log::warning('PMS doctor push failed', [
                    'doctor_id' => $doctor->id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'success' => false,
                    'message' => 'PMS returned HTTP '.$response->status(),
                    'data' => $response->json(),
                ];
            }

            return ['success' => true, 'message' => 'Doctor pushed to PMS', 'data' => $response->json()];
        } catch (\Throwable $e) {
            Log::error('PMS doctor push exception', [
                'doctor_id' => $doctor->id ?? null,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'message' => $e->getMessage(), 'data' => null];
        }
    }
}

