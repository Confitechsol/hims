<?php
namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguagesController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all Languages as key => value (e.g. ['ipd_no' => 'IPD001'])
        $query = Language::query();
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('language', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('short_code', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('country_code', 'LIKE', "%{$searchTerm}%");
            });
        }
        $languages = $query->get();
        return view('admin.setup.languages', compact('languages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'language'     => 'required|string|max:50',
            'short_code'   => 'required|string|max:255',
            'country_code' => 'required|string|max:255',
        ]);
        $language = Language::create([
            'language'     => $request->input('language'),
            'short_code'   => $request->input('short_code'),
            'country_code' => $request->input('country_code'),
            'is_deleted'   => 'no',
            'is_rtl'       => 'no',
            'is_active'    => 'no',
        ]);
        return redirect()->back()->with('success', 'Language Added successfully!');
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'is_active' => 'required|string|max:255',
        ]);

        // Find the requested language
        $language = Language::findOrFail($id);
        // If making this language active
        if ($request->input('is_active') === 'yes') {
            // Deactivate any currently active language
            Language::where('is_active', 'yes')->update(['is_active' => 'no']);
        } else {
            Language::where('short_code', 'en')->update(['is_active' => 'yes']);
        }
        // Update the selected language's status
        $language->is_active = $request->input('is_active');
        $language->save();

        return response()->json($language, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }
    public function updateRTL(Request $request, $id)
    {
        $request->validate([
            'is_rtl' => 'required|string|max:255',
        ]);

        // Find the requested language
        $language = Language::findOrFail($id);

        // Update the selected language's status
        $language->is_rtl = $request->input('is_rtl');
        $language->save();

        return response()->json($language, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
        // return redirect()->route('languages')->with('success', 'Language updated successfully!');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $languages = Language::where('language', 'LIKE', "%{$query}%")
            ->orWhere('short_code', 'LIKE', "%{$query}%")
            ->orWhere('country_code', 'LIKE', "%{$query}%")
            ->get();

        return response()->json($languages);
    }

}