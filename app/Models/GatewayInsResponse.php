<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GatewayInsResponse extends Model
{
    use HasFactory;

    protected $table = 'gateway_ins_response';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'gateway_ins_id',
        'posted_data',
        'response',
    ];

    /**
     * Relationships
     */
    public function gatewayIn()
    {
        return $this->belongsTo(GatewayIn::class, 'gateway_ins_id');
    }
}
