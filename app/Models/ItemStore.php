<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemStore extends Model
{
    use HasFactory;

    protected $table = 'item_store';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'item_store',
        'code',
    ];

    /**
     * Relationship: An ItemStore can have many ItemStocks
     */
    public function stocks()
    {
        return $this->hasMany(ItemStock::class, 'store_id');
    }
}
