<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointment';

    protected $fillable = [
        'patient_id',
        'case_reference_id',
        'visit_details_id',
        'date',
        'priority',
        'specialist',
        'doctor',
        'amount',
        'message',
        'appointment_status',
        'source',
        'is_opd',
        'is_ipd',
        'doctor_shift_time_id',
        'doctor_global_shift_id',
        'is_queue',
        'created_time',
        'rejected_time',
        'live_consult',
        'live_consult_link',
    ];

    protected $casts = [
        'date' => 'datetime',
        'created_time' => 'datetime',
        'rejected_time' => 'datetime',
        'is_queue' => 'integer',
    ];

    // Relationships
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function caseReference(): BelongsTo
    {
        return $this->belongsTo(CaseReference::class, 'case_reference_id');
    }

    public function visitDetails(): BelongsTo
    {
        return $this->belongsTo(VisitDetail::class, 'visit_details_id');
    }

    public function doctorUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor');
    }

    public function doctorShift(): BelongsTo
    {
        return $this->belongsTo(DoctorShiftTime::class, 'doctor_shift_time_id');
    }

    public function doctorGlobalShift(): BelongsTo
    {
        return $this->belongsTo(DoctorGlobalShift::class, 'doctor_global_shift_id');
    }
}
