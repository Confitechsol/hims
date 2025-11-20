<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpdCharges extends Model
{
    use HasFactory;

    protected $table = 'ipd_charges';

    protected $fillable = [
        'ipd_id',
        'charge_type_id',
        'charge_category_id',
        'charge_id',
        'standard_charge',
        'tpa_charge',
        'qty',
        'total',
        'discount_percentage',
        'tax',
        'net_amount',
        'charge_note',
        'date',
    ];

    public function charge()
    {
        return $this->belongsTo(Charge::class);
    }

    public function ipd()
    {
        return $this->belongsTo(IpdDetail::class);
    }
    public function chargeCategory()
    {
        return $this->belongsTo(ChargeCategory::class, 'charge_category_id', 'id');
    }
    public function chargeType()
    {
        return $this->belongsTo(ChargeTypeMaster::class, 'charge_type_master', 'id');
    }
}