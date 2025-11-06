<?php

namespace App\Http\Controllers;

use App\Models\PharmacyCompany;
use Illuminate\Http\Request;

class PharmacyCompanyController extends Controller
{
    /**
     * Display a listing of pharmacy companies
     */
    public function index()
    {
        $companies = PharmacyCompany::orderBy('company_name', 'asc')->paginate(20);
        return view('admin.setup.pharmacy_company', compact('companies'));
    }

    /**
     * Store a newly created company
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255|unique:pharmacy_company,company_name',
        ]);

        try {
            PharmacyCompany::create($validated);

            return redirect()->back()->with('success', 'Company added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to add company: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified company
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:pharmacy_company,id',
            'company_name' => 'required|string|max:255|unique:pharmacy_company,company_name,' . $request->id,
        ]);

        try {
            $company = PharmacyCompany::findOrFail($validated['id']);
            $company->update(['company_name' => $validated['company_name']]);

            return redirect()->back()->with('success', 'Company updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update company: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified company
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:pharmacy_company,id',
        ]);

        try {
            $company = PharmacyCompany::findOrFail($validated['id']);
            
            // Check if company has medicines
            if ($company->medicines()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete company with associated medicines.');
            }

            $company->delete();

            return redirect()->back()->with('success', 'Company deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete company: ' . $e->getMessage());
        }
    }
}

