<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsuranceCompany extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'name',
        'code',
        'contact_no',
        'address',
        'contact_person_name',
        'contact_person_phone',
    ];

    public function tpas()
    {
        return $this->hasMany(Organisation::class, 'insurance_company_id');
    }

    public function ratePanels()
    {
        return $this->belongsToMany(
            InsuranceRatePanel::class,
            'insurance_company_panel',
            'insurance_company_id',
            'insurance_rate_panel_id'
        )->withTimestamps();
    }
}
