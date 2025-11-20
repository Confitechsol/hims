<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpdCharges extends Model
{
    use HasFactory;

    protected $table = 'opd_charges';

    protected $fillable = [
        'opd_id',
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

    public function opd()
    {
        return $this->belongsTo(OpdDetail::class);
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