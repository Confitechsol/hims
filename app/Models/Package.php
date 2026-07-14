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
        'linked_hospital_package_id',
        'insurer_procedure_code',
        'speciality',
        'package_inclusions',
        'package_exclusions',
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
        return $this->package_type === self::TYPE_INSURANCE
            || ! empty($this->insurance_company_id);
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

    public function linkedHospitalPackage()
    {
        return $this->belongsTo(Package::class, 'linked_hospital_package_id');
    }

    public function insurancePackagesLinkedHere()
    {
        return $this->hasMany(Package::class, 'linked_hospital_package_id');
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
        $panelIds = collect();

        if ($panelId) {
            $panelIds = collect([(int) $panelId]);
        } elseif ($insuranceCompanyId) {
            $panelIds = \Illuminate\Support\Facades\DB::table('insurance_company_panel')
                ->where('insurance_company_id', (int) $insuranceCompanyId)
                ->pluck('insurance_rate_panel_id');
        }

        return $query->where(function ($q) use ($insuranceCompanyId, $panelIds) {
            $q->where('package_type', self::TYPE_HOSPITAL);

            if ($insuranceCompanyId || $panelIds->isNotEmpty()) {
                $q->orWhere(function ($ins) use ($insuranceCompanyId, $panelIds) {
                    $ins->where('package_type', self::TYPE_INSURANCE);

                    if ($panelIds->isNotEmpty()) {
                        $ins->where(function ($panelQ) use ($insuranceCompanyId, $panelIds) {
                            $panelQ->whereIn('insurance_rate_panel_id', $panelIds);
                            if ($insuranceCompanyId) {
                                $panelQ->orWhere('insurance_company_id', (int) $insuranceCompanyId);
                            }
                        });
                    } elseif ($insuranceCompanyId) {
                        $ins->where('insurance_company_id', (int) $insuranceCompanyId);
                    }
                });
            }
        });
    }

    /**
     * IPD admission: insurance package rates apply when an insurer is selected (independent of TPA).
     */
    public static function resolveIpdInsurancePackageContext(?int $organisationId, ?int $insuranceCompanyId): ?int
    {
        return $insuranceCompanyId ? (int) $insuranceCompanyId : null;
    }
}
