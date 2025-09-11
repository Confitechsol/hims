<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostnatalExamine extends Model
{
    use HasFactory;

    protected $table = 'postnatal_examine';

    protected $fillable = [
        'hospital_id',
    ];
}
