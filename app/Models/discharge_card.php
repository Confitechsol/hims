<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DischargeCard extends Model
{
    use HasFactory;

    protected $table = 'discharge_card';

    protected $fillable = [
        'hospital_id',
    ];

    
}
