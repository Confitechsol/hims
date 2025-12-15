<?php
namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\LetterheadCategory;
use App\Models\LetterheadSettings;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LetterHeadController extends Controller
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

    private function processImage($content)
    {
        return file_get_contents($content->getRealPath());
    }
    public function index(Request $request)
    {
        $letterheadCategory = LetterheadCategory::all();
        $letterheadSettings = LetterheadSettings::all()->keyBy('letterhead_cat_id');
        foreach ($letterheadSettings as $letterHead) {
            if ($letterHead->print_header) {
                $letterHead->print_header = $this->encodeImage($letterHead->print_header);
            }
        }
        return view("admin.setup.letter_head_foot", compact("letterheadCategory", 'letterheadSettings'));
    }

    public function store(Request $request, $categoryId)
    {
        $request->validate([
            'letterhead_cat_id' => 'required|integer',
            'print_header'      => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // 5MB
            'print_footer'      => 'nullable|string',
        ]);
        $letterhead = LetterheadSettings::find($categoryId);
        if ($request->hasFile('print_header')) {
            $headerImage = $this->processImage($request->file('print_header'));
        } else {
            // Keep the existing image from DB
            $headerImage = $letterhead->print_header;
        }
        // $headerImage = $request->hasFile('print_header') ? $this->processImage($request->file('print_header')) : null;
        // dd($request->input('print_footer'));
        // Insert or update record
        $setting = LetterheadSettings::updateOrCreate(
            ['letterhead_cat_id' => $categoryId],
            [
                'print_header' => $headerImage,
                'print_footer' => $request->input('print_footer'),
                'setting_for'  => 'letterhead',
                'is_active'    => "yes",
            ]
        );

        return redirect()->back()->with('success', 'Letterhead settings saved successfully!');
    }

    public function storeLetterheadCategory(Request $request)
    {
        try {
            $request->validate([
                'category_name' => 'required|string',
            ]);
            LetterheadCategory::create([
                'hospital_id' => Auth::user()->hospital_id ?? null,
                'name'        => $request->category_name,
            ]);
            return redirect()->back()->with('success', 'Letterhead Category saved successfully!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to save Letterhead Category: ' . $e->getMessage());
        }

    }
}
