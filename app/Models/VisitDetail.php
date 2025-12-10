<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitDetail extends Model
{
    use HasFactory;

    protected $table = 'visit_details'; // Table name

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'checkup_id',
        'patient_id',
        'opd_details_id',
        'organisation_id',
        'patient_charge_id',
        'transaction_id',
        'cons_doctor',
        'case_type',
        'appointment_date',
        'symptoms_type',
        'symptoms',
        'bp',
        'height',
        'weight',
        'pulse',
        'temperature',
        'respiration',
        'known_allergies',
        'patient_old',
        'casualty',
        'refference',
        'date',
        'note',
        'note_remark',
        'payment_mode',
        'generated_by',
        'live_consult',
        'is_antenatal',
        'can_delete',
    ];

    /**
     * Example relationships (update with actual related models)
     */
    public function opdDetail()
    {
        return $this->belongsTo(OpdDetail::class, 'opd_details_id');
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function patientCharge()
    {
        return $this->belongsTo(PatientCharge::class, 'patient_charge_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'cons_doctor'); // assuming doctors are stored in `users` table
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
