<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Table name (optional if same as plural of model)
    protected $table = 'transactions';

    // Primary key (auto handled since it's `id`)
    protected $primaryKey = 'id';

    // Disable default timestamps (since you only have created_at)
    public $timestamps = false;

    // Mass assignable attributes
    protected $fillable = [
        'hospital_id',
         'branch_id',
        'type',
        'section',
        'patient_id',
        'case_reference_id',
        'opd_id',
        'ipd_id',
        'pharmacy_bill_basic_id',
        'pathology_billing_id',
        'radiology_billing_id',
        'blood_donor_cycle_id',
        'blood_issue_id',
        'ambulance_call_id',
        'appointment_id',
        'bill_id',
        'attachment',
        'attachment_name',
        'amount_type',
        'amount',
        'payment_mode',
        'cheque_no',
        'cheque_date',
        'payment_date',
        'note',
        'received_by',
        'created_at',
    ];

    /**
     * Relationships
     */

    // Example relationships (you can modify according to actual model names)
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function caseReference()
    {
        return $this->belongsTo(CaseReference::class, 'case_reference_id');
    }

    public function opd()
    {
        return $this->belongsTo(Opd::class, 'opd_id');
    }

    public function ipd()
    {
        return $this->belongsTo(Ipd::class, 'ipd_id');
    }

    public function pharmacyBill()
    {
        return $this->belongsTo(PharmacyBill::class, 'pharmacy_bill_basic_id');
    }

    public function pathologyBilling()
    {
        return $this->belongsTo(PathologyBilling::class, 'pathology_billing_id');
    }

    public function radiologyBilling()
    {
        return $this->belongsTo(RadiologyBilling::class, 'radiology_billing_id');
    }

    public function bloodDonorCycle()
    {
        return $this->belongsTo(BloodDonorCycle::class, 'blood_donor_cycle_id');
    }

    public function bloodIssue()
    {
        return $this->belongsTo(BloodIssue::class, 'blood_issue_id');
    }

    public function ambulanceCall()
    {
        return $this->belongsTo(AmbulanceCall::class, 'ambulance_call_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
