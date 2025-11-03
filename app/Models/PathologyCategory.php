<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PathologyCategory extends Model
{
    use HasFactory;

    protected $table = 'pathology_category';
    public $timestamps = false; 

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'category_name',
    ];

    /**
     * Relationship with Pathology
     */
    public function pathologies()
    {
        return $this->hasMany(Pathology::class, 'pathology_category_id');
    }
}
