<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NurseNote extends Model
{
    use HasFactory;

    protected $table = 'nurse_note';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'date',
        'ipd_id',
        'staff_id',
        'note',
        'comment',
        'created_by',
    ];

    /**
     * Relationships
     */

    public function ipd()
    {
        return $this->belongsTo(IpdDetail::class, 'ipd_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
