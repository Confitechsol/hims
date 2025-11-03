<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatUser extends Model
{
    use HasFactory;

    protected $table = 'chat_users';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'user_type',
        'staff_id',
        'patient_id',
        'create_staff_id',
        'create_patient_id',
        'is_active',
    ];

    public $timestamps = false; // since created_at/updated_at are custom

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'date',
    ];

    /**
     * Relationships
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function creatorStaff()
    {
        return $this->belongsTo(Staff::class, 'create_staff_id');
    }

    public function creatorPatient()
    {
        return $this->belongsTo(Patient::class, 'create_patient_id');
    }

    public function connectionsAsUserOne()
    {
        return $this->hasMany(ChatConnection::class, 'chat_user_one');
    }

    public function connectionsAsUserTwo()
    {
        return $this->hasMany(ChatConnection::class, 'chat_user_two');
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'chat_user_id');
    }
}
