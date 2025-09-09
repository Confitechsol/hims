<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadContent extends Model
{
    use HasFactory;

    protected $table = 'upload_contents';
    protected $primaryKey = 'id';
    public $timestamps = false; // because only created_at exists

    protected $fillable = [
        'hospital_id',
        'content_type_id',
        'image',
        'thumb_path',
        'dir_path',
        'real_name',
        'img_name',
        'thumb_name',
        'file_type',
        'mime_type',
        'file_size',
        'vid_url',
        'vid_title',
        'upload_by',
        'created_at',
    ];
}
