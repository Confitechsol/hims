<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DutyRosterAssign extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'duty_roster_assign';

    /**
     * The attributes that are mass assignable.
     */

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'code',
        'roster_duty_date',
        'floor_id',
        'department_id',
        'staff_id',
        'doctor_id',
        'duty_roster_list_id',
    ];

    /**
     * Relationships
     */

    // Each roster assignment belongs to a floor
    public function floor()
    {
        return $this->belongsTo(Floor::class, 'floor_id');
    }

    // Each roster assignment belongs to a department
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    // Each roster assignment belongs to a staff member
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

        public function doctor()
        {
            return $this->belongsTo(Doctor::class, 'doctor_id');
        }

    // Each roster assignment belongs to a duty roster list
    public function dutyRosterList()
    {
        return $this->belongsTo(DutyRosterList::class, 'duty_roster_list_id');
    }
}
