<?php
namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\Finding;
use App\Models\FindingCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FindingsController extends Controller
{
   public function index(Request $request)
{
    $perPage = (int) $request->input('perPage', 10);
    if ($perPage <= 0) {
        $perPage = 10;
    }

    $search = $request->input('search');

    $query = Finding::with('category');

    // 🔍 Search
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhereHas('category', function ($cat) use ($search) {
                  $cat->where('category', 'like', "%{$search}%");
              });
        });
    }

    // Pagination
    $findings = $query->orderBy('id','desc')
                      ->paginate($perPage)
                      ->appends($request->all());

    $findingCategories = FindingCategory::all();

    return view("admin.setup.finding", compact("findings", 'findingCategories','perPage'));
}
    public function store(Request $request)
    {
        $request->validate([
            'finding'     => 'required|string|max:255',
            'category'    => 'required|integer',
            'description' => 'required|string',
        ]);
        $user = Auth::user();
        // dd($user);
        if (! $user || ! $user->hospital_id) {
            return redirect()->back()->with('error', 'User not authenticated or hospital ID missing.');
        }
        Finding::create([
            'hospital_id'         => $user->hospital_id,
            'name'                => $request->input('finding'),
            'finding_category_id' => $request->input('category'),
            'description'         => $request->input('description'),
        ]);
        return redirect()->back()->with('success', 'Finding successfully Added!');
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'finding_id'  => 'required|exists:finding,id',
            'finding'     => 'required|string|max:200',
            'category'    => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        // dd($validated);
        $finding = Finding::findOrFail($request->finding_id);
        if (! $finding) {
            return redirect()->back()->with('error', 'No Finding Found with this Given ID!');
        }
        $finding->update([
            'name'                => $request->input('finding'),
            'finding_category_id' => $request->input('category'),
            'description'         => $request->input('description'),
        ]);
        // $radiologyUnit->save();

        return redirect()->back()->with('success', 'Finding Successfully Updated.');
    }

    public function delete(Request $request, $id)
    {
        if (Auth::user()->role == '1') {
            $finding = Finding::findOrFail($id);
            $finding->delete();
        } else {
            return redirect()->back()->with('error', 'Unauthorized!');
        }
        return redirect()->back()->with('success', 'Finding Successfully Deleted');
    }

    // finding category
    public function indexCategory(Request $request)
    {
        $perPage = (int) $request->input('perPage', 10);
    if ($perPage <= 0) {
        $perPage = 10;
    }

    $query = FindingCategory::query();

    // 🔍 Search
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where('category', 'like', "%{$search}%");
    }

    // Pagination
    $findingCategories = $query->orderBy('id','desc')
                               ->paginate($perPage)
                               ->appends($request->all());

    return view("admin.setup.finding_category", compact('findingCategories','perPage'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'finding_category'   => 'required|array',
            'finding_category.*' => 'required|string|max:255', // validate each element
        ]);

        $user = Auth::user();

        if (! $user || ! $user->hospital_id) {
            return redirect()->back()->with('error', 'User not authenticated or hospital ID missing.');
        }

        // Loop through each category and insert
        foreach ($request->finding_category as $categoryName) {
            // Skip empty rows (if user adds a row but doesn’t fill it)
            if (! empty($categoryName)) {
                FindingCategory::create([
                    'hospital_id' => $user->hospital_id,
                    'category'    => $categoryName,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Finding Categories successfully added!');
    }

    public function updateCategory(Request $request)
    {
        $request->validate([
            'finding_category' => 'required|string|max:255',
            'category_id'      => 'required|exists:finding_category,id',
        ]);

        $findingCategory = FindingCategory::findOrFail($request->category_id);
        $findingCategory->update([
            'category' => $request->finding_category,
        ]);
        // $radiologyCategory->save();

        return redirect()->back()->with('success', 'Finding Category Successfully Updated.');
    }

    public function deleteCategory(Request $request, $id)
    {
        // dd(Auth::user());
        if (Auth::user()->role == '1') {
            $radiologyCategory = FindingCategory::findOrFail($id);
            $radiologyCategory->delete();
        } else {
            return redirect()->back()->with('error', 'Unauthorized!');
        }
        return redirect()->back()->with('success', 'Finding Category Successfully Deleted');
    }

    public function getFindingCategories(Request $request)
    {
        // dd($id);
        $categories = FindingCategory::all();
        // dd($bedNumbers);
        return response()->json($categories, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }
    public function getFindings(Request $request)
    {
        $categoryIds = $request->input('category_ids', []);
        $findings    = Finding::whereIn('finding_category_id', $categoryIds)->get();
        // dd($bedNumbers);
        return response()->json($findings, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }
}