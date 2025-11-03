<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailConfig extends Model
{
    use HasFactory;

    protected $table = 'email_config';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'email_type',
        'smtp_server',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'ssl_tls',
        'smtp_auth',
        'is_active',
    ];
}
