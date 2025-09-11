<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientIdCard extends Model
{
    use HasFactory;

    protected $table = 'patient_id_card';

    public $timestamps = false; // only created_at exists

    protected $fillable = [
        'hospital_id',
        'title',
        'hospital_name',
        'hospital_address',
        'background',
        'logo',
        'sign_image',
        'header_color',
        'enable_patient_name',
        'enable_guardian_name',
        'enable_patient_unique_id',
        'enable_address',
        'enable_phone',
        'enable_dob',
        'enable_blood_group',
        'status',
        'enable_barcode',
        'created_at',
    ];

    protected $casts = [
        'enable_patient_name' => 'boolean',
        'enable_guardian_name' => 'boolean',
        'enable_patient_unique_id' => 'boolean',
        'enable_address' => 'boolean',
        'enable_phone' => 'boolean',
        'enable_dob' => 'boolean',
        'enable_blood_group' => 'boolean',
        'status' => 'boolean',
        'enable_barcode' => 'boolean',
    ];
}
