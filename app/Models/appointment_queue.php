<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentQueue extends Model
{
    use HasFactory;

    protected $table = 'appointment_queue';

    protected $fillable = [
        'appointment_id',
        'staff_id',
        'position',
        'shift_id',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
        'position' => 'integer',
    ];

    // Relationships
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }
}
