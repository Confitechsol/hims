<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultCharge extends Model
{
    use HasFactory;

    protected $table = 'consult_charges';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'doctor',
        'standard_charge',
        'date',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'standard_charge' => 'float',
    ];

    /**
     * Relationships
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor');
    }
}
