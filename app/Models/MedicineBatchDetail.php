<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineBatchDetail extends Model
{
    use HasFactory;

    protected $table = 'medicine_batch_details';

    protected $fillable = [
        'pharmacy_id',
        'batch_no',
        'expiry',
        'packing_qty',
        'purchase_price',
        'sale_rate',
        'mrp',
        'quantity',
        'amount',
        'inward_date',
        'purchase_no',
    ];

    protected $casts = [
        'expiry' => 'date',
        'inward_date' => 'date',
        'purchase_price' => 'decimal:2',
        'sale_rate' => 'decimal:2',
        'mrp' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    /**
     * Relationship: A batch detail belongs to a pharmacy medicine
     */
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacy_id');
    }

    /**
     * Relationship: A batch detail has many bill details
     */
    public function billDetails()
    {
        return $this->hasMany(PharmacyBillDetail::class, 'medicine_batch_detail_id');
    }

    /**
     * Get available quantity (total - used)
     */
    public function getAvailableQuantityAttribute()
    {
        $usedQuantity = $this->billDetails()->sum('quantity');
        return max(0, $this->quantity - $usedQuantity);
    }

    /**
     * Check if batch is expired
     */
    public function getIsExpiredAttribute()
    {
        return $this->expiry && $this->expiry < now()->toDateString();
    }

    /**
     * Check if batch is expiring soon (within 30 days)
     */
    public function getIsExpiringSoonAttribute()
    {
        return $this->expiry && $this->expiry <= now()->addDays(30)->toDateString();
    }

    /**
     * Scope: Filter by pharmacy medicine
     */
    public function scopeForPharmacy($query, $pharmacyId)
    {
        return $query->where('pharmacy_id', $pharmacyId);
    }

    /**
     * Scope: Filter by batch number
     */
    public function scopeByBatch($query, $batchNo)
    {
        return $query->where('batch_no', $batchNo);
    }

    /**
     * Scope: Filter by expiry date
     */
    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiry', '<=', now()->addDays($days)->toDateString())
                    ->where('expiry', '>', now()->toDateString());
    }

    /**
     * Scope: Filter expired batches
     */
    public function scopeExpired($query)
    {
        return $query->where('expiry', '<', now()->toDateString());
    }

    /**
     * Scope: Filter by available quantity
     */
    public function scopeWithStock($query)
    {
        return $query->whereRaw('quantity > (
            SELECT COALESCE(SUM(quantity), 0) 
            FROM pharmacy_bill_detail 
            WHERE medicine_batch_detail_id = medicine_batch_details.id
        )');
    }
}