<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DischargeCard extends Model
{
    use HasFactory;

    // Table name (explicit since it doesn’t follow plural convention)
    protected $table = 'discharge_card';

    // Primary key
    protected $primaryKey = 'id';

    // Mass assignable attributes
    protected $fillable = [
        'hospital_id',
        'branch_id',
        'opd_details_id',
        'ipd_details_id',
        'case_reference_id',
        'discharge_number',

        'patient_name',
        'patient_id',
        'admission_no',
        'discharge_date',
        'discharge_time',
        'admission_date',
        'admit_time',
        'bed',
        'age',
        'gender',
        'phone',
        'marital_status',
        'address',
        'guardian',
        'relation',
        'nationality',
        'under_care_dr',
        'registration_no',
        'referral',
        'corporate',
        'reason_discharge',
        'ot_date',
        'ot_type',
        'ot_name',
        'ot_done',
        'ot_done_by',
        'diagnosis',
        'ot_note',
        'discharge_advice',
        'course_in_hospital',
        'present_complaints',
        'remarks',
        'discharged_by',
        'barcode',
        'created_by',
    ];

    // Casts for date/datetime fields

    // Relationships
    public function caseReference()
    {
        return $this->belongsTo(CaseReference::class, 'case_reference_id');
    }

    public function opdDetails()
    {
        return $this->belongsTo(OpdDetail::class, 'opd_details_id');
    }

    public function ipdDetails()
    {
        return $this->belongsTo(IpdDetail::class, 'ipd_details_id');
    }

    // public function dischargedBy()
    // {
    //     return $this->belongsTo(User::class, 'discharge_by');
    // }
}
