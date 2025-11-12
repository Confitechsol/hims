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
        'opd_no',
        'patient_id',
        'appointment_date',
        'case_type',
        'apply_tpa',
        'casualty',
        'reference',
        'doctor_id',
        'charge_category_id',
        'charge_id',
        'standard_charge',
        'applied_charge',
        'discount',
        'tax',
        'amount',
        'payment_mode',
        'paid_amount',
        'payment_date',
        'live_consultation',
        'symptoms_type',
        'symptoms_title',
        'allergies',
        'symptoms_description',
        'note',
        'status',

        'created_by',
    ];

    /**
     * Relationships
     */

    // public function caseReference()
    // {
    //     return $this->belongsTo(CaseReference::class, 'case_reference_id');
    // }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
    public function chargeCategory()
    {
        return $this->belongsTo(ChargeCategory::class, 'charge_category_id');
    }
    public function charge()
    {
        return $this->belongsTo(Charge::class, 'charge_id');
    }

    public function patients()
    {
        return $this->belongsToMany(Patient::class, 'opd_patient', 'opd_id', 'patient_id')->withPivot('doctor_id')
            ->withTimestamps();
    }
    // public function symptom()
    // {
    //     return $this->belongsTo(Symptom::class, 'symptom_id');
    // }
}