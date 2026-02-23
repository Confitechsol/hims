<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadiologyReport extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'radiology_report';

    // Primary key
    protected $primaryKey = 'id';
    
    // Disable timestamps if table doesn't have created_at/updated_at
    public $timestamps = false;

    // Fillable fields
    protected $fillable = [
        'hospital_id',
        'branch_id',
        'radiology_bill_id',
        'radiology_id',
        'ipd_prescription_test_id',
        'instance_number',
        'patient_id',
        'customer_type',
        'patient_name',
        'consultant_doctor',
        'reporting_date',
        'parameter_update',
        'description',
        'radiology_report',
        'report_name',
        'radiology_result',
        'tax_percentage',
        'apply_charge',
        'radiology_center',
        'generated_by',
        'collection_specialist',
        'collection_date',
        'radio_doc_path',
        'collection_by',
        'approved_by',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function radiology()
    {
        return $this->belongsTo(Radio::class, 'radiology_id');
    }

    /**
     * Relationship with IpdPrescriptionTest (prescription test instance)
     */
    public function prescriptionTestInstance()
    {
        return $this->belongsTo(IpdPrescriptionTest::class, 'ipd_prescription_test_id');
    }
}
