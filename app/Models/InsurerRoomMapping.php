<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsurerRoomMapping extends Model
{
    protected $fillable = [
        'insurance_rate_panel_id',
        'insurance_company_id',
        'insurer_room_code',
        'bed_group_id',
        'label',
    ];

    public function ratePanel()
    {
        return $this->belongsTo(InsuranceRatePanel::class, 'insurance_rate_panel_id');
    }

    public function insuranceCompany()
    {
        return $this->belongsTo(InsuranceCompany::class, 'insurance_company_id');
    }

    public function bedGroup()
    {
        return $this->belongsTo(BedGroup::class, 'bed_group_id');
    }
}
