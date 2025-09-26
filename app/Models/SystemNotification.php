<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemNotification extends Model
{
    use HasFactory;

    // Explicit table name
    protected $table = 'system_notification';

    // Primary key
    protected $primaryKey = 'id';

    // Only created_at exists, no updated_at
    public $timestamps = false;

    // Fillable fields
    protected $fillable = [
        'hospital_id',
         'branch_id',
        'notification_title',
        'notification_type',
        'notification_desc',
        'notification_for',
        'role_id',
        'receiver_id',
        'date',
        'is_active',
        'created_at',
    ];

    // Casts
    protected $casts = [
        'date' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function receiver()
    {
        // Assuming receiver_id links to staff/users
        return $this->belongsTo(Staff::class, 'receiver_id');
    }
}
