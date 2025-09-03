<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class case_reference extends Model
{
    use HasFactory;

    protected $table = 'case_references';

    protected $fillable = [
        'hospital_id',
        'bill_id',
        'discount_percentage',
    ];

    // only created_at exists (no updated_at)
    public $timestamps = false;

    protected $casts = [
        'bill_id' => 'integer',
        'discount_percentage' => 'float',
        'created_at' => 'datetime',
    ];
}
