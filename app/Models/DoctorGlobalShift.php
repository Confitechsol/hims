<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorGlobalShift extends Model
{
    use HasFactory;

    // Table name (since it doesn’t follow plural convention)
    protected $table = 'doctor_global_shift';

    // Primary key
    protected $primaryKey = 'id';

    // Mass assignable attributes
    protected $fillable = [
        'hospital_id',
        'staff_id',
        'global_shift_id',
        'branch_id', // as said by Shreya Didi
    ];

    // Relationships (if you want to use them later)
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function globalShift()
    {
        return $this->belongsTo(GlobalShift::class, 'global_shift_id');
    }
}
