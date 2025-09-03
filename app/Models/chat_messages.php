<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $table = 'chat_messages';

    protected $fillable = [
        'hospital_id',
        'message',
        'chat_user_id',
        'ip',
        'time',
        'is_first',
        'is_read',
        'chat_connection_id',
    ];

    public $timestamps = false; // since created_at is nullable & custom

    protected $casts = [
        'time' => 'integer',
        'is_first' => 'boolean',
        'is_read' => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'chat_user_id');
    }

    public function connection()
    {
        return $this->belongsTo(ChatConnection::class, 'chat_connection_id');
    }
}
