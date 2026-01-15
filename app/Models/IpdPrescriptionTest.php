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
        'ipd_prescription_id',  // NEW
        'pathology_id',
        'radiology_id',
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
}