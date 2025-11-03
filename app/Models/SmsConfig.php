<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsConfig extends Model
{
    use HasFactory;

    protected $table = 'sms_config';

    protected $fillable = [
        'hospital_id',
         'branch_id',
        'type',
        'name',
        'api_id',
        'authkey',
        'senderid',
        'contact',
        'username',
        'url',
        'password',
        'is_active',
    ];
}
