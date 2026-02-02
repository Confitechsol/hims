<?php
namespace App\Http\Controllers;

use App\Models\DischargeCard;
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
        $ipd = DischargeCard::where('ipd_details_id', $id)->firstOrFail();
        if ($ipd->barcode) {
            $ipd->barcode = $this->encodeImage($ipd->barcode);

        }
        $medCombinations = [];
        $meds            = array_filter(
            explode(',', $ipd->medicines),
            fn($med) => $med !== null && trim($med) !== ''
        );
        $intervals = array_filter(
            explode(',', $ipd->intervals),
            fn($inv) => $inv !== null && trim($inv) !== ''
        );
        $durations = array_filter(
            explode(',', $ipd->durations),
            fn($dur) => $dur !== null && trim($dur) !== ''
        );
        $count = min(count($meds), count($intervals), count($durations));

        for ($i = 0; $i < $count; $i++) {
            $medCombinations[] = "{$meds[$i]} {$intervals[$i]} x {$durations[$i]}";
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
        $pdf                           = Pdf::loadView('admin.ipd.pdf.discharge-summary', [
            'data'             => $ipd,
            'showHeaderFooter' => (bool) $showHeaderFooter,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('Discharge_Summary.pdf', [
            'Attachment' => false,
        ]);
    }
}
