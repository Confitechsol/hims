<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrontCmsProgramPhoto extends Model
{
    use HasFactory;

    protected $table = 'front_cms_program_photos';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'program_id',
        'media_gallery_id',
    ];

    /**
     * Relationships
     */
    public function program()
    {
        return $this->belongsTo(FrontCmsProgram::class, 'program_id');
    }

    public function mediaGallery()
    {
        return $this->belongsTo(FrontCmsMediaGallery::class, 'media_gallery_id');
    }
}
