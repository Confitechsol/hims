<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    use HasFactory;

    protected $table = 'pharmacy';

    public $timestamps = false; // only created_at exists

    protected $fillable = [
        'hospital_id',
        'medicine_name',
        'medicine_category_id',
        'medicine_image',
        'medicine_company',
        'medicine_composition',
        'medicine_group',
        'unit',
        'min_level',
        'reorder_level',
        'vat',
        'unit_packing',
        'vat_ac',
        'rack_number',
        'note',
        'is_active',
        'created_at',
    ];

    protected $casts = [
        'vat' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /*
     * Relationships
     */
    public function category()
    {
        return $this->belongsTo(MedicineCategory::class, 'medicine_category_id');
    }
}
