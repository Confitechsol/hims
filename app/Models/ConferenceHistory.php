<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConferenceHistory extends Model
{
    use HasFactory;

    protected $table = 'conferences_history';

    protected $fillable = [
        'hospital_id',
        'conference_id',
        'staff_id',
        'patient_id',
        'total_hit',
    ];

    public $timestamps = false; // only created_at exists

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

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
