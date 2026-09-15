<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackendBillItem extends Model
{
    //
    protected $table='backend_bill_item';



    protected $fillable = [
        'bill_item',
        'amount',
        'patient_id',
        'case_no',
    ];




}