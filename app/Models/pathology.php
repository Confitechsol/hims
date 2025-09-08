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
        'test_name',
        'short_name',
        'test_type',
        'pathology_category_id',
        'unit',
        'sub_category',
        'report_days',
        'method',
        'charge_id',
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
}
