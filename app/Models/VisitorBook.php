<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorBook extends Model
{
    use HasFactory;

    protected $table = 'visitors_book';

    protected $fillable = [
        'hospital_id',
         'branch_id',
        'source',
        'purpose',
        'name',
        'email',
        'contact',
        'id_proof',
        'visit_to',
        'ipd_opd_staff_id',
        'related_to',
        'no_of_pepple',
        'date',
        'in_time',
        'out_time',
        'note',
        'image',
    ];

    protected $casts = [
        'date' => 'date',
        'in_time' => 'datetime:H:i',
        'out_time' => 'datetime:H:i',
    ];

    /**
     * Example relationship: link to staff (if ipd_opd_staff_id refers to a staff user)
     */
    public function staff()
    {
        return $this->belongsTo(User::class, 'ipd_opd_staff_id');
    }
}
