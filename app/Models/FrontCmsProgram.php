<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrontCmsProgram extends Model
{
    use HasFactory;

    protected $table = 'front_cms_programs';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'type',
        'slug',
        'url',
        'title',
        'date',
        'event_start',
        'event_end',
        'event_venue',
        'description',
        'is_active',
        'meta_title',
        'meta_description',
        'meta_keyword',
        'feature_image',
        'publish_date',
        'publish',
        'sidebar',
    ];

    /**
     * Relationships
     */
    public function photos()
    {
        return $this->hasMany(FrontCmsProgramPhoto::class, 'program_id');
    }
}
