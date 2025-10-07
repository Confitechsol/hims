<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadSystemNotification extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'read_systemnotification';

    // Primary key
    protected $primaryKey = 'id';

    // Fillable fields
    protected $fillable = [
        'hospital_id',
        'branch_id',
        'notification_id',
        'receiver_id',
        'is_active',
    ];

    // Relationships
    // Uncomment when Notifications model exists
    // public function notification()
    // {
    //     return $this->belongsTo(Notification::class, 'notification_id');
    // }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
