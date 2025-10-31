<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyBillDetail extends Model
{
    use HasFactory;

    protected $table = 'pharmacy_bill_detail';

    protected $fillable = [
        'pharmacy_bill_basic_id',
        'medicine_batch_detail_id',
        'quantity',
        'sale_price',
        'amount',
    ];

    protected $casts = [
        'sale_price' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    /**
     * Relationship: A bill detail belongs to a pharmacy bill
     */
    public function pharmacyBill()
    {
        return $this->belongsTo(PharmacyBillBasic::class, 'pharmacy_bill_basic_id');
    }

    /**
     * Relationship: A bill detail belongs to a medicine batch
     */
    public function medicineBatch()
    {
        return $this->belongsTo(MedicineBatchDetail::class, 'medicine_batch_detail_id');
    }

    /**
     * Get medicine details through batch
     */
    public function medicine()
    {
        return $this->hasOneThrough(
            Pharmacy::class,
            MedicineBatchDetail::class,
            'id',
            'id',
            'medicine_batch_detail_id',
            'pharmacy_id'
        );
    }

    /**
     * Calculate amount based on quantity and sale price
     */
    public function calculateAmount()
    {
        return $this->quantity * $this->sale_price;
    }

    /**
     * Scope: Filter by bill
     */
    public function scopeForBill($query, $billId)
    {
        return $query->where('pharmacy_bill_basic_id', $billId);
    }
}