<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimaryExamine extends Model
{
    use HasFactory;

    protected $table = 'primary_examine';

    protected $fillable = [
        'hospital_id',
    ];
}
