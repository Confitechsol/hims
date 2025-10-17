<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemStockBatches extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_stock_id',
        'batch_no',
        'serial_no',
        'salvage_value',
        'useful_life',
        'annual_depreciation',
        'expiry_date',
    ];

    // Relationship back to ItemStock
    public function stock()
    {
        return $this->belongsTo(ItemStock::class, 'item_stock_id');
    }
}
