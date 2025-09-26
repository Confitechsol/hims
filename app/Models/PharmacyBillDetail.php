<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyBillDetail extends Model
{
    use HasFactory;

    protected $table = 'pharmacy_bill_detail';

    public $timestamps = false; // only created_at exists

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'pharmacy_bill_basic_id',
        'medicine_batch_detail_id',
        'quantity',
        'sale_price',
        'amount',
        'created_at',
    ];

    protected $casts = [
        'sale_price' => 'decimal:2',
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /*
     * Relationships
     */
    public function bill()
    {
        return $this->belongsTo(PharmacyBillBasic::class, 'pharmacy_bill_basic_id');
    }

    public function medicineBatchDetail()
    {
        return $this->belongsTo(MedicineBatchDetail::class, 'medicine_batch_detail_id');
    }
}
