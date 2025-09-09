<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = false; // since only created_at exists, no updated_at

    protected $fillable = [
        'hospital_id',
        'user_id',
        'username',
        'password',
        'childs',
        'role',
        'verification_code',
        'is_active',
        'created_at',
    ];

    /**
     * Automatically cast `childs` JSON/text field into array if needed.
     */
    protected $casts = [
        'childs' => 'array',
    ];

    /**
     * Hide password when serializing to array/json.
     */
    protected $hidden = [
        'password',
        'verification_code',
    ];
}
