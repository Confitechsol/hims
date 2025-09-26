<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffLeaveRequest extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'staff_leave_request';

    // Fillable fields
    protected $fillable = [
        'hospital_id',
         'branch_id',
        'staff_id',
        'leave_type_id',
        'leave_from',
        'leave_to',
        'leave_days',
        'employee_remark',
        'admin_remark',
        'status',
        'approved_date',
        'applied_by',
        'status_updated_by',
        'document_file',
        'date',
    ];

    // Casts
    protected $casts = [
        'leave_from' => 'date',
        'leave_to' => 'date',
        'approved_date' => 'date',
        'date' => 'date',
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

    public function appliedBy()
    {
        return $this->belongsTo(User::class, 'applied_by');
    }

    public function statusUpdatedBy()
    {
        return $this->belongsTo(User::class, 'status_updated_by');
    }
}
