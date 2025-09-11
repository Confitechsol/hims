<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    use HasFactory;

    protected $table = 'payment_settings';

    public $timestamps = false; // only created_at is defined

    protected $fillable = [
        'hospital_id',
        'payment_type',
        'api_username',
        'api_secret_key',
        'salt',
        'api_publishable_key',
        'paytm_website',
        'paytm_industrytype',
        'api_password',
        'api_signature',
        'api_email',
        'paypal_demo',
        'account_no',
        'is_active',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
