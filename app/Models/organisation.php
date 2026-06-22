<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organisation extends Model
{
    use HasFactory;

    protected $table = 'organisation';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'insurance_company_id',
        'organisation_name',
        'code',
        'contact_no',
        'address',
        'contact_person_name',
        'contact_person_phone',
        'poilicy_no',
        'e_card_no',
        'e_card_upload',
    ];

    public $timestamps = false;

    /**
     * Primary / default insurance company (legacy column on organisation).
     */
    public function insuranceCompany()
    {
        return $this->belongsTo(InsuranceCompany::class, 'insurance_company_id');
    }

    /**
     * All insurance companies linked to this TPA (many-to-many).
     */
    public function insuranceCompanies()
    {
        return $this->belongsToMany(
            InsuranceCompany::class,
            'insurance_company_organisation',
            'organisation_id',
            'insurance_company_id'
        )->withTimestamps();
    }

    public function syncPrimaryInsuranceCompany(): void
    {
        if (!$this->exists) {
            return;
        }

        $firstId = $this->insuranceCompanies()->orderBy('insurance_companies.id')->value('insurance_companies.id');
        $this->update(['insurance_company_id' => $firstId]);
    }
}
