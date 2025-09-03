<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DispatchReceive extends Model
{
    use HasFactory;

    protected $table = 'dispatch_receive';

    protected $fillable = [
        'hospital_id',
    ];

    
}
