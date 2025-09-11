<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NurseNotesComment extends Model
{
    use HasFactory;

    protected $table = 'nurse_notes_comment';

    protected $fillable = [
        'hospital_id',
        'nurse_note_id',
        'comment_staffid',
        'comment_staff',
    ];

    /**
     * Relationships
     */

    public function nurseNote()
    {
        return $this->belongsTo(NurseNote::class, 'nurse_note_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'comment_staffid');
    }
}
