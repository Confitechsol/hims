<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationRole extends Model
{
    use HasFactory;

    protected $table = 'notification_roles';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'send_notification_id',
        'role_id',
        'is_active',
    ];

    /**
     * Relationships
     */

    // If you have a SendNotification model
    public function sendNotification()
    {
        return $this->belongsTo(SendNotification::class, 'send_notification_id');
    }

    // If you have a Role model
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
