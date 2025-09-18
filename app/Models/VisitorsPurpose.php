<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorsPurpose extends Model
{
    use HasFactory;

    protected $table = 'visitors_purpose';

    public $timestamps = true; // Since created_at exists, Laravel can manage it

    protected $fillable = [
        'hospital_id',
        'visitors_purpose',
        'description',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Example relationship: 
     */
    public function visitorBooks()
    {
        return $this->hasMany(VisitorBook::class, 'purpose', 'visitors_purpose');
    }
}
