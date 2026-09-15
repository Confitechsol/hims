<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackentMoneyReceipt extends Model
{
    //

    protected $table='backend_money_receipt';

    protected $fillable = [
        'backend_bill_id',
        'case_no',
        'patient_name',
        'age',
        'receipt_no',
        'receipt_type',
        'received_amount',
        'payment_mode',
        'payment_details',
        'received_by',
        'received_date',
    ];
}
