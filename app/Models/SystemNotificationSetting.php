<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemNotificationSetting extends Model
{
    use HasFactory;

    // Explicit table name
    protected $table = 'system_notification_setting';

    // Primary key
    protected $primaryKey = 'id';

    // Only created_at exists, no updated_at
    public $timestamps = false;

    // Fillable fields
    protected $fillable = [
        'hospital_id',
         'branch_id',
        'event',
        'subject',
        'staff_message',
        'is_staff',
        'patient_message',
        'is_patient',
        'variables',
        'url',
        'patient_url',
        'notification_type',
        'is_active',
        'created_at',
    ];

    // Casts
    protected $casts = [
        'is_staff' => 'boolean',
        'is_patient' => 'boolean',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * Relationships
     
     */
}
