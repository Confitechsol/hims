<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Captcha extends Model
{
    use HasFactory;

    protected $table = 'captcha';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'name',
        'status',
    ];

    // created_at is present, but not updated_at → disable timestamps
    public $timestamps = false;

    protected $casts = [
        'status' => 'integer',
        'created_at' => 'datetime',
    ];
}
