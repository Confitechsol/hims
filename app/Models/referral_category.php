<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralCategory extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'referral_category';

    // Primary key
    protected $primaryKey = 'id';

    // Fillable fields
    protected $fillable = [
        'hospital_id',
        'name',
        'is_active',
    ];
}
