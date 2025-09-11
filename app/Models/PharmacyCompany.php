<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyCompany extends Model
{
    use HasFactory;

    protected $table = 'pharmacy_company';

    public $timestamps = false; // only created_at exists

    protected $fillable = [
        'hospital_id',
        'company_name',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
