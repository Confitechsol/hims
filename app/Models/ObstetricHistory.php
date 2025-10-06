<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObstetricHistory extends Model
{
    use HasFactory;

    protected $table = 'obstetric_history';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'patient_id',
        'place_of_delivery',
        'pregnancy_duration',
        'pregnancy_complications',
        'birth_weight',
        'gender',
        'infant_feeding',
        'alive_dead',
        'date',
        'death_cause',
        'previous_medical_history',
        'special_instruction',
    ];

    /**
     * Relationships
     */

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
