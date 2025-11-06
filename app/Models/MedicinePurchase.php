<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicinePurchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'medicine_purchase';

    protected $fillable = [
        'supplier_id',
        'purchase_date',
        'invoice_no',
        'total',
        'discount',
        'tax',
        'net_amount',
        'payment_mode',
        'payment_date',
        'payment_amount',
        'cheque_no',
        'cheque_date',
        'payment_note',
        'note',
        'attachment',
        'attachment_name',
        'received_by',
    ];

    protected $casts = [
        'purchase_date' => 'datetime',
        'payment_date' => 'datetime',
        'cheque_date' => 'date',
        'total' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'payment_amount' => 'decimal:2',
    ];

    /**
     * Relationship: Purchase belongs to a supplier
     */
    public function supplier()
    {
        return $this->belongsTo(MedicineSupplier::class, 'supplier_id');
    }

    /**
     * Relationship: Purchase has many purchase details (batches)
     */
    public function purchaseDetails()
    {
        return $this->hasMany(MedicinePurchaseDetail::class, 'medicine_purchase_id');
    }

    /**
     * Relationship: Purchase received by staff
     */
    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Get formatted purchase number
     */
    public function getPurchaseNumberAttribute()
    {
        return 'PHPN' . $this->id;
    }
}
