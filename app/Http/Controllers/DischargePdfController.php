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
        $showHeaderFooter = $request->query('hf', 1);

        $pdf = Pdf::loadView('admin.ipd.pdf.discharge-summary', [
            'data'             => $ipd,
            'showHeaderFooter' => (bool) $showHeaderFooter,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('Discharge_Summary.pdf', [
            'Attachment' => false,
        ]);
    }
}
