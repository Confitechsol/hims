<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseHead extends Model
{
    use HasFactory;

    protected $table = 'expense_head';
    public $timestamps = false;

    protected $fillable = [
        'hospital_id',
        'exp_category',
        'description',
        'is_active',
        'is_deleted',
    ];
}
