<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareUploadContent extends Model
{
    use HasFactory;

    protected $table = 'share_upload_contents';

    protected $fillable = [
        'hospital_id',
         'branch_id',
        'upload_content_id',
        'share_content_id',
    ];

    // Relationships (optional, if needed)
    public function uploadContent()
    {
        return $this->belongsTo(UploadContent::class, 'upload_content_id');
    }

    public function shareContent()
    {
        return $this->belongsTo(ShareContent::class, 'share_content_id');
    }
}
