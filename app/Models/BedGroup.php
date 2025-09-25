<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BedGroup extends Model
{
    use HasFactory;

    protected $table = 'bed_group';
    public $timestamps = false; // since only created_at exists and no updated_at

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'name',
        'color',
        'description',
        'floor',
        'bed_cost',
        'is_active',
    ];

    protected $casts = [
        'bed_cost' => 'decimal:2',
        'is_active' => 'boolean',
        'floor' => 'integer',
    ];
}
