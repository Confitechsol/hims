<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpdPatient extends Model
{
    use HasFactory;

    protected $table = 'ipd_patient';

    protected $fillable = [
        'patient_id',
        'ipd_id',
        'doctor_id',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function ipd()
    {
        return $this->belongsTo(IpdDetail::class);
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}