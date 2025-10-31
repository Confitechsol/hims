<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoseInterval extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'name',
    ];
}
