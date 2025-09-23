<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Symptom;
use App\Models\SymptomsClassification;

class SymptomController extends Controller
{
    public function symptomHead()
    {
         $symptoms = Symptom::with('classification')->get();
         $classifications = SymptomsClassification::all();
        return view('admin.setup.symptoms_head', compact('symptoms','classifications'));
    }

    public function storeSymptomHead(Request $request)
    {
            $request->validate([
            'symptoms_title' => 'required|string|max:255',
            'description'    => 'nullable|string|max:500',
            'type'           => 'nullable|string|max:255',
        ]);

        
            Symptom::insert([
                'symptoms_title' => $request->symptoms_title,
                'description'    => $request->description,
                'type'           => $request->type,
            ]);
        

        return redirect()->back()->with('success', 'Symptoms saved successfully!');
    }

    public function updateSymptomHead(Request $request, $id)
    {
        $validated = $request->validate([
            'symptoms_title' => 'required|string|max:255',
            'description'    => 'nullable|string|max:500',
            'type'           => 'nullable|string|max:255',
        ]);

        $symptom = Symptom::findOrFail($id);
        $symptom->update([
            'symptoms_title' => $validated['symptoms_title'],
            'description'    => $validated['description'],
            'type'           => $validated['type'],
        ]);

        return redirect()->back()->with('success', 'Symptom updated successfully!');
    }

    public function destroySymptomHead($id)
    {
        $symptom = Symptom::findOrFail($id);
        $symptom->delete();

        return redirect()->back()->with('success', 'Symptom deleted successfully.');
    }

     public function symptomType()
    {
        $symptomTypes = SymptomsClassification::all();
        return view('admin.setup.symptoms_type', compact('symptomTypes'));
    }

    // Store multiple symptom types
    public function storeSymptomType(Request $request)
    {
        $request->validate([
            'symptoms_type.*' => 'required|string|max:255',
        ]);

        foreach ($request->symptoms_type as $type) {
            SymptomsClassification::create([
                'symptoms_type' => $type,
            ]);
        }

        return redirect()->back()->with('success', 'Symptoms Types added successfully!');
    }

    // Update a symptom type
    public function updateSymptomType(Request $request, $id)
    {
        $request->validate([
            'symptoms_type' => 'required|string|max:255',
        ]);

        $symptomType = SymptomsClassification::findOrFail($id);
        $symptomType->update([
            'symptoms_type' => $request->symptoms_type,
        ]);

        return redirect()->back()->with('success', 'Symptoms Type updated successfully!');
    }

    // Delete a symptom type
    public function destroySymptomType($id)
    {
        $symptomType = SymptomsClassification::findOrFail($id);
        $symptomType->delete();

        return redirect()->back()->with('success', 'Symptoms Type deleted successfully!');
    }
}
