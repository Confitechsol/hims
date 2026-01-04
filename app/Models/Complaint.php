<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $table = 'complaint';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'complaint_type_id',
        'source',
        'name',
        'contact',
        'email',
        'date',
        'description',
        'action_taken',
        'assigned',
        'note',
        'image',
    ];

    public $timestamps = false; // no default Laravel timestamps

    protected $casts = [
        'date' => 'date',
        'created_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function complaintType()
    {
        return $this->belongsTo(ComplaintType::class, 'complaint_type_id');
    }
}
