<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffPayroll extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'staff_payroll';

    // Primary key
    protected $primaryKey = 'id';

    // Disable Laravel's default timestamps (since only created_at is present)
    public $timestamps = false;

    // Mass assignable attributes
    protected $fillable = [
        'hospital_id',
         'branch_id',
        'basic_salary',
        'pay_scale',
        'grade',
        'is_active',
        'created_at',
    ];

    // Casts
    protected $casts = [
        'basic_salary' => 'float',
        'pay_scale'    => 'integer',
    ];
}
