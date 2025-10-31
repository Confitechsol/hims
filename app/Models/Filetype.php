<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filetype extends Model
{
    use HasFactory;

    protected $table = 'filetypes';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'file_extension',
        'file_mime',
        'file_size',
        'image_extension',
        'image_mime',
        'image_size',
    ];
}
