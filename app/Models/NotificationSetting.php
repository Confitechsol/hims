<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    use HasFactory;

    protected $table = 'notification_setting';

    protected $fillable = [
        'hospital_id',
        'type',
        'is_mail',
        'is_sms',
        'is_mobileapp',
        'is_notification',
        'display_notification',
        'display_sms',
        'template',
        'template_id',
        'subject',
        'variables',
    ];
}
