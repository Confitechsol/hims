<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorShiftTime extends Model
{
    use HasFactory;

    // Table name (since it doesn’t follow plural convention)
    protected $table = 'doctor_shift_time';
    public $timestamps  = false;

    // Primary key
    protected $primaryKey = 'id';

    // Mass assignable attributes
    protected $fillable = [
        'hospital_id',
        'day',
        'staff_id',
        'doctor_id',
        'doctor_global_shift_id',
        'consultation_duration',
        'charge_category_id',
        'start_time',
        'end_time',
        'branch_id', // as said by Shreya Didi
    ];

    // Relationships
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function doctorGlobalShift()
    {
        return $this->belongsTo(DoctorGlobalShift::class, 'doctor_global_shift_id');
    }
     public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }
}
