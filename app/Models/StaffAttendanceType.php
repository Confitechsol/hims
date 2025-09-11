<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffAttendanceType extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'staff_attendance_type';

    // Fillable columns
    protected $fillable = [
        'hospital_id',
        'type',
        'key_value',
        'is_active',
        'long_lang_name',
        'long_name_style',
        'for_schedule',
    ];

    // Casts for proper data handling
    protected $casts = [
        'for_schedule' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'date',
    ];

    // Relationships
    public function attendances()
    {
        return $this->hasMany(StaffAttendance::class, 'staff_attendance_type_id');
    }

    // Timestamps enabled (since both created_at & updated_at exist)
    public $timestamps = true;
}
