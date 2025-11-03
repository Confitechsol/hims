<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prefix extends Model
{
    use HasFactory;

    protected $table = 'prefixes';
    public $timestamps = false;  

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'type',
        'prefix',

    ];
}
