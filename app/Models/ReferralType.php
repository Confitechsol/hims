<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralType extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'referral_type';

    // Mass assignable fields
    protected $fillable = [
        'hospital_id',
        'name',
        'prefixes_type',
        'is_active',
    ];

    /**
     * Relationships
     */

    // A referral type can have many person commissions
    public function personCommissions()
    {
        return $this->hasMany(ReferralPersonCommission::class, 'referral_type_id');
    }

    // A referral type can have many referral commissions
    public function commissions()
    {
        return $this->hasMany(ReferralCommission::class, 'referral_type_id');
    }
}
