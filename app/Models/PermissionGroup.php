<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    use HasFactory;

    protected $table = 'permission_group';

    public $timestamps = false; // only created_at exists

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'name',
        'short_code',
        'is_active',
        'system',
        'sort_order',
        'created_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'system' => 'integer',
        'sort_order' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /*
     * Relationships
     */
    public function categories()
    {
        return $this->hasMany(PermissionCategory::class, 'perm_group_id');
    }
}
