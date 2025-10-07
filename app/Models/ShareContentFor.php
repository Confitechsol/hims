<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareContentFor extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'share_content_for';

    // Mass assignable attributes
    protected $fillable = [
        'hospital_id',
         'branch_id',
        'group_id',
        'patient_id',
        'staff_id',
        'share_content_id',
    ];
}
