<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineBadStock extends Model
{
    use HasFactory;

    protected $table = 'medicine_bad_stock';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'medicine_batch_details_id',
        'pharmacy_id',
        'outward_date',
        'expiry_date',
        'batch_no',
        'quantity',
        'note',
    ];

    /**
     * Relations
     */
    public function medicineBatchDetails()
    {
        return $this->belongsTo(MedicineBatchDetail::class, 'medicine_batch_details_id');
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacy_id');
    }
}
