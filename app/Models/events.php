<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'hospital_id',
        'event_title',
        'event_description',
        'start_date',
        'end_date',
        'event_type',
        'event_color',
        'event_for',
        'role_id',
        'is_active',
    ];

    /**
     * Relationship with Role (many events can belong to one role).
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
