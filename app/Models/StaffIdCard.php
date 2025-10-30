<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffIdCard extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'staff_id_card';

    // Fillable fields
    protected $fillable = [
        'hospital_id',
         'branch_id',
        'title',
        'hospital_name',
        'hospital_address',
        'background',
        'logo',
        'sign_image',
        'header_color',
        'enable_staff_role',
        'enable_staff_id',
        'enable_staff_department',
        'enable_designation',
        'enable_name',
        'enable_fathers_name',
        'enable_mothers_name',
        'enable_date_of_joining',
        'enable_permanent_address',
        'enable_staff_dob',
        'enable_staff_phone',
        'enable_staff_barcode',
        'status',
    ];

    // Casts for booleans and datetime
    protected $casts = [
        'enable_staff_role' => 'boolean',
        'enable_staff_id' => 'boolean',
        'enable_staff_department' => 'boolean',
        'enable_designation' => 'boolean',
        'enable_name' => 'boolean',
        'enable_fathers_name' => 'boolean',
        'enable_mothers_name' => 'boolean',
        'enable_date_of_joining' => 'boolean',
        'enable_permanent_address' => 'boolean',
        'enable_staff_dob' => 'boolean',
        'enable_staff_phone' => 'boolean',
        'enable_staff_barcode' => 'boolean',
        'status' => 'boolean',
        'created_at' => 'datetime',
    ];

    // No updated_at column in migration
    public $timestamps = false;
}
