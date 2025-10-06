<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentType extends Model
{
    use HasFactory;

    protected $table = 'content_types';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'name',
        'description',
        'is_active',
    ];

    /**
     * Example relationship:
     * One ContentType may have many Contents
     */
    public function contents()
    {
        return $this->hasMany(Content::class, 'content_type_id');
    }
}
