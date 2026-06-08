<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsuranceTestRate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'insurance_rate_panel_id',
        'test_type',
        'pathology_id',
        'radiology_id',
        'hospital_system_name',
        'insurer_test_name',
        'rate',
        'mapping_status',
        'effective_from',
        'effective_to',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'effective_from' => 'date',
        'effective_to' => 'date',
    ];

    public function panel()
    {
        return $this->belongsTo(InsuranceRatePanel::class, 'insurance_rate_panel_id');
    }

    public function pathology()
    {
        return $this->belongsTo(Pathology::class, 'pathology_id');
    }

    public function radiology()
    {
        return $this->belongsTo(Radio::class, 'radiology_id');
    }

    public function canonicalTestName(): ?string
    {
        if ($this->test_type === 'pathology' && $this->pathology) {
            return $this->pathology->test_name;
        }
        if ($this->test_type === 'radiology' && $this->radiology) {
            return $this->radiology->test_name;
        }

        return null;
    }
}
