<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FindingCategory extends Model
{
    use HasFactory;

    protected $table = 'finding_category';

    protected $fillable = [
        'hospital_id',
        'category',
    ];

    /**
     * Relationship with Finding
     */
    public function findings()
    {
        return $this->hasMany(Finding::class, 'finding_category_id');
    }
}
