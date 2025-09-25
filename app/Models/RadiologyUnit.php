<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadiologyUnit extends Model
{
    use HasFactory;

    protected $table = 'radiology_unit';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'name',
    ];

}