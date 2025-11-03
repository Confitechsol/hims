<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientCharge extends Model
{
    use HasFactory;

    protected $table = 'patient_charges';

    public $timestamps = false; // only created_at is present

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'date',
        'ipd_id',
        'opd_id',
        'qty',
        'charge_id',
        'standard_charge',
        'tpa_charge',
        'discount_percentage',
        'tax',
        'apply_charge',
        'amount',
        'note',
        'organisation_id',
        'insurance_validity',
        'insurance_id',
        'created_at',
    ];

    /**
     * Relationship with IPD
     */
    public function ipd()
    {
        return $this->belongsTo(Ipd::class, 'ipd_id');
    }

    /**
     * Relationship with OPD
     */
    public function opd()
    {
        return $this->belongsTo(Opd::class, 'opd_id');
    }

    /**
     * Relationship with Charge
     */
    public function charge()
    {
        return $this->belongsTo(Charge::class, 'charge_id');
    }

    /**
     * Relationship with Organisation
     */
    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }
}
