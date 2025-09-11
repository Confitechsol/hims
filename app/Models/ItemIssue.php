<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemIssue extends Model
{
    use HasFactory;

    protected $table = 'item_issue';

    protected $fillable = [
        'hospital_id',
        'issue_type',
        'issue_to',
        'issue_by',
        'issue_date',
        'return_date',
        'item_category_id',
        'item_id',
        'quantity',
        'note',
        'is_returned',
        'is_active',
    ];

    /**
     * Relationship: ItemIssue belongs to ItemCategory
     */
    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id');
    }

    /**
     * Relationship: ItemIssue belongs to Item
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
