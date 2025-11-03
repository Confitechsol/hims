<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadiologyParameterDetail extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'radiology_parameterdetails';

    // Primary key
    protected $primaryKey = 'id';

    // Fillable fields
    protected $fillable = [
        'hospital_id',
        'branch_id',
        'radiology_id',
        'radiology_parameter_id',
    ];

    // Relationships
    public function radiology()
    {
        return $this->belongsTo(Radiology::class, 'radiology_id');
    }

    public function radiologyParameter()
    {
        return $this->belongsTo(RadiologyParameter::class, 'radiology_parameter_id');
    }
}
