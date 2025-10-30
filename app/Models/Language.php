<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $table = 'languages';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'language',
        'short_code',
        'country_code',
        'is_deleted',
        'is_rtl',
        'is_active',
    ];
}
