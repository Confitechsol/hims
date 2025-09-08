<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralPayment extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'referral_payment';

    // Primary key
    protected $primaryKey = 'id';

    // Fillable fields
    protected $fillable = [
        'hospital_id',
        'referral_person_id',
        'patient_id',
        'referral_type',
        'billing_id',
        'bill_amount',
        'percentage',
        'amount',
        'date',
    ];

    /**
     * Relationships
     */

    // Referral Person
    public function referralPerson()
    {
        return $this->belongsTo(ReferralPerson::class, 'referral_person_id');
    }

    // Patient
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    // Referral Type
    public function referralType()
    {
        return $this->belongsTo(ReferralType::class, 'referral_type');
    }
}
