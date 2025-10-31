<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralPerson extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'referral_person';

    // Primary key
    protected $primaryKey = 'id';

    // Fillable fields
    protected $fillable = [
        'hospital_id',
        'branch_id',
        'name',
        'category_id',
        'contact',
        'person_name',
        'person_phone',
        'standard_commission',
        'address',
        'is_active',
    ];

    /**
     * Relationships
     */

    // Category
    public function category()
    {
        return $this->belongsTo(ReferralCategory::class, 'category_id');
    }

    // Referral Payments
    public function payments()
    {
        return $this->hasMany(ReferralPayment::class, 'referral_person_id');
    }
}
