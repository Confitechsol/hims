<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralCommission extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'referral_commission';

    // Primary key
    protected $primaryKey = 'id';

    // Fillable fields
    protected $fillable = [
        'hospital_id',
        'branch_id',
        'referral_category_id',
        'referral_type_id',
        'commission',
        'is_active',
    ];

    /**
     * Relationships
     */

    // Referral category
    public function category()
    {
        return $this->belongsTo(ReferralCategory::class, 'referral_category_id');
    }

    // Referral type
    public function type()
    {
        return $this->belongsTo(ReferralType::class, 'referral_type_id');
    }
}
