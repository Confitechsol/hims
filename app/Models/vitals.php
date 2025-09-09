<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vital extends Model
{
    use HasFactory;

    protected $table = 'vitals';

    public $timestamps = true; // Laravel will handle created_at automatically

    protected $fillable = [
        'hospital_id',
        'name',
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
