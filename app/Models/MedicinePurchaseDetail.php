<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicinePurchaseDetail extends Model
{
    use HasFactory;

    protected $table = 'medicine_purchase_details';

    protected $fillable = [
        'medicine_purchase_id',
        'medicine_id',
        'inward_date',
        'batch_no',
        'expiry_date',
        'mrp',
        'sale_price',
        'purchase_price',
        'quantity',
        'available_quantity',
        'tax_percentage',
        'amount',
        'batch_amount',
        'packing_qty',
    ];

    protected $casts = [
        'inward_date' => 'datetime',
        'expiry_date' => 'date',
        'mrp' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'amount' => 'decimal:2',
        'batch_amount' => 'decimal:2',
        'quantity' => 'integer',
        'available_quantity' => 'integer',
    ];

    /**
     * Relationship: Purchase detail belongs to a purchase
     */
    public function medicinePurchase()
    {
        return $this->belongsTo(MedicinePurchase::class, 'medicine_purchase_id');
    }

    /**
     * Relationship: Purchase detail belongs to a medicine
     */
    public function medicine()
    {
        return $this->belongsTo(Pharmacy::class, 'medicine_id');
    }
}
