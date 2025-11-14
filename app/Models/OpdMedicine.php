<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpdMedicine extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'opd_medicines';

    // Fillable fields
    protected $fillable = [
        'prescription_id',
        'pharmacy_id',
        'medicine_dosage_id',
        'dose_interval_id',
        'dose_duration_id',
        'instruction',
    ];

    /**
     * 🔗 Relation: Each medicine belongs to one OPD Prescription.
     */
    public function prescription()
    {
        return $this->belongsTo(OpdPrescription::class, 'prescription_id');
    }

    /**
     * 🔗 Relation: Each medicine belongs to a Pharmacy item.
     */
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacy_id');
    }

    /**
     * 🔗 Relation: Each medicine has a specific dosage.
     */
    public function medicineDosage()
    {
        return $this->belongsTo(MedicineDosage::class, 'medicine_dosage_id');
    }

    /**
     * 🔗 Relation: Each medicine has an interval (e.g., every 6 hours).
     */
    public function doseInterval()
    {
        return $this->belongsTo(DoseInterval::class, 'dose_interval_id');
    }

    /**
     * 🔗 Relation: Each medicine has a duration (e.g., 5 days).
     */
    public function doseDuration()
    {
        return $this->belongsTo(DoseDuration::class, 'dose_duration_id');
    }
}