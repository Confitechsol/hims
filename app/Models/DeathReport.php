<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeathReport extends Model
{
    use HasFactory;

    protected $table = 'death_report';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'patient_id',
        'case_reference_id',
        'attachment',
        'attachment_name',
        'death_date',
        'guardian_name',
        'death_report',
        'is_active',
        'patient_name',
        'doctor_name',
        'due_to_a',
        'due_to_b',
        'due_to_c',
    ];

    public $timestamps = false;   // ✅ VERY IMPORTANT

    /**
     * A death report belongs to a patient
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    /**
     * A death report belongs to a hospital
     */
    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id','hospital_id');
    }

    public function ipd_details()
    {
        return $this->belongsTo(IpdDetails::class, 'patient_id');
    }

    function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_name','name');
    }

    

    /**
     * A death report belongs to a case reference
     */
    public function caseReference()
    {
        return $this->belongsTo(CaseReference::class, 'case_reference_id');
    }
}
