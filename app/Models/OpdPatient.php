<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpdPatient extends Model
{
    use HasFactory;

    protected $table = 'opd_patient';

    protected $fillable = [
        'patient_id',
        'opd_id',
        'doctor_id',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function opd()
    {
        return $this->belongsTo(OpdDetail::class);
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
