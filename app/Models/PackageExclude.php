<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageExclude extends Model
{
    use HasFactory;

    protected $table = 'package_excludes';

    protected $fillable = [
        'package_id',
        'charge_category_id',
        'charge_id',
        'description',
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
