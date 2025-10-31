<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierBillBasic extends Model
{
    use HasFactory;

    protected $table = 'supplier_bill_basic';

    protected $fillable = [
        'invoice_no',
        'date',
        'supplier_id',
        'file',
        'total',
        'tax',
        'discount',
        'net_amount',
        'note',
        'payment_mode',
        'cheque_no',
        'cheque_date',
        'payment_date',
        'received_by',
        'attachment',
        'attachment_name',
        'payment_note',
    ];

    protected $casts = [
        'date' => 'datetime',
        'cheque_date' => 'date',
        'payment_date' => 'datetime',
        'total' => 'float',
        'tax' => 'float',
        'discount' => 'float',
        'net_amount' => 'float',
    ];

    /**
     * Relationship: A bill belongs to a supplier
     */
    public function supplier()
    {
        return $this->belongsTo(MedicineSupplier::class, 'supplier_id');
    }

    /**
     * Relationship: A bill received by a user
     */
    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Relationship: A bill has many medicine batches
     */
    public function batches()
    {
        return $this->hasMany(MedicineBatchDetail::class, 'supplier_bill_basic_id');
    }

    /**
     * Check if bill is paid
     */
    public function isPaid()
    {
        return !empty($this->payment_mode) && !empty($this->payment_date);
    }

    /**
     * Scope: Get paid bills
     */
    public function scopePaid($query)
    {
        return $query->whereNotNull('payment_mode')
                     ->whereNotNull('payment_date');
    }

    /**
     * Scope: Get unpaid bills
     */
    public function scopeUnpaid($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('payment_mode')
              ->orWhereNull('payment_date');
        });
    }

    /**
     * Scope: Filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
}
