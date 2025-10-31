<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'company_name',
    ];
}
