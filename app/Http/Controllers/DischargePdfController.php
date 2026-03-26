<?php
namespace App\Http\Controllers;

use App\Models\DischargeCard;
use App\Models\IpdDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class DischargePdfController extends Controller
{
    private function encodeImage($content)
    {
        if ($content) {
            // Detect MIME type
            $finfo    = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_buffer($finfo, $content);
            finfo_close($finfo);
            return 'data:' . $mimeType . ';base64,' . base64_encode($content);
        }
        return null;
    }
    public function generate($id, Request $request)
    {
        $ipd        = DischargeCard::where('ipd_details_id', $id)->firstOrFail();
        $idpDetails = IpdDetail::with('doctor')->findOrFail($id);
        if ($ipd->barcode) {
            $ipd->barcode = $this->encodeImage($ipd->barcode);

        }
        $medCombinations = [];
        $meds            = array_map(function ($med) {
            return trim($med) === '' ? '' : trim($med);
        }, explode(',', $ipd->medicines ?? ''));

        $medTypes = array_map(function ($types) {
            return trim($types) === '' ? '' : trim($types);
        }, explode(',', $ipd->medicine_types ?? ''));

        $intervals = array_map(function ($inv) {
            return trim($inv) === '' ? '' : trim($inv);
        }, explode(',', $ipd->intervals ?? ''));

        $medDates = array_map(function ($date) {
            return trim($date) === '' ? '' : trim($date);
        }, explode('||', $ipd->med_dates ?? ''));
        $rawDurations = $ipd->durations ?? '';

        $rawDurations = $ipd->durations ?? '';

        if (str_contains($rawDurations, '||')) {
            // New format (correct one)
            $durations = explode('||', $rawDurations);
        } else {
            // Old format fallback
            // Split ONLY on comma followed by capital letter (new item indicator)
            $durations = preg_split('/,(?=[A-Z0-9])/', $rawDurations);
        }

        $durations = array_map(function ($dur) {
            return trim($dur) === '' ? '' : trim($dur);
        }, $durations);
        // $durations = array_filter(
        //     explode(',', $ipd->durations),
        //     fn($dur) => $dur !== null && trim($dur) !== ''
        // );
        // $count = min(count($meds), count($medTypes), count($intervals), count($durations));
        $count = count($meds);
        if (count($medTypes) < count($meds)) {
            $medTypes = array_pad(
                $medTypes,
                count($meds),
                ''
            );
        }
        if (count($intervals) < count($meds)) {
            $intervals = array_pad(
                $intervals,
                count($meds),
                ''
            );
        }
        if (count($durations) < count($meds)) {
            $durations = array_pad(
                $durations,
                count($meds),
                ''
            );
        }
        if (count($medDates) < count($meds)) {
            $medDates = array_pad(
                $medDates,
                count($meds),
                ''
            );
        }

        for ($i = 0; $i < $count; $i++) {
            $medText = "{$meds[$i]} ({$medTypes[$i]})";
            if ($intervals[$i] != '') {
                $medText .= " {$intervals[$i]}";
            }

// Add duration with "x" ONLY if both interval & duration exist
            if ($durations[$i] != '') {
                if ($intervals[$i] != '') {
                    $medText .= " x {$durations[$i]}";
                } else {
                    $medText .= " x {$durations[$i]}";
                }
            }
            if ($medDates[$i] != '') {
                $medText .= " - {$medDates[$i]}";
            }
            $medCombinations[] = $medText;
        }

        $medListHtml = '<ol style="padding-left:2.5rem;margin:0;">';

        foreach ($medCombinations as $med) {
            $medListHtml .= '<li>' . e($med) . '</li>';
        }

        $medListHtml .= '</ol>';
        if (empty($medCombinations)) {
            $medListHtml = null;
        }
        $ipd->discharge_medicines_html = $medListHtml;
        $showHeaderFooter              = $request->query('hf', 1);

        if ($idpDetails->doctor->signature) {
            $path = public_path('uploads/Doctor/signatures/' . $idpDetails->doctor->signature);

            if (file_exists($path)) {
                $ipd->signature_base64 = '<img class="d-signature" src="data:image/png;base64,' . base64_encode(file_get_contents($path)) . '">';
            } else {
                $ipd->signature_base64 = '<p class="fw-bold mb-2" style="font-size: small;">' . e($ipd->under_care_dr) . '</p>';
            }
        } else {
            $ipd->signature_base64 = '<p class="fw-bold mb-2" style="font-size: small;">' . e($ipd->under_care_dr) . '</p>';
        }
        // dd($ipd);
        $pdf = Pdf::loadView('admin.ipd.pdf.discharge-summary', [
            'data'             => $ipd,
            'showHeaderFooter' => (bool) $showHeaderFooter,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('Discharge_Summary.pdf', [
            'Attachment' => false,
        ]);
    }
}
