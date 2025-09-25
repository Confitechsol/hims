<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PathologyReport extends Model
{
    use HasFactory;

    protected $table = 'pathology_report';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'pathology_bill_id',
        'pathology_id',
        'customer_type',
        'patient_id',
        'reporting_date',
        'parameter_update',
        'tax_percentage',
        'apply_charge',
        'collection_date',
        'collection_specialist',
        'pathology_center',
        'approved_by',
        'patient_name',
        'description',
        'pathology_report',
        'report_name',
        'pathology_result',
    ];

    /**
     * Relationship with PathologyBilling
     */
    public function pathologyBilling()
    {
        return $this->belongsTo(PathologyBilling::class, 'pathology_bill_id');
    }

    /**
     * Relationship with Pathology
     */
    public function pathology()
    {
        return $this->belongsTo(Pathology::class, 'pathology_id');
    }

    /**
     * Relationship with Patient
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    /**
     * Relationship with PathologyReportParameterDetail
     */
    public function parameterDetails()
    {
        return $this->hasMany(PathologyReportParameterDetail::class, 'pathology_report_id');
    }
}
