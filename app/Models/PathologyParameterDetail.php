<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PathologyParameterDetail extends Model
{
    use HasFactory;

    protected $table = 'pathology_parameterdetails';
    
    public $timestamps = false; // Disable timestamps since updated_at column doesn't exist

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'pathology_id',
        'pathology_parameter_id',
    ];

    /**
     * Relationship with Pathology
     */
    public function pathology()
    {
        return $this->belongsTo(Pathology::class, 'pathology_id');
    }

    /**
     * Relationship with PathologyParameter
     */
    public function parameter()
    {
        return $this->belongsTo(PathologyParameter::class, 'pathology_parameter_id');
    }
}
