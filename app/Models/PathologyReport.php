<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PathologyReport extends Model
{
    use HasFactory;

    protected $table = 'pathology_report';
    
    public $timestamps = false; // Disable timestamps if table doesn't have created_at/updated_at

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'pathology_bill_id',
        'pathology_id',
        'ipd_prescription_test_id',
        'instance_number',
        'customer_type',
        'patient_id',
        'reporting_date',
        'parameter_update',
        'tax_percentage',
        'apply_charge',
        'collection_date',
        'collection_by',
        'collection_specialist',
        'pathology_center',
        'approved_by',
        'patient_name',
        'description',
        'pathology_report',
        'report_name',
        'path_doc_path',
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

    /**
     * Relationship with IpdPrescriptionTest (prescription test instance)
     */
    public function prescriptionTestInstance()
    {
        return $this->belongsTo(IpdPrescriptionTest::class, 'ipd_prescription_test_id');
    }
}
