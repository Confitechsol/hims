<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AmbulanceCall extends Model
{
    use HasFactory;

    protected $table = 'ambulance_call';

    protected $fillable = [
        'patient_id',
        'case_reference_id',
        'vehicle_id',
        'contact_no',
        'address',
        'vehicle_model',
        'driver',
        'date',
        'call_from',
        'call_to',
        'charge_category_id',
        'charge_id',
        'standard_charge',
        'discount_percentage',
        'discount',
        'tax_percentage',
        'amount',
        'net_amount',
        'transaction_id',
        'note',
        'generated_by',
    ];

    protected $casts = [
        'date' => 'datetime',
        'discount_percentage' => 'float',
        'discount' => 'float',
        'tax_percentage' => 'float',
        'amount' => 'float',
        'net_amount' => 'float',
    ];

    // Relationships
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
