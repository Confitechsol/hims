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
        'case_reference_id',
        'opd_details_id',
        'ipd_details_id',
        'discharge_by',
        'discharge_date',
        'discharge_status',
        'death_date',
        'refer_date',
        'refer_to_hospital',
        'reason_for_referral',
        'operation',
        'diagnosis',
        'investigations',
        'treatment_home',
        'note',
    ];

    // Casts for date/datetime fields
    protected $casts = [
        'discharge_date' => 'datetime',
        'death_date'     => 'datetime',
        'refer_date'     => 'datetime',
    ];

    // Relationships
    public function caseReference()
    {
        return $this->belongsTo(CaseReference::class, 'case_reference_id');
    }

    public function opdDetails()
    {
        return $this->belongsTo(OpdDetails::class, 'opd_details_id');
    }

    public function ipdDetails()
    {
        return $this->belongsTo(IpdDetails::class, 'ipd_details_id');
    }

    public function dischargedBy()
    {
        return $this->belongsTo(User::class, 'discharge_by');
    }
}
