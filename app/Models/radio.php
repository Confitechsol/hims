<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Radio extends Model
{
    use HasFactory;

    protected $table = 'radio';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'test_name',
        'short_name',
        'test_type',
        'radiology_category_id',
        'sub_category',
        'report_days',
        'charge_id',
        'standard_charge_ipd',
        'standard_charge_opd',
        'standard_charge', // Keep for backward compatibility
        'amount',          // Keep for backward compatibility
    ];

    protected $casts = [
        'report_days'         => 'integer',
        'standard_charge_ipd' => 'decimal:2',
        'standard_charge_opd' => 'decimal:2',
        'standard_charge'     => 'decimal:2',
        'amount'              => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function radiologyCategory()
    {
        return $this->belongsTo(RadiologyCategory::class, 'radiology_category_id');
    }

    public function tpaCharges()
    {
        return $this->hasMany(OrganisationsCharge::class, 'radiology_id');
    }
}
