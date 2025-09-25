<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterheadCategory extends Model
{
    use HasFactory;

    protected $table = 'letterhead_category';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'name',
        'header_text',
        'enable_view',
        'enable_add',
        'enable_edit',
        'enable_delete',
    ];
}