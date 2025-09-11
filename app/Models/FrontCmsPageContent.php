<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrontCmsPageContent extends Model
{
    use HasFactory;

    protected $table = 'front_cms_page_contents';

    protected $fillable = [
        'hospital_id',
        'page_id',
        'content_type',
    ];
}
