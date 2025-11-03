<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftDetail extends Model
{
    use HasFactory;

    protected $table = 'shift_details';

    protected $fillable = [
        'hospital_id',
         'branch_id',
        'staff_id',
        'consult_duration',
        'charge_id',
    ];

    // Relationships
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function charge()
    {
        return $this->belongsTo(Charge::class, 'charge_id');
    }
}
