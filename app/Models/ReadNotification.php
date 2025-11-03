<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadNotification extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'read_notification';

    // Primary key
    protected $primaryKey = 'id';

    // Fillable fields
    protected $fillable = [
        'hospital_id',
        'branch_id',
        'staff_id',
        'notification_id',
        'is_active',
    ];

    // Relationships
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    // Uncomment when Notifications model exists
    // public function notification()
    // {
    //     return $this->belongsTo(Notification::class, 'notification_id');
    // }
}
