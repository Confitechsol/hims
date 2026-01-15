<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pathology extends Model
{
    use HasFactory;

    protected $table = 'pathology';
    
    public $timestamps = false; 

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'test_name',
        'short_name',
        'test_type',
        'pathology_category_id',
        'sub_category',
        'report_days',
        'method',
        'standard_charge_ipd',
        'standard_charge_opd',
        'standard_charge', // Keep for backward compatibility
        'amount', // Keep for backward compatibility
    ];

    protected $casts = [
        'report_days' => 'integer',
        'standard_charge_ipd' => 'decimal:2',
        'standard_charge_opd' => 'decimal:2',
        'standard_charge' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    /**
     * Relationship with PathologyCategory
     */
    public function category()
    {
        return $this->belongsTo(PathologyCategory::class, 'pathology_category_id');
    }

    /**
     * Relationship with OrganisationsCharge for TPA charges
     */
    public function tpaCharges()
    {
        return $this->hasMany(OrganisationsCharge::class, 'pathology_id');
    }

    /**
     * Relationship with PathologyParameterDetail
     */
    public function parameters()
    {
        return $this->hasMany(PathologyParameterDetail::class, 'pathology_id');
    }
}
