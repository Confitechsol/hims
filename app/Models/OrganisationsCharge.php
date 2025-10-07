<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganisationsCharge extends Model
{
    use HasFactory;

    protected $table = 'organisations_charges';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'org_id',
        'charge_id',
        'org_charge',
    ];

    /**
     * Relationship with Organisation
     */
    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'org_id');
    }

    /**
     * Relationship with Charge
     */
    public function charge()
    {
        return $this->belongsTo(Charge::class, 'charge_id');
    }
}
