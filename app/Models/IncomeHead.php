<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeHead extends Model
{
    use HasFactory;

    protected $table = 'income_head';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'income_category',
        'description',
        'is_active',
        'is_deleted',
    ];

    /**
     * Relation: An income head can have many incomes.
     */
    public function incomes()
    {
        return $this->hasMany(Income::class, 'inc_head_id');
    }
}
