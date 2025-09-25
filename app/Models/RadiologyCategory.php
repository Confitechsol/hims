<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadiologyCategory extends Model
{
    use HasFactory;

    protected $table = 'radiology_category';

    protected $fillable = [
        'hospital_id',
        'name',
        'is_active',
    ];

}