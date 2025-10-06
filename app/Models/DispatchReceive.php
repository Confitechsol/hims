<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DispatchReceive extends Model
{
    use HasFactory;

    // Table name (explicit since it doesn’t follow plural convention)
    protected $table = 'dispatch_receive';

    // Primary key
    protected $primaryKey = 'id';

    // Mass assignable fields
    protected $fillable = [
        'hospital_id',
        'branch_id',
        'reference_no',
        'to_title',
        'address',
        'note',
        'from_title',
        'date',
        'image',
        'type',
    ];

    // Casts
    protected $casts = [
        'date' => 'date',
    ];
}
