<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralCall extends Model
{
    use HasFactory;

    protected $table = 'general_calls';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'name',
        'contact',
        'date',
        'description',
        'follow_up_date',
        'call_duration',
        'note',
        'call_type',
    ];
}
