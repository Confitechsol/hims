<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChargeTypeMaster extends Model
{
    use HasFactory;

    protected $table = 'charge_type_master';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'charge_type',
        'is_default',
        'is_active',
    ];

    public $timestamps = false; // only created_at exists

    protected $casts = [
        'created_at' => 'datetime',
    ];

    
}
