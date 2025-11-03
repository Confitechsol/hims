<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChargeTypeMaster;
use App\Models\Charge;
class ChargeCategory extends Model
{
    use HasFactory;

    protected $table = 'charge_categories';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'charge_type_id',
        'name',
        'description',
        'short_code',
        'is_default',
    ];

    public $timestamps = false; // only created_at exists (no updated_at)

    protected $casts = [
        'charge_type_id' => 'integer',
        'created_at' => 'datetime',
    ];

    public function chargeType()
    {
        return $this->belongsTo(ChargeTypeMaster::class, 'charge_type_id');
    }
    public function charge()
    {
        return $this->hasOne(Charge::class, 'charge_category_id');
    }
}
