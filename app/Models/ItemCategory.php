<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    use HasFactory;

    protected $table = 'item_category';

    protected $fillable = [
        'hospital_id',
        'item_category',
        'is_active',
        'item_head',
        'description',
    ];
}