<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintSetting extends Model
{
    use HasFactory;

    protected $table = 'print_setting';

    protected $fillable = [
        'hospital_id',
        'print_header',
        'print_footer',
        'setting_for',
        'is_active',
    ];
}