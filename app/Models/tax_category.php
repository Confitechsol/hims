<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxCategory extends Model
{
    use HasFactory;

    // Explicit table name
    protected $table = 'tax_category';

    // Primary key
    protected $primaryKey = 'id';

    // Only created_at exists, no updated_at
    public $timestamps = false;

    // Fillable fields
    protected $fillable = [
        'hospital_id',
        'name',
        'percentage',
        'created_at',
    ];

    // Casts
    protected $casts = [
        'percentage' => 'float',
        'created_at' => 'datetime',
    ];

    /**
     * Relationships
     * (Later, bills/invoices can reference tax_category_id → belongsTo)
     */
}
