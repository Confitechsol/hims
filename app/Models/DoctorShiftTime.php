<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorShiftTime extends Model
{
    use HasFactory;

    protected $table = 'doctor_shift_time';

    protected $fillable = [
        'hospital_id',
    ];

    
}
