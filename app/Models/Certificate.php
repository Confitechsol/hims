<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $table = 'certificates';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'certificate_name',
        'certificate_text',
        'left_header',
        'center_header',
        'right_header',
        'left_footer',
        'right_footer',
        'center_footer',
        'background_image',
        'created_for',
        'status',
        'header_height',
        'content_height',
        'footer_height',
        'content_width',
        'enable_patient_image',
        'enable_image_height',
        'updated_at',
    ];

    // Only created_at & updated_at exist (but updated_at is a DATE, not TIMESTAMP)
    public $timestamps = false;

    protected $casts = [
        'created_for' => 'boolean',
        'status' => 'boolean',
        'enable_patient_image' => 'boolean',
        'header_height' => 'integer',
        'content_height' => 'integer',
        'footer_height' => 'integer',
        'content_width' => 'integer',
        'enable_image_height' => 'integer',
        'updated_at' => 'date',
        'created_at' => 'datetime',
    ];
}
