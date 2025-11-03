<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PharmacyCompany;
use Illuminate\Support\Facades\Auth;

class CompanyListController extends Controller
{
    function index (Request $request) {
    
        $companys = PharmacyCompany::query();
        $perPage = intval($request->input('perPage', 5));
        if ($perPage <= 0) {        
              $perPage = 5;
          } 

        if($request->has('search')){
            $search_term = $request->search;
            $companys->where(function ($query) use ($search_term) {
                $query->where('company_name', 'like', "%{$search_term}%");
            });
            $companys = $companys->paginate($perPage);
            return array("result"=>$companys);
        }
        $companys = $companys->paginate($perPage);
        //dd($company);

        return view('admin.setup.company_list', compact('companys'));
    }

    function store (Request $request) {
        $user = Auth::user();

        if (!$user || !$user->hospital_id) {
            return redirect()->back()->with('error', 'User not authenticated or hospital ID missing.');
        }
        //dd($request->all());
        $request->validate([
            'company_name' => 'required|array|min:1',
            'company_name.*' => 'required|string|max:255|unique:pharmacy_company,company_name',
        ],
        [
            'company_name.required' => 'Please provide at least one company name.',
            'company_name.*.required' => 'Each company name is required.',
            'company_name.*.unique' => 'The company name :input already exists.',
        ]);
        $company_names = $request->company_name;
        $arr = [];
        foreach ($company_names as $key => $company_name) {
            if (!isset($company_names[$key])) {
                return redirect()->back()->with('error', 'Missing name for company at index ' . $key);
            }

            $company = new PharmacyCompany();
            $company->hospital_id = $user->hospital_id;
            $company->branch_id = null;
            $company->company_name = $company_name;
            $company->save();
        }
    
        return redirect()->back()->with('success', 'Company added successfully');
    }

    function update (Request $request) {
        $request->validate([
            'id' => 'required|exists:pharmacy_company,id',
            'company_name' => 'required|string|max:255|unique:pharmacy_company,company_name,' . $request->id,
        ],
        [
            'company_name.required' => 'Company name is required.',
            'company_name.unique' => 'The company name :input already exists.',
        ]);

        $company = PharmacyCompany::findOrFail($request->id);
        $company->company_name = $request->company_name;
        $company->save();

        return redirect()->back()->with('success', 'Company updated successfully');
    }

    function destroy (Request $request) {
        $request->validate([
            'id' => 'required|exists:pharmacy_company,id',
        ]);

        $company = PharmacyCompany::findOrFail($request->id);
        $company->delete();

        return redirect()->back()->with('success', 'Company deleted successfully');
    }
}
