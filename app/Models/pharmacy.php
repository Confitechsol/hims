<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    use HasFactory;

    protected $table = 'pharmacy';

    protected $fillable = [
        'medicine_name',
        'medicine_category_id',
        'medicine_image',
        'medicine_company',
        'medicine_composition',
        'medicine_group',
        'unit',
        'min_level',
        'reorder_level',
        'gst_percentage',
        'unit_packing',
        'rack_number',
        'note',
        'is_active',
    ];

    protected $casts = [
        'gst_percentage' => 'decimal:2',
    ];

    /**
     * Relationship: A medicine belongs to a medicine category
     */
    public function medicineCategory()
    {
        return $this->belongsTo(MedicineCategory::class, 'medicine_category_id');
    }

    /**
     * Relationship: A medicine belongs to a company
     */
    public function company()
    {
        return $this->belongsTo(PharmacyCompany::class, 'medicine_company');
    }

    /**
     * Relationship: A medicine belongs to a medicine group
     */
    public function medicineGroup()
    {
        return $this->belongsTo(MedicineGroup::class, 'medicine_group');
    }

    /**
     * Relationship: A medicine belongs to a unit
     */
    public function unitRelation()
    {
        return $this->belongsTo(Unit::class, 'unit');
    }

    /**
     * Relationship: A medicine has many batches
     */
    public function batches()
    {
        return $this->hasMany(MedicineBatchDetail::class, 'pharmacy_id');
    }

    /**
     * Get total available quantity across all batches
     */
    public function getTotalQuantityAttribute()
    {
        return $this->batches()->sum('available_quantity');
    }

    /**
     * Check if medicine is below minimum level
     */
    public function isBelowMinLevel()
    {
        return $this->total_quantity <= $this->min_level;
    }

    /**
     * Check if medicine needs reordering
     */
    public function needsReorder()
    {
        return $this->total_quantity <= $this->reorder_level;
    }

    /**
     * Scope: Get active medicines
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 'yes');
    }

    /**
     * Scope: Get medicines below min level
     */
    public function scopeBelowMinLevel($query)
    {
        return $query->whereHas('batches', function ($q) {
            $q->selectRaw('SUM(available_quantity) as total')
                ->groupBy('pharmacy_id')
                ->havingRaw('SUM(available_quantity) <= pharmacy.min_level');
        });
    }
}
