<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SymptomsClassification extends Model
{
    use HasFactory;

    // Explicit table name
    protected $table = 'symptoms_classification';

    // Primary key
    protected $primaryKey = 'id';

    // Only created_at exists, no updated_at
    public $timestamps = false;

    // Fillable fields
    protected $fillable = [
        'hospital_id',
        'symptoms_type',
        'created_at',
    ];

    // Casts
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Relationships
     * (If symptoms are linked to patients/diagnoses later, we can add them here)
     */
}
