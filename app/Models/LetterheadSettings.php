<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterheadSettings extends Model
{
    use HasFactory;

    // protected $table = 'letterhead_settings';

    protected $fillable = [
        'hospital_id',
         'branch_id',
        'letterhead_cat_id',
        'print_header',
        'print_footer',
        'setting_for',
        'is_active',
    ];

    // // Cast blobs to string when retrieving
    // protected $casts = [
    //     'print_header' => 'string',
    //     'print_footer' => 'string',
    // ];
}