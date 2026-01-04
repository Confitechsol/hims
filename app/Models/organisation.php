<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organisation extends Model
{
    use HasFactory;

    protected $table = 'organisation';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'organisation_name',
        'code',
        'contact_no',
        'address',
        'contact_person_name',
        'contact_person_phone',
        'poilicy_no',
        'e_card_no',
        'e_card_upload',
    ];

    public $timestamps = false;
}
