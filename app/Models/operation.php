<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    protected $table = 'operation';

    public $timestamps = false;

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'operation',
        'category_id',
        'is_active',
    ];

    /**
     * Relation with OperationCategory
     */
    public function category()
    {
        return $this->belongsTo(OperationCategory::class, 'category_id');
    }
}
