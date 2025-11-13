<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Organisation;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::get();
        return view('admin.setup.patient', compact("patients"));
    }
    public function store(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'guardian_name'      => 'nullable|string|max:255',
            'gender'             => 'required|in:Male,Female',
            'birth_date'         => 'nullable|date',
            'age.year'           => 'nullable|integer|min:0',
            'age.month'          => 'nullable|integer|min:0|max:11',
            'age.day'            => 'nullable|integer|min:0|max:31',
            'blood_group'        => 'nullable|in:1,2,3,4,5,6',
            'marital_status'     => 'nullable|in:Single,Married,Widowed,Separated,Not Specified',
            'file'               => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'phone'              => 'nullable|string|max:20',
            'email'              => 'nullable|email|max:255',
            'address'            => 'nullable|string|max:500',
            'remarks'            => 'nullable|string|max:500',
            'allergies'          => 'nullable|string|max:255',
            'tpa'                => 'nullable|in:1,2,3,4,5',
            'tpa_id'             => 'nullable|string|max:100',
            'tpa_validity'       => 'nullable|string|max:100',
            'national_id_number' => 'nullable|string|max:50',
        ]);

        // Handle file upload
        $photoPath = null;
        if ($request->hasFile('file')) {
            $photoPath = $request->file('file')->store('patient_photos', 'public');
        }

        // Save the patient
        $patient = Patient::create([
            'patient_name'          => $validated['name'],
            'guardian_name'         => $validated['guardian_name'] ?? null,
            'gender'                => $validated['gender'],
            'dob'                   => $validated['birth_date'] ?? null,
            'age'                   => $validated['age']['year'] ?? null,
            'month'                 => $validated['age']['month'] ?? null,
            'day'                   => $validated['age']['day'] ?? null,
            'blood_group'           => $validated['blood_group'] ?? null,
            'marital_status'        => $validated['marital_status'] ?? null,
            'image'                 => $photoPath,
            'mobileno'              => $validated['phone'] ?? null,
            'email'                 => $validated['email'] ?? null,
            'address'               => $validated['address'] ?? null,
            'note'                  => $validated['remarks'] ?? null,
            'known_allergies'       => $validated['allergies'] ?? null,
            'organisation_id'       => $validated['tpa'] ?? null,
            'tpa_code'              => $validated['tpa_id'] ?? null,
            'tpa_validity'          => $validated['tpa_validity'] ?? null,
            'identification_number' => $validated['national_id_number'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Patient saved successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('selected_patients');

        if (! $ids || count($ids) === 0) {
            return redirect()->back()->with('error', 'No patients selected.');
        }

        Patient::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', 'Selected patients deleted successfully.');
    }

    public function import(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('admin.setup.import_patient');
        }
        if ($request->isMethod('post')) {
            $request->validate([
                'csv_file'    => 'required|file|mimes:csv,txt',
                'blood_group' => 'required|in:1,2,3,4,5,6',
            ]);
            $bloodGroups = [
                '1' => 'O+',
                '2' => 'A+',
                '3' => 'B+',
                '4' => 'AB+',
                '5' => 'O-',
                '6' => 'AB-',
            ];
            $bloodGroupText = $bloodGroups[$request->input('blood_group')];
            $file           = $request->file('csv_file');
            $path           = $file->getRealPath();

            $rows   = array_map('str_getcsv', file($path));
            $header = array_map('strtolower', array_map('trim', $rows[0]));

            if (count($rows) < 2) {
                return back()->with('error', 'CSV file must contain at least one data row.');
            }

            // Expected header fields for matching (optional validation)
            $expected = [
                'patient',
                'gender',
                'age(year)',
                'age(month)',
                'age(day)',
                'marital status',
                'phone',
                'email',
                'address',
                'remarks',
                'known allergies',
                'identification number',
                'tpa id',
                'tpa validity',
            ];
            if ($header !== $expected) {
                return back()->with('error', 'CSV header does not match expected format.');
            }

                                                // unset($rows[0]); // remove header row
            $row = array_map('trim', $rows[1]); // Only one row expected
            try {
                // foreach ($rows as $row) {
                // Trim all fields
                $row = array_map('trim', $row);

                // Insert into DB
                Patient::create([
                    'patient_name'          => $row[0],
                    'gender'                => $row[1],
                    'age'                   => $row[2],
                    'month'                 => $row[3],
                    'day'                   => $row[4],
                    'marital_status'        => $row[5],
                    'mobileno'              => $row[6],
                    'email'                 => $row[7],
                    'address'               => $row[8],
                    'note'                  => $row[9],
                    'known_allergies'       => $row[10],
                    'identification_number' => $row[11],
                    'insurance_id'          => $row[12],
                    'insurance_validity'    => \Carbon\Carbon::parse($row[13])->format('Y-m-d'),
                    'blood_group'           => $bloodGroupText,
                ]);
                // }
                return back()->with('success', 'Patients imported successfully.');
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to import patients: ' . $e->getMessage());
            }
        }
    }

    public function organizations()
    {
        $organizations = Organisation::all();
        return response()->json($organizations, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }
    public function getPatients()
    {
        $patients = Patient::with('organisation', 'bloodGroup')->get();
        // dd($patients);
        return response()->json($patients, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }

}