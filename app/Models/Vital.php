<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vital extends Model
{
    use HasFactory;

    protected $table = 'vitals';

    public $timestamps = false;

    protected $fillable = [
        'hospital_id',
        'name',
        'range_from',
        'range_to',
        'reference_range',
        'unit',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * Example relationship: if needed, link to patient vitals records
     */
    public function patientVitals()
    {
        return $this->hasMany(PatientVital::class, 'vital_id');
    }
}
