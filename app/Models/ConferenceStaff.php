<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConferenceStaff extends Model
{
    use HasFactory;

    protected $table = 'conference_staff';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'conference_id',
        'staff_id',
    ];

    public $timestamps = false; // only created_at exists, no updated_at

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function conference()
    {
        return $this->belongsTo(Conference::class, 'conference_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
