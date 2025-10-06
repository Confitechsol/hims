<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatConnection extends Model
{
    use HasFactory;

    protected $table = 'chat_connections';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'chat_user_one',
        'chat_user_two',
        'ip',
        'time',
    ];

    public $timestamps = false; // because created_at & updated_at are custom

    protected $casts = [
        'time' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'date',
    ];

    /**
     * Relationships
     */
    public function userOne()
    {
        return $this->belongsTo(User::class, 'chat_user_one');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'chat_user_two');
    }
}
