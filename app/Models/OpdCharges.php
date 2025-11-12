<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpdCharges extends Model
{
    use HasFactory;

    protected $table = 'opd_charges';

    protected $fillable = [
        'charge_id',
        'charge_type_id',
        'opd_id',
        'discount',
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
        return $this->belongsTo(ChargeCategory::class, 'charge_type_id', 'id');
    }
}
