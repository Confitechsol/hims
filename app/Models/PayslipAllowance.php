<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayslipAllowance extends Model
{
    use HasFactory;

    protected $table = 'payslip_allowance';

    public $timestamps = false; // only created_at is present

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'staff_payslip_id',
        'staff_id',
        'allowance_type',
        'amount',
        'cal_type',
        'created_at',
    ];

    protected $casts = [
        'amount' => 'float',
        'created_at' => 'datetime',
    ];

    /*
     * Relationships
     */
    public function staffPayslip()
    {
        return $this->belongsTo(StaffPayslip::class, 'staff_payslip_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
