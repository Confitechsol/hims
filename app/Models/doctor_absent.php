<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorAbsent extends Model
{
    use HasFactory;

    protected $table = 'doctor_absent';

    protected $fillable = [
        'hospital_id',
    ];

    
}
