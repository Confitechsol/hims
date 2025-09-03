<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorGlobalShift extends Model
{
    use HasFactory;

    protected $table = 'doctor_global_shift';

    protected $fillable = [
        'hospital_id',
    ];

    
}
