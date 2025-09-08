<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientBedHistory extends Model
{
    use HasFactory;

    protected $table = 'patient_bed_history';

    public $timestamps = false; // created_at only, no updated_at

    protected $fillable = [
        'hospital_id',
        'case_reference_id',
        'bed_group_id',
        'bed_id',
        'revert_reason',
        'from_date',
        'to_date',
        'is_active',
        'created_at',
    ];

    /**
     * Relationship with CaseReference
     */
    public function caseReference()
    {
        return $this->belongsTo(CaseReference::class, 'case_reference_id');
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
