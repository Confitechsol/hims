<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageCharge extends Model
{
    use HasFactory;

    protected $table = 'package_charges';

    protected $fillable = [
        'package_id',
        'charge_type',
        'charge_category_id',
        'charge_id',
        'amount',
        'is_percentage',
        'display_order',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_percentage' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function chargeCategory()
    {
        return $this->belongsTo(ChargeCategory::class);
    }

    public function charge()
    {
        return $this->belongsTo(Charge::class);
    }
}
