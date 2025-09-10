<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentPayment extends Model
{
    use HasFactory;

    protected $table = 'appointment_payment';

    protected $fillable = [
        'hospital_id',
        'appointment_id',
        'charge_id',
        'standard_amount',
        'tax',
        'discount_percentage',
        'paid_amount',
        'payment_mode',
        'payment_type',
        'transaction_id',
        'note',
        'date',
    ];

    protected $casts = [
        'standard_amount' => 'float',
        'tax' => 'float',
        'discount_percentage' => 'float',
        'paid_amount' => 'float',
        'date' => 'datetime',
    ];

    // Relationships
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    public function charge(): BelongsTo
    {
        return $this->belongsTo(Charge::class, 'charge_id');
    }
}
