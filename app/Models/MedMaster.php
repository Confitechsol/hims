<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedMaster extends Model
{
    use HasFactory;

    protected $table   = 'medicine_master';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'price',
        'manufacturer_name',
        'pack_size_label',
        'short_composition1',
        'short_composition2',
    ];
}
