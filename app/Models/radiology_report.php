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

    // Fillable fields
    protected $fillable = [
        'hospital_id',
        'radiology_bill_id',
        'radiology_id',
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
}
