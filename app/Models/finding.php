<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finding extends Model
{
    use HasFactory;

    protected $table = 'finding';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'name',
        'description',
        'finding_category_id',
    ];

    /**
     * Relationship with FindingCategory
     */
    public function category()
    {
        return $this->belongsTo(FindingCategory::class, 'finding_category_id');
    }
}
