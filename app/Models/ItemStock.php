<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemStock extends Model
{
    use HasFactory;

    protected $table = 'item_stock';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'item_category_id',
        'item_id',
        'supplier_id',
        'symbol',
        'store_id',
        'quantity',
        'purchase_price',
        'date',
        'expiry_date',
        'salvage_value',
        'useful_life',
        'annual_depreciation',
        'attachment',
        'description',
        'is_active',
    ];


    /**
     * Relationship: ItemStock belongs to Item
     */
    public function itemCategory()
{
    return $this->belongsTo(ItemCategory::class, 'item_category_id');
}
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    /**
     * Relationship: ItemStock belongs to Supplier
     */
    public function supplier()
    {
        return $this->belongsTo(ItemSupplier::class, 'supplier_id');
    }

    /**
     * Relationship: ItemStock belongs to Store
     */
    public function store()
    {
        return $this->belongsTo(ItemStore::class, 'store_id');
    }
}
