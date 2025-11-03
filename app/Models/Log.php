<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $table = 'logs';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'message',
        'record_id',
        'user_id',
        'action',
        'ip_address',
        'platform',
        'agent',
        'time',
    ];

    /**
     * Relation: Each log belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
