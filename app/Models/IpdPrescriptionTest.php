<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpdPrescriptionTest extends Model
{
    use HasFactory;

    protected $table = 'ipd_prescription_test';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'ipd_prescription_basic_id',
        'ipd_prescription_id',
        'pathology_id',
        'radiology_id',
        'instance_number',
        'test_date',
        'prescription_time',
        'notes',
    ];

    /**
     * Relationships
     */
    public function prescriptionBasic()
    {
        return $this->belongsTo(IpdPrescriptionBasic::class, 'ipd_prescription_basic_id');
    }

    public function prescription()
    {
        return $this->belongsTo(IpdPrescription::class, 'ipd_prescription_id');
    }

    public function pathology()
    {
        return $this->belongsTo(Pathology::class, 'pathology_id');
    }

    public function radiology()
    {
        return $this->belongsTo(Radio::class, 'radiology_id');
    }

    /**
     * Relationship with PathologyReport
     */
    public function pathologyReports()
    {
        return $this->hasMany(PathologyReport::class, 'ipd_prescription_test_id');
    }

    /**
     * Relationship with RadiologyReport
     */
    public function radiologyReports()
    {
        return $this->hasMany(RadiologyReport::class, 'ipd_prescription_test_id');
    }

    /**
     * Scope to get instances for same test on same day
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $testId
     * @param string $date Date in Y-m-d format
     * @param string $testType 'pathology' or 'radiology'
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSameTestSameDay($query, $testId, $date, $testType = 'pathology')
    {
        $column = $testType === 'pathology' ? 'pathology_id' : 'radiology_id';
        return $query->where($column, $testId)
                     ->whereDate('test_date', $date);
    }

    /**
     * Get the next instance number for a test on a given date
     * 
     * @param int $testId
     * @param string $date Date in Y-m-d format
     * @param string $testType 'pathology' or 'radiology'
     * @param int|null $prescriptionId Optional: limit to specific prescription
     * @return int
     */
    public static function getNextInstanceNumber($testId, $date, $testType = 'pathology', $prescriptionId = null)
    {
        $query = self::sameTestSameDay($testId, $date, $testType);
        
        if ($prescriptionId) {
            // Count instances across all prescriptions for same patient/day
            // Get patient_id from prescription
            $prescription = \App\Models\IpdPrescription::find($prescriptionId);
            if ($prescription && $prescription->ipd_id) {
                $ipd = \App\Models\IpdDetail::find($prescription->ipd_id);
                if ($ipd) {
                    // Count instances for this patient on this date across all prescriptions
                    $allPrescriptions = \App\Models\IpdPrescription::where('ipd_id', $ipd->id)
                        ->whereDate('date', $date)
                        ->pluck('id');
                    
                    $maxInstance = self::whereIn('ipd_prescription_id', $allPrescriptions)
                        ->sameTestSameDay($testId, $date, $testType)
                        ->max('instance_number');
                    
                    return ($maxInstance ?? 0) + 1;
                }
            }
        }
        
        $maxInstance = $query->max('instance_number');
        return ($maxInstance ?? 0) + 1;
    }
}