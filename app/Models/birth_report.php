<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BirthReport extends Model
{
    use HasFactory;

    protected $table = 'birth_report';

    protected $fillable = [
        'hospital_id',
        'child_name',
        'child_pic',
        'gender',
        'birth_date',
        'weight',
        'patient_id',
        'case_reference_id',
        'contact',
        'mother_pic',
        'father_name',
        'father_pic',
        'birth_report',
        'document',
        'address',
        'is_active',
    ];

    protected $casts = [
        'birth_date' => 'datetime',
    ];

    // Relationships
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function caseReference(): BelongsTo
    {
        return $this->belongsTo(CaseModel::class, 'case_reference_id'); // Assuming CaseModel is the model
    }
}
