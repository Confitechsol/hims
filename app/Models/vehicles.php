<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $table = 'vehicles';
    protected $primaryKey = 'id';
    public $timestamps = false; // only created_at is present, no updated_at

    protected $fillable = [
        'hospital_id',
        'vehicle_no',
        'vehicle_model',
        'manufacture_year',
        'vehicle_type',
        'driver_name',
        'driver_licence',
        'driver_contact',
        'note',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
