<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationCategory extends Model
{
    use HasFactory;

    protected $table = 'operation_category';

    protected $fillable = [
        'hospital_id',
        'category',
        'is_active',
    ];
}
