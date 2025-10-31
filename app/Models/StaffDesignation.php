<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffDesignation extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'staff_designation';

    // Fillable columns
    protected $fillable = [
        'hospital_id',
         'branch_id',
        'designation',
        'is_active',
    ];

    // Casts for proper data handling
    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
    ];

    // No updated_at column in migration
    public $timestamps = false;

    // Relationships
    public function staff()
    {
        return $this->hasMany(Staff::class, 'staff_designation_id');
    }
}
