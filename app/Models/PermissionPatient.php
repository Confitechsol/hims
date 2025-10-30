<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionPatient extends Model
{
    use HasFactory;

    protected $table = 'permission_patient';

    public $timestamps = false; // only created_at exists

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'permission_group_short_code',
        'name',
        'short_code',
        'is_active',
        'system',
        'sort_order',
        'created_at',
    ];

    protected $casts = [
        'is_active' => 'integer',
        'system' => 'integer',
        'sort_order' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /*
     * Relationships (if needed later)
     * Example: belongsTo PermissionGroup using short_code
     */
    public function permissionGroup()
    {
        return $this->belongsTo(PermissionGroup::class, 'permission_group_short_code', 'short_code');
    }
}
