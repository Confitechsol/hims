<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomSetting extends Model
{
    use HasFactory;

    protected $table = 'zoom_settings';

    public $timestamps = true; // Laravel will manage created_at automatically

    protected $fillable = [
        'hospital_id',
        'zoom_api_key',
        'zoom_api_secret',
        'use_doctor_api',
        'use_zoom_app',
        'opd_duration',
        'ipd_duration',
    ];

    protected $casts = [
        'use_doctor_api' => 'boolean',
        'use_zoom_app' => 'boolean',
        'opd_duration' => 'integer',
        'ipd_duration' => 'integer',
        'created_at' => 'datetime',
    ];
}
