<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrontCmsMenu extends Model
{
    use HasFactory;

    protected $table = 'front_cms_menus';

    protected $fillable = [
        'hospital_id',
        'menu',
        'slug',
        'description',
        'open_new_tab',
        'ext_url',
        'ext_url_link',
        'publish',
        'content_type',
        'is_active',
    ];
}
