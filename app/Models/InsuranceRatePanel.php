<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsuranceRatePanel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function insuranceCompanies()
    {
        return $this->belongsToMany(
            InsuranceCompany::class,
            'insurance_company_panel',
            'insurance_rate_panel_id',
            'insurance_company_id'
        )->withTimestamps();
    }

    public function testRates()
    {
        return $this->hasMany(InsuranceTestRate::class, 'insurance_rate_panel_id');
    }
}
