<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAuthentication extends Model
{
    use HasFactory;

    protected $table = 'users_authentication';
    protected $primaryKey = 'id';
    public $timestamps = true; // since created_at & updated_at exist

    protected $fillable = [
        'hospital_id',
         'branch_id',
        'users_id',
        'token',
        'expired_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation with User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
