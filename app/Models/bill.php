<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bill extends Model
{
    use HasFactory;

    protected $table = 'bill';

    protected $fillable = [
        'hospital_id',
        'case_id',
        'attachment',
        'attachment_name',
        'amount',
        'payment_mode',
        'cheque_no',
        'cheque_date',
        'payment_date',
        'note',
        'received_by',
    ];

    protected $casts = [
        'cheque_date' => 'date',
        'payment_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function case(): BelongsTo
    {
        return $this->belongsTo(CaseModel::class, 'case_id'); // Assuming the model is CaseModel
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
