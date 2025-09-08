<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendNotification extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'send_notification';

    // Mass assignable attributes
    protected $fillable = [
        'hospital_id',
        'title',
        'publish_date',
        'date',
        'message',
        'visible_staff',
        'visible_patient',
        'created_by',
        'created_id',
        'is_active',
    ];
}
