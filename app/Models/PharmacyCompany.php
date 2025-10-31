<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyCompany extends Model
{
    use HasFactory;

    protected $table = 'pharmacy_company';

    protected $fillable = [
        'company_name',
    ];

    /**
     * Relationship: A company has many medicines
     */
    public function medicines()
    {
        return $this->hasMany(Pharmacy::class, 'medicine_company');
    }
}
