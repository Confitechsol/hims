<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $table = 'contents';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'title',
        'type',
        'is_public',
        'file',
        'note',
        'date',
        'is_active',
        'created_by',
    ];

    /**
     * A Content may belong to a ContentType (optional, if you later add relation)
     */
    public function contentType()
    {
        return $this->belongsTo(ContentType::class, 'type', 'name');
    }

    /**
     * A Content may be assigned to users via content_for
     */
    public function assignedUsers()
    {
        return $this->hasMany(ContentFor::class, 'content_id');
    }

    /**
     * Creator relationship (if created_by refers to users table)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
