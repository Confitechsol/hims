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
     * A death report belongs to a case reference
     */
    public function caseReference()
    {
        return $this->belongsTo(CaseReference::class, 'case_reference_id');
    }
}
