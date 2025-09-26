<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GatewayIn extends Model
{
    use HasFactory;

    protected $table = 'gateway_ins';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'online_appointment_id',
        'type',
        'gateway_name',
        'module_type',
        'unique_id',
        'parameter_details',
        'payment_status',
    ];

    /**
     * Relationships
     */
    public function onlineAppointment()
    {
        return $this->belongsTo(OnlineAppointment::class, 'online_appointment_id');
    }
}
