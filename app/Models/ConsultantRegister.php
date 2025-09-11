<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultantRegister extends Model
{
    use HasFactory;

    protected $table = 'consultant_register';

    protected $fillable = [
        'hospital_id',
        'ipd_id',
        'date',
        'ins_date',
        'instruction',
        'cons_doctor',
    ];

    protected $casts = [
        'date' => 'datetime',
        'ins_date' => 'date',
    ];

    /**
     * Relationships
     */
    public function ipd()
    {
        return $this->belongsTo(IpdDetail::class, 'ipd_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'cons_doctor');
    }
}
