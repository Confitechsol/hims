<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodBankProduct extends Model
{
    use HasFactory;

    // Explicit table name
    protected $table = 'blood_bank_products';

    // No updated_at column
    public $timestamps = false;

    protected $fillable = [
        'hospital_id',
        'name',
        'is_blood_group',
        'created_at',
    ];

    protected $casts = [
        'is_blood_group' => 'integer',
        'created_at' => 'datetime',
    ];
}
