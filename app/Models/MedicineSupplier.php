<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineSupplier extends Model
{
    use HasFactory;

    protected $table = 'medicine_supplier';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'supplier',
        'contact',
        'supplier_person',
        'supplier_person_contact',
        'supplier_drug_licence',
        'address',
    ];
}
