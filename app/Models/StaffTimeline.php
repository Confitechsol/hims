<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffTimeline extends Model
{
    use HasFactory;

    // Explicit table name
    protected $table = 'staff_timeline';

    // Primary key
    protected $primaryKey = 'id';

    // Only created_at exists, no updated_at
    public $timestamps = false;

    // Fillable fields
    protected $fillable = [
        'hospital_id',
         'branch_id',
        'invoice_no',
        'date',
        'supplier_id',
        'file',
        'total',
        'tax',
        'discount',
        'net_amount',
        'note',
        'payment_mode',
        'cheque_no',
        'cheque_date',
        'payment_date',
        'received_by',
        'attachment',
        'attachment_name',
        'payment_note',
        'created_at',
    ];

    // Casts
    protected $casts = [
        'date' => 'datetime',
        'cheque_date' => 'date',
        'payment_date' => 'datetime',
        'total' => 'float',
        'tax' => 'float',
        'discount' => 'float',
        'net_amount' => 'float',
        'created_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function receivedBy()
    {
        return $this->belongsTo(Staff::class, 'received_by');
    }
}
