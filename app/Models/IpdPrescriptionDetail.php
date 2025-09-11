<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpdPrescriptionDetail extends Model
{
    use HasFactory;

    protected $table = 'ipd_prescription_details';

    protected $fillable = [
        'hospital_id',
        'basic_id',
        'pharmacy_id',
        'dosage',
        'dose_interval_id',
        'dose_duration_id',
        'instruction',
    ];

    /**
     * Relationships
     */
    public function prescriptionBasic()
    {
        return $this->belongsTo(IpdPrescriptionBasic::class, 'basic_id');
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacy_id');
    }

    public function doseInterval()
    {
        return $this->belongsTo(DoseInterval::class, 'dose_interval_id');
    }

    public function doseDuration()
    {
        return $this->belongsTo(DoseDuration::class, 'dose_duration_id');
    }
}
