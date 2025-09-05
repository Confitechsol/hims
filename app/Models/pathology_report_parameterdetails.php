<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PathologyReportParameterDetail extends Model
{
    use HasFactory;

    protected $table = 'pathology_report_parameterdetails';

    protected $fillable = [
        'hospital_id',
        'pathology_report_id',
        'pathology_parameterdetail_id',
        'pathology_report_value',
    ];

    /**
     * Relationship with PathologyReport
     */
    public function pathologyReport()
    {
        return $this->belongsTo(PathologyReport::class, 'pathology_report_id');
    }

    /**
     * Relationship with PathologyParameterDetail
     */
    public function pathologyParameterDetail()
    {
        return $this->belongsTo(PathologyParameterDetail::class, 'pathology_parameterdetail_id');
    }
}
