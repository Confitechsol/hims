<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DoctorExportPatientCountController extends Controller
{
    //
   
    // public function getDoctorsPatientCount()
    // {
    //     $doctors = Doctor::select('name')->get();
    //     return response()->json($doctors);
    // }
    
 //   public function getDoctors()
// {
//     $doctors = Doctor::select('id', 'name')->get();

//     $ipdCounts = DB::table('ipd_details')
//         ->select(
//             'cons_doctor',
//             DB::raw('MONTH(date) as month'),
//             DB::raw('COUNT(id) as total')
//         )
//         ->whereNotNull('cons_doctor')
//         ->groupBy('cons_doctor', DB::raw('MONTH(date)'))
//         ->get();

//     // ✅ convert for blade
//     $doctorData = [];

//     foreach ($ipdCounts as $row) {
//         $doctorData[$row->cons_doctor][$row->month] = $row->total;
//     }

//     return view('admin.reports.doctor.doctor_reports', compact('doctors', 'doctorData'));
// }

//  public function getDoctors(Request $request)
// {

//     $doctors = Doctor::select('id', 'name')->get();

//     return view('admin.reports.doctor.doctor_reports', compact('doctors'));
// }
  

  public function getDoctors(Request $request)
{
    $year = $request->input('year', date('Y')); // default: current year

    $doctors = Doctor::select('id', 'name')->get();

    $ipdCounts = DB::table('ipd_details')
        ->select(
            'cons_doctor',
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(id) as total')
        )
        ->whereNotNull('cons_doctor')
        ->whereYear('created_at', $year) // ✅ filter by created_at year
        ->groupBy('cons_doctor', DB::raw('MONTH(created_at)'), DB::raw('YEAR(created_at)'))
        ->get();

    $doctorData = [];

    foreach ($ipdCounts as $row) {
        $doctorData[$row->cons_doctor][$row->month] = $row->total;
    }


    return view('admin.reports.doctor.doctor_reports', compact('doctors', 'doctorData', 'year'));
}

    

}
