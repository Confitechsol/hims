<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'item';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'item_category_id',
        'name',
        'unit',
        'item_photo',
        'description',
        'quantity',
        'date',
    ];

    /**
     * Relationship: An item belongs to a category
     */
    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id');
    }
}
