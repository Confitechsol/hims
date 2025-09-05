<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrontCmsPage extends Model
{
    use HasFactory;

    protected $table = 'front_cms_pages';

    protected $fillable = [
        'hospital_id',
        'page_type',
        'is_homepage',
        'title',
        'url',
        'type',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keyword',
        'feature_image',
        'description',
        'publish_date',
        'publish',
        'sidebar',
        'is_active',
    ];
}
