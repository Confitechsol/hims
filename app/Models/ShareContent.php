<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareContent extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'share_contents';

    // Mass assignable attributes
    protected $fillable = [
        'hospital_id',
         'branch_id',
        'send_to',
        'title',
        'share_date',
        'valid_upto',
        'description',
        'created_by',
    ];
}
