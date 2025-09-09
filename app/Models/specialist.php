<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialist extends Model
{
    use HasFactory;

    // Table name (optional if it matches plural form)
    protected $table = 'specialist';

    // Fillable fields
    protected $fillable = [
        'hospital_id',
        'specialist_name',
        'is_active',
    ];
}
