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

    public function pathology()
    {
        return $this->belongsTo(Pathology::class, 'pathology_id');
    }

    public function radiology()
    {
        return $this->belongsTo(Radiology::class, 'radiology_id');
    }
}
