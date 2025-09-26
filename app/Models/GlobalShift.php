<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalShift extends Model
{
    use HasFactory;

    protected $table = 'global_shift';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'name',
        'start_time',
        'end_time',
    ];
}
