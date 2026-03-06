<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IcdCode extends Model
{
    //
    protected $table = 'icd_code';
    protected $fillable = [
        'code',
        'diseases',
        'is_active',
    ];


}
