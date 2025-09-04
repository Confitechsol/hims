<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrontCmsMenuItem extends Model
{
    use HasFactory;

    protected $table = 'front_cms_menu_items';

    protected $fillable = [
        'hospital_id',
        'menu_id',
        'menu',
        'page_id',
        'parent_id',
        'ext_url',
        'open_new_tab',
        'ext_url_link',
        'slug',
        'weight',
        'publish',
        'description',
        'is_active',
    ];
}
