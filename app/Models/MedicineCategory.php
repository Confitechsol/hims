<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineCategory extends Model
{
    use HasFactory;

    protected $table = 'medicine_category';
    public $timestamps = false;
    protected $fillable = [
        'medicine_category',
    ];

    /**
     * Relations
     */
    public function medicines()
    {
        // return $this->hasMany(Medicine::class, 'medicine_category_id');
    }
}
