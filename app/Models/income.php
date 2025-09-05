<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $table = 'income';

    protected $fillable = [
        'hospital_id',
        'inc_head_id',
        'name',
        'invoice_no',
        'date',
        'amount',
        'note',
        'is_deleted',
        'documents',
        'generated_by',
        'is_active',
    ];
}
