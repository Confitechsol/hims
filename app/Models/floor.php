<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;

    protected $table = 'floor';

    protected $fillable = [
        'hospital_id',
        'name',
        'description',
    ];

    /**
     * Relationships
     */
    public function dutyRosterAssignments()
    {
        return $this->hasMany(DutyRosterAssign::class, 'floor_id');
    }
}
