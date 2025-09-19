<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BedType extends Model
{
    use HasFactory;

    protected $table = 'bed_type';

    public $timestamps = false; 
    protected $fillable = [
        'hospital_id',
        'name',
    ];

    // Relationships
    public function beds(): HasMany
    {
        return $this->hasMany(Bed::class, 'bed_type_id');
    }
}
