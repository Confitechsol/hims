<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientTimeline extends Model
{
    use HasFactory;

    protected $table = 'patient_timeline';

    public $timestamps = false; // only created_at exists in migration

    protected $fillable = [
        'hospital_id',
        'patient_id',
        'title',
        'timeline_date',
        'description',
        'document',
        'status',
        'date',
        'generated_users_type',
        'generated_users_id',
        'created_at',
    ];

    protected $casts = [
        'timeline_date' => 'datetime',
        'date'          => 'datetime',
        'created_at'    => 'datetime',
    ];

    /**
     * Relation: A timeline belongs to a patient.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    
    public function generatedUser()
    {
        return $this->belongsTo(User::class, 'generated_users_id');
    }
}
