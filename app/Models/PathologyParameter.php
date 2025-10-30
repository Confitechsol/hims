<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PathologyParameter extends Model
{
    use HasFactory;

    protected $table = 'pathology_parameter';
    public $timestamps = false; 

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'parameter_name',
        'test_value',
        'reference_range',
        'range_from',
        'range_to',
        'gender',
        'unit_id',
        'description',
    ];

    /**
     * Relationship with Unit
     */
    public function unitRelation()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
