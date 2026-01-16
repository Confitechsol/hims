<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpdDaywiseBedCharge extends Model
{
    use HasFactory;

    protected $table = 'ipd_daywise_bed_charges';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'ipd_id',
        'case_reference_id',
        'patient_id',
        'charge_date',
        'period_start_date',
        'period_end_date',
        'bed_group_id',
        'bed_id',
        'bed_charge',
        'bed_charge_rate',
        'no_of_days',
        'is_active',
    ];

    protected $casts = [
        'ipd_id' => 'integer',
        'case_reference_id' => 'integer',
        'patient_id' => 'integer',
        'charge_date' => 'date',
        'period_start_date' => 'date',
        'period_end_date' => 'date',
        'bed_group_id' => 'integer',
        'bed_id' => 'integer',
        'bed_charge' => 'decimal:2',
        'bed_charge_rate' => 'decimal:2',
        'no_of_days' => 'integer',
    ];

    /**
     * Relationship with IpdDetail
     */
    public function ipd()
    {
        return $this->belongsTo(IpdDetail::class, 'ipd_id');
    }

    /**
     * Relationship with CaseReference
     */
    public function caseReference()
    {
        return $this->belongsTo(CaseReference::class, 'case_reference_id');
    }

    /**
     * Relationship with Patient
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    /**
     * Relationship with BedGroup
     */
    public function bedGroup()
    {
        return $this->belongsTo(BedGroup::class, 'bed_group_id');
    }

    /**
     * Relationship with Bed
     */
    public function bed()
    {
        return $this->belongsTo(Bed::class, 'bed_id');
    }
}
