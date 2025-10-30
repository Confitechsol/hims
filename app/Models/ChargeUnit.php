<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChargeUnit extends Model
{
    use HasFactory;

    protected $table = 'charge_units';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'unit',
        'is_active',
    ];

    public $timestamps = false; // only created_at exists

    protected $casts = [
        'is_active' => 'integer',
        'created_at' => 'datetime',
    ];
}
