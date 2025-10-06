<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    use HasFactory;

    // Table name (optional if it follows Laravel convention)
    protected $table = 'symptoms';

    // Primary key (optional since it's 'id' by default)
    protected $primaryKey = 'id';

    // Timestamps handling
    public $timestamps = false; // since we only have created_at and no updated_at

    // Mass assignable attributes
    protected $fillable = [
         'hospital_id',
         'branch_id',
        'symptoms_title',
        'description',
        'type',
        'created_at',
    ];

    // If you want, you can also cast created_at to a datetime automatically
    protected $casts = [
        'created_at' => 'datetime',
    ];
     public function classification()
    {
        return $this->belongsTo(SymptomsClassification::class, 'type', 'id');
    }
}
