<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    use HasFactory;

    protected $table = 'charges';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'charge_category_id',
        'tax_category_id',
        'charge_unit_id',
        'name',
        'standard_charge',
        'date',
        'description',
        'status',
    ];

    public $timestamps = false; // only created_at exists

    protected $casts = [
        'standard_charge' => 'decimal:2',
        'date' => 'date',
        'created_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function category()
    {
        return $this->belongsTo(ChargeCategory::class, 'charge_category_id');
    }

    public function taxCategory()
    {
        return $this->belongsTo(TaxCategory::class, 'tax_category_id');
    }

    public function unit()
    {
        return $this->belongsTo(ChargeUnit::class, 'charge_unit_id');
    }

    public function chargeType()
    {
        return $this->category->chargeType(); // This accesses the ChargeTypeMaster through the ChargeCategory
    }

   
}
