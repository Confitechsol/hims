<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchSetting extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'sch_settings';

    // Mass assignable attributes
    protected $fillable = [
        'hospital_id',
        'branch_id',
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
        'theme',
        'credit_limit',
        'opd_record_month',
        'is_active',
        'cron_secret_key',
        'doctor_restriction',
        'superadmin_restriction',
        'patient_panel',
        'scan_code_type',
        'mobile_api_url',
        'app_primary_color_code',
        'app_secondary_color_code',
        'app_logo',
        'zoom_api_key',
        'zoom_api_secret',
    ];
}
