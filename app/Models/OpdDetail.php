<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpdDetail extends Model
{
    use HasFactory;

    protected $table = 'opd_details';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'case_reference_id',
        'patient_id',
        'generated_by',
        'is_ipd_moved',
        'discharged',
    ];

    /**
     * Relationships
     */

    public function caseReference()
    {
        return $this->belongsTo(CaseReference::class, 'case_reference_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
