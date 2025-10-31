<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineSupplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'supplier_name',
        'supplier_contact',
        'contact_person_name',
        'contact_person_phone',
        'drug_license_number',
        'address',
    ];
}
