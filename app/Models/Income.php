<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Income extends Model
{
    use HasFactory;

    protected $table = 'income';

    protected $fillable = [
        'hospital_id',
        'branch_id',
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
    public $timestamps = false;
    public function incomeHead(): BelongsTo{
        return $this->belongsTo(IncomeHead::class,"inc_head_id");
    }
}
