<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentFor extends Model
{
    use HasFactory;

    protected $table = 'content_for';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'role',
        'content_id',
        'user_id',
    ];

    /**
     * Relationships
     */
    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
