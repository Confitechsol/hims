<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorAbsent extends Model
{
    use HasFactory;

    // Table name (explicit since it doesn’t follow plural convention)
    protected $table = 'doctor_absent';

    // Primary key
    protected $primaryKey = 'id';

    // Mass assignable attributes
    protected $fillable = [
        'hospital_id',
        'branch_id',
        'staff_id',
        'date',
    ];

    // Casts
    protected $casts = [
        'date' => 'date',
    ];

    // Relationships
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
