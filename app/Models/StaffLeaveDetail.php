<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffLeaveDetail extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'staff_leave_details';

    // Fillable fields
    protected $fillable = [
        'hospital_id',
        'staff_id',
        'leave_type_id',
        'alloted_leave',
    ];

    // Casts
    protected $casts = [
        'created_at' => 'datetime',
    ];

    // No updated_at column in migration
    public $timestamps = false;

    /**
     * Relationships
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }
}
