<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpdDoctor extends Model
{
    use HasFactory;

    protected $table = 'ipd_doctors';

    protected $fillable = [
        'hospital_id',
        'ipd_id',
        'consult_doctor',
    ];

    /**
     * Relationships
     */
    public function ipdDetail()
    {
        return $this->belongsTo(IpdDetail::class, 'ipd_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'consult_doctor');
    }
}
