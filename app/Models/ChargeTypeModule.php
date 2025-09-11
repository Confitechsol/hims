<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChargeTypeModule extends Model
{
    use HasFactory;

    protected $table = 'charge_type_module';

    protected $fillable = [
        'hospital_id',
        'charge_type_master_id',
        'module_shortcode',
    ];

    public $timestamps = false; // only created_at exists

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Relationship: Each ChargeTypeModule belongs to a ChargeTypeMaster.
     */
    public function chargeTypeMaster()
    {
        return $this->belongsTo(ChargeTypeMaster::class, 'charge_type_master_id');
    }
}
