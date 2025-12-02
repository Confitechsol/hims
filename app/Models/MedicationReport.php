<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicationReport extends Model
{
    use HasFactory;

    protected $table = 'medication_report';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'medicine_dosage_id',
        'pharmacy_id',
        'opd_details_id',
        'ipd_id',
        'date',
        'time',
        'remark',
        'generated_by',
    ];

    /**
     * Relations
     */
    public function medicineDosage()
    {
        return $this->belongsTo(MedicineDosage::class);
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function opdDetails()
    {
        return $this->belongsTo(OpdDetail::class);
    }

    public function ipdDetails()
    {
        return $this->belongsTo(IpdDetail::class, 'ipd_id');
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
