<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineGroup extends Model
{
    use HasFactory;

    protected $table = 'medicine_group';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'group_name',
    ];
}
