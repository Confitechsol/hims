<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DutyRosterList extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'duty_roster_list';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'hospital_id',
        'branch_id',
        'duty_roster_shift_id',
        'duty_roster_start_date',
        'duty_roster_end_date',
        'duty_roster_total_day',
    ];

    /**
     * Relationships
     */

    // Each duty roster list belongs to a shift
    public function dutyRosterShift()
    {
        return $this->belongsTo(DutyRosterShift::class, 'duty_roster_shift_id');
    }

    // A duty roster list can have many assignments
    public function dutyRosterAssigns()
    {
        return $this->hasMany(DutyRosterAssign::class, 'duty_roster_list_id');
    }
}
