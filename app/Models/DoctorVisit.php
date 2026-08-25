<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorVisit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'doctor_visits';

    protected $fillable = [
        'patient_id',
        'ipd_id',
        'doctor_id',
        'charge_id',
        'reporting_date',
        'rate',
        'no_of_visit',
        'amount',
        'doctor_pay_amount',
        'visit_date',
        'visit_time',
    ];

    protected $casts = [
        'reporting_date' => 'datetime',
        'visit_date' => 'date',
        'rate' => 'decimal:2',
        'amount' => 'decimal:2',
        'doctor_pay_amount' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function ipd()
    {
        return $this->belongsTo(IpdDetail::class, 'ipd_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function charge()
    {
        return $this->belongsTo(Charge::class, 'charge_id');
    }

    /**
     * Current open (non-discharged) IPD for patient only.
     * Returns null if the patient has no active admission.
     */
    public static function resolveIpdIdForPatient(int $patientId): ?int
    {
        $open = IpdDetail::where('patient_id', $patientId)
            ->where(function ($q) {
                $q->whereNull('discharged')
                    ->orWhere('discharged', '!=', 'yes');
            })
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->value('id');

        return $open ? (int) $open : null;
    }
}
