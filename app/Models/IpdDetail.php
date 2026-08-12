<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpdDetail extends Model
{
    use HasFactory;

    protected $table = 'ipd_details';

    /**
     * Patient IDs whose latest IPD record has discharged = 'yes'.
     * Used to exclude discharged patients from radiology, pathology, pharmacy billing.
     */
    public static function getDischargedPatientIds(): array
    {
        return self::from('ipd_details as i1')
            ->select('i1.patient_id')
            ->where('i1.discharged', 'yes')
            ->whereRaw('i1.id = (SELECT MAX(i2.id) FROM ipd_details i2 WHERE i2.patient_id = i1.patient_id)')
            ->pluck('patient_id')
            ->unique()
            ->values()
            ->all();
    }

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
        'symptoms_description',
        'symptoms_type',
        'symptoms_title',
        'known_allergies',
        'patient_old',
        'note',
        'refference',
        'cons_doctor',
        'cons_doctor2',
        'cons_doctor3',
        'cons_doctor4',
        'organisation_id',
        'insurance_company_id',
        'is_cashless',
        'insurance_policy_no',
        'insurance_card_no',
        'ccn_no',
        'initial_approval_amount',
        'final_approval_amount',
        'credit_limit',
        'payment_mode',
        'date',
        'discharged',
        'discharged_date',
        'final_bill_generated_at',
        'final_bill_generated_by',
        'include_post_discharge_bed_charge',
        'physical_release_at',
        'net_amount',
        'tax',
        'amount',
        'mou_discount',
        'special_discount',
        'due_patient_party_doctor_id',
        'due_patient_party_amount',
        'due_patient_party_receipt_type',
        'live_consult',
        'generated_by',
        'is_antenatal',
        'ipd_no',
    ];

    protected $casts = [
        'mou_discount' => 'decimal:2',
        'special_discount' => 'decimal:2',
        'initial_approval_amount' => 'decimal:2',
        'final_approval_amount' => 'decimal:2',
        'due_patient_party_amount' => 'decimal:2',
        'is_cashless' => 'boolean',
        'final_bill_generated_at' => 'datetime',
        'physical_release_at' => 'datetime',
        'include_post_discharge_bed_charge' => 'boolean',
    ];

    public function isFinalBillGenerated(): bool
    {
        return ! empty($this->final_bill_generated_at);
    }

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

    public function bedDetail()
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

    public function duePatientPartyDoctor()
    {
        return $this->belongsTo(Doctor::class, 'due_patient_party_doctor_id');
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function insuranceCompany()
    {
        return $this->belongsTo(InsuranceCompany::class, 'insurance_company_id');
    }

    public function isInsuranceBilling(): bool
    {
        return (bool) ($this->insurance_company_id || $this->is_cashless || $this->organisation_id);
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
    public function patients()
    {
        return $this->belongsToMany(Patient::class, 'ipd_patient', 'ipd_id', 'patient_id')->withPivot('doctor_id')
            ->withTimestamps();
    }
     public function treatmentHistory()
    {
        return $this->hasMany(ConsultantRegister::class, 'ipd_id', 'id');
    }
    public function ipdPatients()
    {
        return $this->hasOne(IpdPatient::class, 'ipd_id', 'id');
    }
    public function visits()
{
    return $this->hasMany(VisitDetail::class, 'checkup_id');
}

public function charge()
    {
        return $this->hasMany(IpdCharges::class, 'ipd_id');
    }

public function transactions()
{
    return $this->hasMany(Transaction::class, 'ipd_id');
}

public function ipdPackages()
{
    return $this->hasMany(\App\Models\IpdPackage::class, 'ipd_id');
}
}