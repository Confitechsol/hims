<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalBranch extends Model
{
    use HasFactory;

    protected $table = 'hospital_branches';

    protected $fillable = [
        'hospital_id',
        'name',
        'branch_id',
        'email',
        'phone',
        'city',
        'state',
        'address',
        'timezone',
        'image',
        'mini_logo',
        'is_active',
        'currency',
        'currency_symbol',
        'credit_limit',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }
}
