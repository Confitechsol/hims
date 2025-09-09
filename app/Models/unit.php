<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    // Table name
    protected $table = 'unit';

    // Primary key
    protected $primaryKey = 'id';

    // No updated_at column, only created_at
    public $timestamps = false;

    // Mass assignable fields
    protected $fillable = [
        'hospital_id',
        'unit_name',
        'unit_type',
        'created_at',
    ];

    /**
     * Relationships
     */

    // If hospital_id maps to a Hospital model
    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id', 'hospital_id');
    }
}
