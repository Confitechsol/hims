<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintType extends Model
{
    use HasFactory;

    protected $table = 'complaint_type';

    protected $fillable = [
        'hospital_id',
        'complaint_type',
        'description',
    ];

    public $timestamps = false; // only created_at exists, no updated_at

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'complaint_type_id');
    }
}
