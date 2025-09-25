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
    ];

    /**
     * Relationships
     */
    public function radiologyCategory()
    {
        return $this->belongsTo(RadiologyCategory::class, 'radiology_category_id');
    }

    public function charge()
    {
        return $this->belongsTo(Charge::class, 'charge_id');
    }
}
