<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SymptomController extends Controller
{
    public function symptomHead()
    {
        $symptoms = Symptom::all();
        return view('admin.setup.symptoms', compact('symptoms'));
    }

    public function storeSymptomHead(Request $request)
    {
        $request->validate([
            'symptom.*' => 'required|string|max:255',
        ]);

        foreach ($request->symptom as $symptomName) {
            Symptom::insert([
                'name'       => $symptomName,
                'created_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Symptoms saved successfully!');
    }

    public function updateSymptomHead(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $symptom = Symptom::findOrFail($id);
        $symptom->update([
            'name' => $validated['name'],
        ]);

        return redirect()->back()->with('success', 'Symptom updated successfully!');
    }

    public function destroySymptomHead($id)
    {
        $symptom = Symptom::findOrFail($id);
        $symptom->delete();

        return redirect()->back()->with('success', 'Symptom deleted successfully.');
    }
}
