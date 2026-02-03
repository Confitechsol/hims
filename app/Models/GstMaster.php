<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GstMaster extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gst_master';

    protected $fillable = [
        'code',
        'description',
        'gst_rate',
    ];

    protected $casts = [
        'gst_rate' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
