<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineBatchDetail extends Model
{
    use HasFactory;

    protected $table = 'medicine_batch_details';

    protected $fillable = [
        'hospital_id',
        'supplier_bill_
        'branch_id',basic_id',
        'pharmacy_id',
        'inward_date',
        'expiry',
        'batch_no',
        'packing_qty',
        'purchase_rate_packing',
        'quantity',
        'mrp',
        'purchase_price',
        'tax',
        'sale_rate',
        'batch_amount',
        'amount',
        'available_quantity',
    ];

    /**
     * Relations
     */
    public function supplierBillBasic()
    {
        return $this->belongsTo(SupplierBillBasic::class, 'supplier_bill_basic_id');
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacy_id');
    }

    public function badStocks()
    {
        return $this->hasMany(MedicineBadStock::class, 'medicine_batch_details_id');
    }
}
