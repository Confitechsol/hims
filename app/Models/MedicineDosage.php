<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineDosage extends Model
{
    use HasFactory;

    protected $table = 'medicine_dosage';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'medicine_category_id',
        'dosage',
        'units_id',
    ];

    /**
     * Relations
     */
    public function category()
    {
        return $this->belongsTo(MedicineCategory::class, 'medicine_category_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'units_id');
    }

    public function medicationReports()
    {
        return $this->hasMany(MedicationReport::class, 'medicine_dosage_id');
    }
}
