<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadiologyReportParameterDetail extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'radiology_report_parameterdetails';

    // Primary key
    protected $primaryKey = 'id';

    // Fillable fields
    protected $fillable = [
        'hospital_id',
        'radiology_report_id',
        'radiology_parameterdetail_id',
    ];

    // Relationships
    public function radiologyReport()
    {
        return $this->belongsTo(RadiologyReport::class, 'radiology_report_id');
    }

    public function radiologyParameterDetail()
    {
        return $this->belongsTo(RadiologyParameterDetail::class, 'radiology_parameterdetail_id');
    }
}
