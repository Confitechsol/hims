<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointPriority extends Model
{
    use HasFactory;

    protected $table = 'appoint_priority';

    protected $fillable = [
        'hospital_id',
        'appoint_priority',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
