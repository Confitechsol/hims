<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffAttendance extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'staff_attendance';

    // Fillable columns
    protected $fillable = [
        'hospital_id',
         'branch_id',
        'date',
        'staff_id',
        'staff_attendance_type_id',
        'biometric_attendence',
        'biometric_device_data',
        'user_agent',
        'remark',
        'is_active',
        'in_time',
        'out_time',
    ];

    // Casts for automatic type conversion
    protected $casts = [
        'date' => 'date',
        'in_time' => 'datetime:H:i',
        'out_time' => 'datetime:H:i',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function attendanceType()
    {
        return $this->belongsTo(StaffAttendanceType::class, 'staff_attendance_type_id');
    }

    // Enable timestamps (since migration has created_at & updated_at)
    public $timestamps = true;
}
