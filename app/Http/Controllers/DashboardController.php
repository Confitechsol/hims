<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Doctor;

class DashboardController extends Controller
{
   public function index() {
        $doctorsCount = \App\Models\Doctor::count();
        $patientsCount = \App\Models\Patient::count();
        // $revenuesCount = \App\Models\Revenue::sum('amount');
        $period = request()->input('period', 'monthly');
        $now = Carbon::now();
        if ($period === 'weekly') {
            $dateFilter = [
                ['created_at', '>=', $now->startOfWeek()],
                ['created_at', '<=', $now->endOfWeek()],
            ];
        } elseif ($period === 'yearly') {
            $dateFilter = [
                ['created_at', '>=', $now->copy()->startOfYear()],
                ['created_at', '<=', $now->copy()->endOfYear()],
            ];
        } else { // monthly (default)
            $dateFilter = [
                ['created_at', '>=', $now->copy()->startOfMonth()],
                ['created_at', '<=', $now->copy()->endOfMonth()],
            ];
        }
    $appointmentsCount = \App\Models\Appointment::where($dateFilter)->count();
    $cancelledAppointmentsCount = \App\Models\Appointment::where('appointment_status', 'cancelled')
    ->where($dateFilter)
    ->count();

    $rescheduledAppointmentsCount = \App\Models\Appointment::where('appointment_status', 'rescheduled')
    ->where($dateFilter)
    ->count();

    $completedAppointmentsCount = \App\Models\Appointment::where('appointment_status', 'completed')
    ->where($dateFilter)
    ->count();
    $doctors = Doctor::with(['department:id,department_name'])
    ->withCount('appointments')
    ->orderByDesc('appointments_count')->limit(3)
    ->get();
    // return $doctors;
        return view('admin.dashboard',compact('doctorsCount','patientsCount','appointmentsCount','rescheduledAppointmentsCount','cancelledAppointmentsCount','completedAppointmentsCount','doctors'));
    }

}
