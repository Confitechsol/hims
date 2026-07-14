<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpdPackage extends Model
{
    use HasFactory;

    protected $table = 'ipd_packages';

    protected $fillable = [
        'ipd_id',
        'package_id',
        'bed_group_id',
        'applied_date',
        'applied_by',
        'package_rate',
        'approval_percentage',
        'discount_percentage',
        'discount_amount',
        'gst_amount',
        'final_amount',
        'status',
        'note',
    ];

    protected $casts = [
        'applied_date' => 'date',
        'package_rate' => 'decimal:2',
        'approval_percentage' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'gst_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function ipd()
    {
        return $this->belongsTo(IpdDetail::class, 'ipd_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function appliedBy()
    {
        return $this->belongsTo(User::class, 'applied_by');
    }
}
