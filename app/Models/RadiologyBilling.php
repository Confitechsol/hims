<?php

namespace App\Models;

use App\Models\Concerns\HasIpdBillVisibility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadiologyBilling extends Model
{
    use HasFactory;
    use HasIpdBillVisibility;

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
        'show_on_approval_bill',
        'show_on_approval_preview',
        'show_on_final_preview',
        'show_on_final_bill',
        'organisation_id',
        'insurance_validity',
        'insurance_id',
        'generated_by',
    ];

    protected $casts = [
        'show_on_approval_bill' => 'boolean',
        'show_on_approval_preview' => 'boolean',
        'show_on_final_preview' => 'boolean',
        'show_on_final_bill' => 'boolean',
    ];

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

    public function reports()
    {
        return $this->hasMany(RadiologyReport::class, 'radiology_bill_id');
    }

    public function prescription()
    {
        return $this->belongsTo(IpdPrescription::class, 'case_reference_id');
    }
}
