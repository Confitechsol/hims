<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralPersonCommission extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'referral_person_commission';

    // Mass assignable fields
    protected $fillable = [
        'hospital_id',
        'branch_id',
        'referral_person_id',
        'referral_type_id',
        'commission',
    ];

    /**
     * Relationships
     */

    // A commission belongs to a referral person
    public function referralPerson()
    {
        return $this->belongsTo(ReferralPerson::class, 'referral_person_id');
    }

    // A commission belongs to a referral type
    public function referralType()
    {
        return $this->belongsTo(ReferralType::class, 'referral_type_id');
    }
}
