<?php



namespace App\Http\Controllers;



use App\Models\InsuranceCompany;

use App\Models\InsuranceRatePanel;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;



class InsuranceCompanyController extends Controller

{

    public function index(Request $request)

    {

        $perPage = max(1, (int) $request->input('perPage', 10));

        $query = InsuranceCompany::with('ratePanels')->withCount('tpas');



        if ($request->has('search')) {

            $search = $request->input('search');

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")

                    ->orWhere('code', 'like', "%{$search}%")

                    ->orWhere('contact_no', 'like', "%{$search}%")

                    ->orWhere('contact_person_name', 'like', "%{$search}%");

            });



            return response()->json([

                'status' => 200,

                'result' => $query->with('ratePanels')->withCount('tpas')->get()->map(fn ($item) => [

                    'id' => $item->id,

                    'name' => $item->name,

                    'code' => $item->code,

                    'contact_no' => $item->contact_no,

                    'address' => $item->address,

                    'contact_person_name' => $item->contact_person_name,

                    'contact_person_phone' => $item->contact_person_phone,

                    'tpas_count' => $item->tpas_count,

                    'rate_panel_ids' => $item->ratePanels->pluck('id')->join(','),

                    'rate_panels_label' => $item->ratePanels->pluck('name')->join(', ') ?: '—',

                ]),

                'total' => $query->count(),

            ]);

        }



        $insuranceCompanies = $query->orderByDesc('id')->paginate($perPage);

        $ratePanels = InsuranceRatePanel::where('is_active', true)->orderBy('name')->get();



        return view('admin.insurance.insurance_management', compact('insuranceCompanies', 'perPage', 'ratePanels'));

    }



    public function store(Request $request)

    {

        $request->validate([

            'name' => 'required|string|max:200',

            'code' => 'required|string|max:50|unique:insurance_companies,code',

            'contact_no' => 'nullable|string|max:20',

            'address' => 'nullable|string|max:300',

            'contact_person_name' => 'nullable|string|max:200',

            'contact_person_phone' => 'nullable|string|max:20',

            'rate_panel_ids' => 'nullable|array',

            'rate_panel_ids.*' => 'exists:insurance_rate_panels,id',

        ]);



        $user = Auth::user();



        $insurance = InsuranceCompany::create([

            'hospital_id' => $user->hospital_id ?? null,

            'branch_id' => $user->branch_id ?? null,

            'name' => $request->name,

            'code' => $request->code,

            'contact_no' => $request->contact_no,

            'address' => $request->address,

            'contact_person_name' => $request->contact_person_name,

            'contact_person_phone' => $request->contact_person_phone,

        ]);



        $insurance->ratePanels()->sync($request->input('rate_panel_ids', []));



        return redirect()->route('insurance.management')->with('success', 'Insurance company added successfully.');

    }



    public function update(Request $request)

    {

        $request->validate([

            'id' => 'required|exists:insurance_companies,id',

            'name' => 'required|string|max:200',

            'code' => 'required|string|max:50|unique:insurance_companies,code,' . $request->id,

            'contact_no' => 'nullable|string|max:20',

            'address' => 'nullable|string|max:300',

            'contact_person_name' => 'nullable|string|max:200',

            'contact_person_phone' => 'nullable|string|max:20',

            'rate_panel_ids' => 'nullable|array',

            'rate_panel_ids.*' => 'exists:insurance_rate_panels,id',

        ]);



        $insurance = InsuranceCompany::findOrFail($request->id);

        $insurance->update($request->only([

            'name',

            'code',

            'contact_no',

            'address',

            'contact_person_name',

            'contact_person_phone',

        ]));



        $insurance->ratePanels()->sync($request->input('rate_panel_ids', []));



        return redirect()->route('insurance.management')->with('success', 'Insurance company updated successfully.');

    }



    public function destroy(Request $request)

    {

        $request->validate([

            'id' => 'required|exists:insurance_companies,id',

        ]);



        InsuranceCompany::findOrFail($request->id)->delete();



        return redirect()->route('insurance.management')->with('success', 'Insurance company deleted successfully.');

    }

}

