<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodIssue extends Model
{
    use HasFactory;

    protected $table = 'blood_issue';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'patient_id',
        'case_reference_id',
        'blood_donor_cycle_id',
        'date_of_issue',
        'hospital_doctor',
        'reference',
        'charge_id',
        'standard_charge',
        'tax_percentage',
        'discount_percentage',
        'amount',
        'net_amount',
        'institution',
        'technician',
        'remark',
        'organisation_id',
        'insurance_validity',
        'insurance_id',
        'generated_by',
    ];

    // created_at & updated_at are present
    public $timestamps = true;

    /**
     * Relationships
     */
    public function donorCycle()
    {
        return $this->belongsTo(BloodDonorCycle::class, 'blood_donor_cycle_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'hospital_doctor');
    }
}
