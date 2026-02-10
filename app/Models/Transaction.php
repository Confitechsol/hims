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
        'receipt_no',
        'receipt_type',
        'slip_no',
        'booking_no',
        'final_bill_no',
        'tds',
        'paid_by',
        'narration',
        'remarks',
        'bank_name',
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
        return $this->belongsTo(IpdDetail::class, 'ipd_id');
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

    /**
     * Get financial year based on start_month setting
     */
    public static function getFinancialYear($date = null)
    {
        $date = $date ? \Carbon\Carbon::parse($date) : \Carbon\Carbon::now();
        
        // Get start_month from hospital settings (default to April if not set)
        $hospital = \App\Models\Hospital::first();
        $startMonth = $hospital && $hospital->start_month ? (int) $hospital->start_month : 4; // Default to April
        
        $currentMonth = $date->month;
        $currentYear = $date->year;
        
        // If current month is before start month, financial year started in previous calendar year
        if ($currentMonth < $startMonth) {
            $financialYear = $currentYear - 1;
        } else {
            $financialYear = $currentYear;
        }
        
        return $financialYear;
    }

    /**
     * Generate next receipt number (format: MR-YYYY-NNNN)
     * YYYY is based on financial year (accounting year)
     */
    public static function generateReceiptNo($date = null)
    {
        $financialYear = self::getFinancialYear($date);
        $prefix = 'MR-' . $financialYear . '-';
        
        // Get the last receipt number for this financial year
        $lastReceipt = self::where('receipt_no', 'like', $prefix . '%')
            ->orderBy('receipt_no', 'desc')
            ->first();
        
        if ($lastReceipt && $lastReceipt->receipt_no) {
            // Extract the number part
            $lastNumber = (int) substr($lastReceipt->receipt_no, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
