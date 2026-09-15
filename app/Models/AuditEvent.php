<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditEvent extends Model
{
    protected $table = 'audit_events';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'occurred_at',
        'user_id',
        'user_role_name',
        'ip_address',
        'user_agent',
        'module',
        'entity_type',
        'entity_id',
        'parent_type',
        'parent_id',
        'patient_id',
        'case_no',
        'action',
        'reason',
        'request_route',
        'request_method',
        'old_values',
        'new_values',
        'meta',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'old_values' => 'array',
        'new_values' => 'array',
        'meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
