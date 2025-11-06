<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pathology extends Model
{
    use HasFactory;

    protected $table = 'pathology';

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
        'charge_category_id',
        'charge_id',
        'standard_charge',
        'amount',
    ];

    protected $casts = [
        'report_days' => 'integer',
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
     * Relationship with Charge
     */
    public function charge()
    {
        return $this->belongsTo(Charge::class, 'charge_id');
    }

    /**
     * Relationship with ChargeCategory
     */
    public function chargeCategory()
    {
        return $this->belongsTo(ChargeCategory::class, 'charge_category_id');
    }

    /**
     * Relationship with PathologyParameterDetail
     */
    public function parameters()
    {
        return $this->hasMany(PathologyParameterDetail::class, 'pathology_id');
    }
}
