<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DutyRosterShift extends Model
{
    use HasFactory;

    protected $table = 'duty_roster_shift';

    protected $fillable = [
        'hospital_id',
        'shift_name',
        'shift_start',
        'shift_end',
        'shift_hour',
        'is_active',
    ];

    public function dutyRosterLists()
    {
        return $this->hasMany(DutyRosterList::class, 'duty_roster_shift_id');
    }
}
