<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Charge;
use App\Models\DoctorVisit;
use Illuminate\Support\Facades\DB;

class DoctorVisitController extends Controller
{
    /**
     * Show create form
     */
    public function create()
    {
        $patients = Patient::all();
        $doctors  = Doctor::all();
        $charges = Charge::where('charge_category_id', 1)->get();

        return view('admin.doctor-visit.create', compact('patients','doctors','charges'));
    }

    /**
     * Store doctor visit data
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctor,id',
            'visit_type' => 'required|exists:charges,id',
            'date' => 'required|date',
            'rate' => 'required|numeric|min:0',
            'no_of_visit' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0',
            'doctor_pay_amount' => 'nullable|numeric|min:0',
            'visit_date' => 'required|date',
            'visit_time' => 'required',
        ]);

        try {
            DB::beginTransaction();

            // Check if editing
            if ($request->edit_id) {
                // Use withTrashed() to include soft deleted records, or find normally
                $visit = DoctorVisit::withTrashed()->find($request->edit_id);
                
                if (!$visit) {
                    throw new \Exception('Visit record not found.');
                }
                
                // If it was soft deleted, restore it first
                if ($visit->trashed()) {
                    $visit->restore();
                }
                
                $visit->update([
                    'patient_id' => $request->patient_id,
                    'doctor_id' => $request->doctor_id,
                    'charge_id' => $request->visit_type,
                    'reporting_date' => $request->date,
                    'rate' => $request->rate,
                    'no_of_visit' => $request->no_of_visit,
                    'amount' => $request->amount,
                    'doctor_pay_amount' => $request->doctor_pay_amount ?? 0.00,
                    'visit_date' => $request->visit_date,
                    'visit_time' => $request->visit_time,
                ]);
                $message = 'Doctor visit record updated successfully!';
            } else {
                DoctorVisit::create([
                    'patient_id' => $request->patient_id,
                    'doctor_id' => $request->doctor_id,
                    'charge_id' => $request->visit_type,
                    'reporting_date' => $request->date,
                    'rate' => $request->rate,
                    'no_of_visit' => $request->no_of_visit,
                    'amount' => $request->amount,
                    'doctor_pay_amount' => $request->doctor_pay_amount ?? 0.00,
                    'visit_date' => $request->visit_date,
                    'visit_time' => $request->visit_time,
                ]);
                $message = 'Doctor visit record created successfully!';
            }

            DB::commit();

            return redirect()->route('doctor-visit.create')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('doctor-visit.create')
                ->with('error', 'Error saving doctor visit record: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Get patient visits for AJAX request
     */
    public function getPatientVisits($patientId)
    {
        try {
            $visits = DoctorVisit::with(['doctor', 'charge'])
                ->where('patient_id', $patientId)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($visits);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get single visit for editing
     */
    public function getVisit($id)
    {
        try {
            // Use withTrashed() to allow editing even if soft deleted
            $visit = DoctorVisit::withTrashed()
                ->with(['doctor', 'charge', 'patient'])
                ->find($id);

            if (!$visit) {
                return response()->json(['error' => 'Visit record not found.'], 404);
            }

            return response()->json($visit);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update doctor visit data
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctor,id',
            'visit_type' => 'required|exists:charges,id',
            'date' => 'required|date',
            'rate' => 'required|numeric|min:0',
            'no_of_visit' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0',
            'doctor_pay_amount' => 'nullable|numeric|min:0',
            'visit_date' => 'required|date',
            'visit_time' => 'required',
        ]);

        try {
            DB::beginTransaction();

            // Use withTrashed() to include soft deleted records
            $visit = DoctorVisit::withTrashed()->find($id);
            
            if (!$visit) {
                throw new \Exception('Visit record not found.');
            }
            
            // If it was soft deleted, restore it first
            if ($visit->trashed()) {
                $visit->restore();
            }
            
            $visit->update([
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->doctor_id,
                'charge_id' => $request->visit_type,
                'reporting_date' => $request->date,
                'rate' => $request->rate,
                'no_of_visit' => $request->no_of_visit,
                'amount' => $request->amount,
                'doctor_pay_amount' => $request->doctor_pay_amount ?? 0.00,
                'visit_date' => $request->visit_date,
                'visit_time' => $request->visit_time,
            ]);

            DB::commit();

            return redirect()->route('doctor-visit.create')
                ->with('success', 'Doctor visit record updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('doctor-visit.create')
                ->with('error', 'Error updating doctor visit record: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete doctor visit (Soft Delete)
     */
    public function destroy($id)
    {
        try {
            $visit = DoctorVisit::findOrFail($id);
            $visit->delete(); // This will perform a soft delete

            return response()->json(['success' => true, 'message' => 'Doctor visit deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
