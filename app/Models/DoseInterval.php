<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoseInterval extends Model
{
    use HasFactory;

    protected $table = 'dose_interval';

    protected $fillable = [
        'hospital_id',
    ];

    
}
