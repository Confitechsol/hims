<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrontCmsSetting extends Model
{
    use HasFactory;

    protected $table = 'front_cms_settings';

    protected $fillable = [
        'hospital_id',
        'theme',
        'is_active_rtl',
        'is_active_front_cms',
        'is_active_online_appointment',
        'is_active_sidebar',
        'logo',
        'contact_us_email',
        'complain_form_email',
        'sidebar_options',
        'fb_url',
        'twitter_url',
        'youtube_url',
        'google_plus',
        'instagram_url',
        'pinterest_url',
        'linkedin_url',
        'google_analytics',
        'footer_text',
        'fav_icon',
    ];
}
