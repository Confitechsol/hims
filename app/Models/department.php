<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'department';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'department_name',
        'is_active',
    ];

    /**
     * Example relationship:
     * A department can have many doctors.
     */
    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'department_id');
    }
}
