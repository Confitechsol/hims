<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpdDetail extends Model
{
    use HasFactory;

    protected $table = 'ipd_details';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'patient_id',
        'case_reference_id',
        'height',
        'weight',
        'pulse',
        'temperature',
        'respiration',
        'bp',
        'bed',
        'bed_group_id',
        'case_type',
        'casualty',
        'symptoms',
        'known_allergies',
        'patient_old',
        'note',
        'refference',
        'cons_doctor',
        'organisation_id',
        'credit_limit',
        'payment_mode',
        'date',
        'discharged',
        'live_consult',
        'generated_by',
        'is_antenatal',
    ];

    /**
     * Relationships
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function caseReference()
    {
        return $this->belongsTo(CaseReference::class, 'case_reference_id');
    }

    public function bed()
    {
        return $this->belongsTo(Bed::class, 'bed');
    }

    public function bedGroup()
    {
        return $this->belongsTo(BedGroup::class, 'bed_group_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'cons_doctor');
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
