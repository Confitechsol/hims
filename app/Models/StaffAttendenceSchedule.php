<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffAttendenceSchedule extends Model
{
    use HasFactory;

    protected $table = 'staff_attendence_schedules';

    protected $fillable = [
        'hospital_id',
         'branch_id',
        'staff_attendence_type_id',
        'role_id',
        'entry_time_from',
        'entry_time_to',
        'total_institute_hour',
        'is_active',
        'created_at',
    ];

    protected $casts = [
        'entry_time_from' => 'datetime:H:i',
        'entry_time_to' => 'datetime:H:i',
        'total_institute_hour' => 'datetime:H:i',
        'created_at' => 'datetime',
    ];

    /**
     * Relationship with staff attendance type
     */
    public function attendanceType()
    {
        return $this->belongsTo(StaffAttendenceType::class, 'staff_attendence_type_id');
    }

    /**
     * Relationship with roles
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
