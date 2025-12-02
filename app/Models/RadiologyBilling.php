<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadiologyBilling extends Model
{
    use HasFactory;

    protected $table = 'radiology_billing';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'patient_id',
        'case_reference_id',
        'ipd_prescription_basic_id',
        'doctor_id',
        'date',
        'doctor_name',
        'total',
        'discount_percentage',
        'discount',
        'tax_percentage',
        'tax',
        'net_amount',
        'transaction_id',
        'note',
        'organisation_id',
        'insurance_validity',
        'insurance_id',
        'generated_by',
    ];

    /**
     * Relationships
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    /**
     * Relationship with RadiologyReport
     */
    public function reports()
    {
        return $this->hasMany(RadiologyReport::class, 'radiology_bill_id');
    }
}
