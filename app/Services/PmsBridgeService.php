<?php

namespace App\Services;

use App\Models\IpdPrescription;
use App\Models\IpdPrescriptionTest;
use App\Models\Pathology;
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

            $testsPayload = collect($pathologyTestIds)->map(function ($testId) use ($pathologyMap) {
                $pathology = $pathologyMap->get((int) $testId);

                return [
                    'external_test_id' => (string) $testId,
                    'external_test_name' => $pathology->test_name ?? ('HIMS test '.$testId),
                    'external_test_short_name' => $pathology->short_name ?? null,
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
}

