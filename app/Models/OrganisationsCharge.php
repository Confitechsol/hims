<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganisationsCharge extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'organisations_charges';
    public $timestamps = false;
    protected $fillable = [
        'hospital_id',
        'branch_id',
        'org_id',
        'charge_id', // Keep for backward compatibility with other modules
        'pathology_id', // For pathology TPA charges
        'radiology_id', // For radiology TPA charges
        'charge_type', // 'IPD' or 'OPD' for pathology/radiology charges
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
     * Relationship with Charge (for backward compatibility)
     */
    public function charge()
    {
        return $this->belongsTo(Charge::class, 'charge_id');
    }

    /**
     * Relationship with Pathology
     */
    public function pathology()
    {
        return $this->belongsTo(Pathology::class, 'pathology_id');
    }

    /**
     * Relationship with Radiology
     */
    public function radiology()
    {
        return $this->belongsTo(Radio::class, 'radiology_id');
    }
}
