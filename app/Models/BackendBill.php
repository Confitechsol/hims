<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class BackendBill extends Model
{
    //
    protected $table='backend_bill';

    use SoftDeletes;


     protected $fillable = [
        'patient_name',
        'age',
        'gender',
        'address',
        'patienttype',
        'date',
        'case_no',
        'patient_id',
        'doctor_id',
        'doctor_name',
        'total_amount',
        'adjustment_type',
        'adjustment_amount',
        
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'adjustment_amount' => 'decimal:2',
    ];

    public function billItems()
    {
        return $this->hasMany(BackendBillItem::class, 'case_no', 'case_no');
    }

    public function moneyReceipts()
    {
        return $this->hasMany(BackentMoneyReceipt::class, 'backend_bill_id', 'id');
    }




}
