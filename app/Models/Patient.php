<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patients';

    public $timestamps = false; // only created_at is defined in migration

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'lang_id',
        'patient_name',
        'dob',
        'age',
        'month',
        'day',
        'as_of_date',
        'image',
        'mobileno',
        'email',
        'gender',
        'marital_status',
        'blood_group',
        'blood_bank_product_id',
        'address',
        'guardian_name',
        'patient_type',
        'identification_number',
        'known_allergies',
        'note',
        'is_ipd',
        'app_key',
        'organisation_id',
        'insurance_id',
        'insurance_validity',
        'is_dead',
        'is_antenatal',
        'is_active',
        'disable_at',
        'created_at',
        'tpa_code',
        'tpa_validity',
    ];

    protected $casts = [
        'dob'                => 'date',
        'as_of_date'         => 'date',
        'insurance_validity' => 'date',
        'disable_at'         => 'date',
        'created_at'         => 'datetime',
    ];

    /**
     * Relation: A patient may belong to an organisation.
     */
    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }
    public function bloodGroup()
    {
        return $this->belongsTo(BloodBankProduct::class, 'blood_group');
    }

    /**
     * Relation: A patient may have many pathology reports.
     */
    public function pathologyReports()
    {
        return $this->hasMany(PathologyReport::class, 'patient_id');
    }

    /**
     * Relation: A patient may have many bed histories.
     */
    public function bedHistories()
    {
        return $this->hasMany(PatientBedHistory::class, 'patient_id');
    }

    /**
     * Relation: A patient may have many timelines.
     */
    public function timelines()
    {
        return $this->hasMany(PatientTimeline::class, 'patient_id');
    }

    public function opds()
    {
        return $this->belongsToMany(OpdDetail::class, 'opd_patient', 'patient_id', 'opd_id')->withPivot('doctor_id')->orderBy('created_at', 'desc')
            ->withTimestamps();
    }
    public function ipds()
    {
        return $this->belongsToMany(IpdDetail::class, 'ipd_patient', 'patient_id', 'ipd_id')->withPivot('doctor_id')->orderBy('created_at', 'desc')
            ->withTimestamps();
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

}