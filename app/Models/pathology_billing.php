<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PathologyBilling extends Model
{
    use HasFactory;

    protected $table = 'pathology_billing';

    protected $fillable = [
        'hospital_id',
        'case_reference_id',
        'ipd_prescription_basic_id',
        'date',
        'patient_id',
        'doctor_id',
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
        'generated_by',
        'insurance_id',
    ];

    /**
     * Relationship with Patient
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    /**
     * Relationship with Organisation
     */
    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    /**
     * Relationship with Transaction
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    /**
     * Relationship with Doctor (optional - uncomment foreign key in migration if needed)
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}
