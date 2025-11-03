<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoseDuration extends Model
{
    use HasFactory;

    // Table name (explicit since it doesn’t follow plural convention)
    protected $table = 'dose_duration';
    public $timestamps = false;
    // Primary key
    protected $primaryKey = 'id';

    // Mass assignable attributes
    protected $fillable = [
        'hospital_id',
        'branch_id',
        'name',
    ];
}
