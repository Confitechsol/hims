<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userlog extends Model
{
    use HasFactory;

    protected $table = 'userlog';
    protected $primaryKey = 'id';
    public $timestamps = false; // only login_datetime exists, no created_at/updated_at pair

    protected $fillable = [
        'hospital_id',
         'branch_id',
        'user',
        'role',
        'ipaddress',
        'user_agent',
        'login_datetime',
    ];
}
