<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyBillBasic extends Model
{
    use HasFactory;

    protected $table = 'pharmacy_bill_basic';

    public $timestamps = false; // only created_at exists

    protected $fillable = [
        'date',
        'patient_id',
        'ipd_prescription_basic_id',
        'case_reference_id',
        'customer_name',
        'customer_type',
        'doctor_name',
        'file',
        'total',
        'discount_percentage',
        'discount',
        'tax_percentage',
        'tax',
        'net_amount',
        'note',
        'generated_by',
        'created_at',
    ];

    protected $casts = [
        'date' => 'datetime',
        'total' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'tax' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /*
     * Relationships
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function prescription()
    {
        return $this->belongsTo(IpdPrescriptionBasic::class, 'ipd_prescription_basic_id');
    }

    public function caseReference()
    {
        return $this->belongsTo(CaseReference::class, 'case_reference_id');
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
