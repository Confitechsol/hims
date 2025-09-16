<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    protected $table = 'hospital'; // since you used singular table name

    protected $fillable = [
        'hospital_id',
        'base_url',
        'folder_path',
        'name',
        'biometric',
        'biometric_device',
        'email',
        'phone',
        'address',
        'start_month',
        'session_id',
        'lang_id',
        'languages',
        'dise_code',
        'date_format',
        'time_format',
        'currency',
        'currency_symbol',
        'is_rtl',
        'timezone',
        'image',
        'mini_logo',
        'credit_limit',
        'opd_record_month',
        'is_active',
        'cron_secret_key',
        'doctor_restriction',
        'superadmin_restriction',
        'patient_panel',
        'scan_code_type',
        'mobile_api_url',
    ];

    // 🔗 Relationships
    public function branches()
    {
        return $this->hasMany(HospitalBranch::class, 'hospital_id');
    }

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'lang_id');
    }
}
