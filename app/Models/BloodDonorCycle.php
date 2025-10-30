<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodDonorCycle extends Model
{
    use HasFactory;

    // Table name (since it's not plural in DB)
    protected $table = 'blood_donor_cycle';

    // Primary key
    protected $primaryKey = 'id';

    // Disable updated_at since migration doesn’t have it
    public $timestamps = false;

    // Allow mass assignment
    protected $fillable = [
        'hospital_id',
        'branch_id',
        'blood_donor_cycle_id',
        'blood_bank_product_id',
        'blood_donor_id',
        'charge_id',
        'donate_date',
        'bag_no',
        'lot',
        'quantity',
        'standard_charge',
        'apply_charge',
        'amount',
        'institution',
        'note',
        'discount_percentage',
        'tax_percentage',
        'volume',
        'unit',
        'available',
        'created_at',
    ];

    // Relationships (if needed later)
    public function bloodBankProduct()
    {
        return $this->belongsTo(BloodBankProduct::class, 'blood_bank_product_id');
    }

    public function bloodDonor()
    {
        return $this->belongsTo(BloodDonor::class, 'blood_donor_id');
    }

    public function charge()
    {
        return $this->belongsTo(Charge::class, 'charge_id');
    }
}
