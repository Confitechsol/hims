<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Role;
use App\Models\Specialist;
use App\Models\Department;
use App\Models\StaffDesignation;
use App\Models\BloodBankProduct;


class StaffController extends Controller
{
     public function index()
    {
        $staffs = Staff::with('department')->get();
        return view('admin.staff.staffs', compact("staffs"));
    }
    public function create()
    {
        $roles = Role::all();
        $designations = StaffDesignation::all();
        $departments = Department::all();
        $specialists = Specialist::all();    
        $bloodgroups = BloodBankProduct::all(); 
        return view('admin.staff.addStaff', compact('roles', 'designations', 'departments', 'specialists','bloodgroups'));
    }
    public function getSpecialists($id)
{
    $specialists = Specialist::where('department_id', $id)->get();
    return response()->json($specialists);
}

public function store(Request $request)
{
    $request->validate([
        'employee_id' => 'required',
        'name' => 'required',
        'email' => 'required|email',
        'role' => 'required',
    ]);

    //dd($request->all());

    $staff = new Staff();
    $staff->employee_id = $request->employee_id;
    $staff->role_id = $request->role;
    $staff->staff_designation_id = $request->designation;
    $staff->department_id = $request->department;
    $staff->specialist = $request->specialist ?? null;
    $staff->name = $request->name;
    $staff->surname = $request->surname;
    $staff->father_name = $request->father_name;
    $staff->mother_name = $request->mother_name;
    $staff->gender = $request->gender;
    $staff->marital_status = $request->marital_status;
    $staff->blood_group = $request->blood_group;
    $staff->dob = $request->dob;
    $staff->date_of_joining = $request->date_of_joining;
    $staff->contact_no = $request->contactno;
    $staff->emergency_contact_no = $request->emgcontactno;
    $staff->email = $request->email;
    $staff->local_address = $request->address;
    $staff->permanent_address = $request->permanent_address;
    $staff->qualification = $request->qualification;
    $staff->work_exp = $request->work_exp;
    $staff->specialization = $request->specialization;
    $staff->note = $request->note;
    $staff->pan_number = $request->pan_number;
    $staff->identification_number = $request->identification_number;
    $staff->local_identification_number = $request->local_identification_number;
    $staff->password = '123456';
    $staff->user_id = '123456';
    $staff->is_active = '1';

    if ($request->file('file')) {
        $file = $request->file('file');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/staff'), $filename);
        $staff->photo = $filename;
    }

    $staff->save();

    return back()->with('success', 'Staff details added successfully!');
}

public function importStaff()
{
    $roles = Role::all();
        $designations = StaffDesignation::all();
        $departments = Department::all();
        $specialists = Specialist::all();    
        $bloodgroups = BloodBankProduct::all(); 
        return view('admin.staff.importStaff', compact('roles', 'designations', 'departments', 'specialists','bloodgroups'));
}
public function bulkImport(Request $request)
{
    if ($request->isMethod('get')) {
            return view('admin.setup.import_patient');
        }
        if ($request->isMethod('post')) {
            $request->validate([
                'csv_file'    => 'required|file|mimes:csv,txt',
            ]);

            // $bloodGroupText = $bloodGroups[$request->input('blood_group')];
            $file           = $request->file('csv_file');
            $path           = $file->getRealPath();

            $rows   = array_map('str_getcsv', file($path));
            $header = array_map('strtolower', array_map('trim', $rows[0]));

            if (count($rows) < 2) {
                return back()->with('error', 'CSV file must contain at least one data row.');
            }

            // Expected header fields for matching (optional validation)
            $expected = [
                'first name',
                'last name',
                'department',
                'designation',
                'specialization',
                'qualification',
                'work experience',
                'fathers name',
                'mothers name',
                'contact number',
                'emergency contact number',
                'email',
                'dob', 
                'marital status',               
                'date of joining',
                'date of leaving',
                'local address',
                'permanent address',
                'gender',
                'blood group',             
                'identification number',
                'pan',
                'remarks',
            ];
            //dd($header, $expected);
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
                Staff::create([
                    'name'                  => $row[0],
                    'surname'               => $row[1],
                    'department_id'         => $row[2],
                    'staff_designation_id'  => $row[3],
                    'specialist'            => $row[4],
                    'qualification'         => $row[5],
                    'work_exp'              => $row[6],
                    'father_name'           => $row[7],
                    'mother_name'           => $row[8],
                    'contact_no'            => $row[9],
                    'emergency_contact_no'  => $row[10],
                    'email'                 => $row[11],
                    'dob'                   => $row[12],
                    'marital_status'        => $row[13],                    
                    'date_of_joining'       => $row[14],
                    'date_of_leaving'       => $row[15],
                    'local_address'         => $row[16],
                    'permanent_address'     => $row[17],
                    'gender'                => $row[18],
                    'blood_group'           => $row[19],
                    'identification_number' => $row[20],
                    'pan'                   => $row[21],
                    'remarks'               => $row[22],
                    
                ]);
                // }
                return back()->with('success', 'Patients imported successfully.');
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to import patients: ' . $e->getMessage());
            }
        }
}

}
