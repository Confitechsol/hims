<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoseDuration extends Model
{
    use HasFactory;

    protected $table = 'dose_duration';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'name',
    ];
}
