<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        'hospital_id',
        'title',
        'template_id',
        'message',
        'send_mail',
        'send_sms',
        'is_group',
        'is_individual',
        'file',
        'group_list',
        'user_list',
    ];
}
