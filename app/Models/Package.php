<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $table = 'packages';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'name',
        'account_head',
        'gst_amount',
        'package_rate',
        'description',
        'status',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'gst_amount' => 'decimal:2',
        'package_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function charges()
    {
        return $this->hasMany(PackageCharge::class)->orderBy('display_order');
    }

    public function excludes()
    {
        return $this->hasMany(PackageExclude::class);
    }

    public function ipdPackages()
    {
        return $this->hasMany(IpdPackage::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
