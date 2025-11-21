<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VisitorsPurpose;
use App\Models\ComplaintType;
use App\Models\Source;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FrontOfficeController extends Controller
{
    public function purposes()
    {
        $purposes = VisitorsPurpose::all();
        return view('admin.setup.visitorspurpose', compact('purposes'));
    }
     public function storePurpose(Request $request)
    {
        // Validate that each purpose has name & description
        $validated = $request->validate([
            'purposes' => 'required|array|min:1',
            'purposes.*.name' => 'required|string|max:255',
            'purposes.*.description' => 'required|string|max:500',
        ]);

        foreach ($validated['purposes'] as $purposeData) {
            VisitorsPurpose::create([
                'visitors_purpose' => $purposeData['name'],
                'description' => $purposeData['description'],
                
            ]);
        }

        return redirect()->back()->with('success', 'Purposes created successfully!');
    }
    public function updatePurpose(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        $purpose = VisitorsPurpose::findOrFail($id);
        $purpose->update([
            'visitors_purpose' => $validated['name'],
            'description' => $validated['description'],
        ]);

        return redirect()->back()->with('success', 'Purpose updated successfully!');
    }
    public function destroyPurpose($id)
    {
        $purpose = VisitorsPurpose::findOrFail($id);
        $purpose->delete();

        return redirect()->back()->with('success', 'Purpose deleted successfully.');
    }
    public function complaintTypes()
    {
        $complaintTypes = ComplaintType::all();
        return view('admin.setup.complaintTypes', compact('complaintTypes'));
    }
    public function storeComplaint(Request $request)
    {
        // dd($request->all());
        // Validate that each purpose has name & description
       $validator = Validator::make($request->all(), [
            'complaint_types' => 'required|array|min:1',
            'complaint_types.*.name' => 'required|string|max:255',
            'complaint_types.*.description' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            dd($validator->errors()->toArray());
        }

        $validated = $validator->validated();
        // dd($validated); 

        foreach ($validated['complaint_types'] as $complaint_types) {
            ComplaintType::create([
                'complaint_type' => $complaint_types['name'],
                'description' => $complaint_types['description'],
                
            ]);
        }

        return redirect()->back()->with('success', 'Purposes created successfully!');
    }
    public function updateComplaint(Request $request, $id)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        // dd($validated);

        $complaint_type = ComplaintType::findOrFail($id);
        $complaint_type->update([
            'complaint_type' => $validated['name'],
            'description' => $validated['description'],
        ]);

        return redirect()->back()->with('success', 'Complaint types updated successfully!');
    }
    public function destroyComplaint($id)
    {
        $purpose = ComplaintType::findOrFail($id);
        $purpose->delete();

        return redirect()->back()->with('success', 'Purpose deleted successfully.');
    }
    public function sources()
    {
        $sources = Source::all();
        return view('admin.setup.sources', compact('sources'));
    }
    public function storeSources(Request $request)
    {
        // dd($request->all());
        // Validate that each purpose has name & description
       $validator = Validator::make($request->all(), [
            'sources' => 'required|array|min:1',
            'sources.*.name' => 'required|string|max:255',
            'sources.*.description' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            dd($validator->errors()->toArray());
        }

        $validated = $validator->validated();
        // dd($validated); 

        foreach ($validated['sources'] as $source) {
            Source::create([
                'source' => $source['name'],
                'description' => $source['description'],
                
            ]);
        }

        return redirect()->back()->with('success', 'Purposes created successfully!');
    }
    public function updateSources(Request $request, $id)
    {
        //dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        // dd($validated);

        $source = Source::findOrFail($id);
        $source->update([
            'source' => $validated['name'],
            'description' => $validated['description'],
        ]);

        return redirect()->back()->with('success', 'Complaint types updated successfully!');
    }
    public function destroySources($id)
    {
        $sources = Source::findOrFail($id);
        $sources->delete();

        return redirect()->back()->with('success', 'Sources deleted successfully.');
    }

}
