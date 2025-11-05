<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineUnit extends Model
{
    use HasFactory;

    protected $table = 'unit';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'unit_name',
    ];

    public function dosages()
    {
        return $this->hasMany(MedicineDosage::class, 'units_id');
    }
}
