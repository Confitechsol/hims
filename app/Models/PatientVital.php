<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientVital extends Model
{
    use HasFactory;

    protected $table = 'patients_vitals';

    public $timestamps = false; // only created_at is defined

    protected $fillable = [
        'hospital_id',
        'patient_id',
        'vital_id',
        'reference_range',
        'messure_date',
        'created_at',
    ];

    protected $casts = [
        'messure_date' => 'datetime',
        'created_at'   => 'datetime',
    ];

    /**
     * Relation: Each record belongs to a patient.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    /**
     * Relation: Each record belongs to a vital.
     */
    public function vital()
    {
        return $this->belongsTo(Vital::class, 'vital_id');
    }
}
