<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\MedicineCompany;
use Illuminate\Http\Request;

class MedicineCompanyController extends Controller
{
    public function index()
    {
        $companies = MedicineCompany::orderBy('id', 'desc')->get();
        return view('admin.setup.medicine_company', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255'
        ]);

        MedicineCompany::create([
            'hospital_id' => session('hospital_id', '1'),
            'branch_id' => session('branch_id', '1'),
            'company_name' => $request->company_name
        ]);

        return redirect()->back()->with('success', 'Company added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'company_name' => 'required|string|max:255'
        ]);

        $company = MedicineCompany::findOrFail($id);
        $company->update([
            'company_name' => $request->company_name
        ]);

        return redirect()->back()->with('success', 'Company updated successfully!');
    }

    public function destroy($id)
    {
        $company = MedicineCompany::findOrFail($id);
        $company->delete();

        return redirect()->back()->with('success', 'Company deleted successfully!');
    }
}
