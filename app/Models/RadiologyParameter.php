<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadiologyParameter extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'radiology_parameter';

    // Primary key
    protected $primaryKey = 'id';

    // Fillable fields
    protected $fillable = [
        'hospital_id',
        'parameter_name',
        'test_value',
        'reference_range',
        'range_from',
        'range_to',
        'gender',
        'unit',
        'description',
    ];

    /**
     * Example relationships
     * 
     */
    
    
    
}
