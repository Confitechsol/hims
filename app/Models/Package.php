<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    public const TYPE_HOSPITAL = 'hospital';
    public const TYPE_INSURANCE = 'insurance';

    protected $table = 'packages';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'package_type',
        'insurance_company_id',
        'insurance_rate_panel_id',
        'insurer_procedure_code',
        'speciality',
        'room_eligibility',
        'name',
        'account_head',
        'gst_amount',
        'package_rate',
        'description',
        'inclusion_notes',
        'effective_from',
        'effective_to',
        'contract_reference',
        'status',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'gst_amount' => 'decimal:2',
        'package_rate' => 'decimal:2',
        'is_active' => 'boolean',
        'effective_from' => 'date',
        'effective_to' => 'date',
    ];

    public function isInsurance(): bool
    {
        return $this->package_type === self::TYPE_INSURANCE;
    }

    public function charges()
    {
        return $this->hasMany(PackageCharge::class)->orderBy('display_order');
    }

    public function excludes()
    {
        return $this->hasMany(PackageExclude::class);
    }

    public function roomRates()
    {
        return $this->hasMany(PackageRoomRate::class)->orderBy('bed_group_id');
    }

    public function ipdPackages()
    {
        return $this->hasMany(IpdPackage::class);
    }

    public function insuranceCompany()
    {
        return $this->belongsTo(InsuranceCompany::class, 'insurance_company_id');
    }

    public function insuranceRatePanel()
    {
        return $this->belongsTo(InsuranceRatePanel::class, 'insurance_rate_panel_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->where('is_active', true)->orWhereNull('is_active');
        })->where(function ($q) {
            $q->where('status', 'active')->orWhereNull('status');
        });
    }

    public function scopeForInsuranceContext($query, ?int $insuranceCompanyId, ?int $panelId = null)
    {
        return $query->where(function ($q) use ($insuranceCompanyId, $panelId) {
            $q->where('package_type', self::TYPE_HOSPITAL);

            if ($insuranceCompanyId || $panelId) {
                $q->orWhere(function ($ins) use ($insuranceCompanyId, $panelId) {
                    $ins->where('package_type', self::TYPE_INSURANCE);

                    if ($panelId) {
                        $ins->where('insurance_rate_panel_id', $panelId);
                    } elseif ($insuranceCompanyId) {
                        $ins->where('insurance_company_id', $insuranceCompanyId);
                    }
                });
            }
        });
    }
}
