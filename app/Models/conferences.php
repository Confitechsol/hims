<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conference extends Model
{
    use HasFactory;

    protected $table = 'conferences';

    protected $fillable = [
        'hospital_id',
        'purpose',
        'staff_id',
        'patient_id',
        'visit_details_id',
        'ipd_id',
        'created_id',
        'title',
        'date',
        'duration',
        'password',
        'host_video',
        'client_video',
        'description',
        'timezone',
        'return_response',
        'api_type',
        'status',
        'live_consult_link',
    ];

    public $timestamps = false; // only created_at is present, no updated_at

    protected $casts = [
        'date'       => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function visitDetails()
    {
        return $this->belongsTo(VisitDetail::class, 'visit_details_id');
    }

    public function ipd()
    {
        return $this->belongsTo(Ipd::class, 'ipd_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_id');
    }

    public function conferenceStaff()
    {
        return $this->hasMany(ConferenceStaff::class, 'conference_id');
    }
}
