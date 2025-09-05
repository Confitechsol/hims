<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionCategory extends Model
{
    use HasFactory;

    protected $table = 'permission_category';

    public $timestamps = false; // only created_at exists

    protected $fillable = [
        'hospital_id',
        'perm_group_id',
        'name',
        'short_code',
        'enable_view',
        'enable_add',
        'enable_edit',
        'enable_delete',
        'created_at',
    ];

    protected $casts = [
        'enable_view' => 'boolean',
        'enable_add' => 'boolean',
        'enable_edit' => 'boolean',
        'enable_delete' => 'boolean',
        'created_at' => 'datetime',
    ];

    /*
     * Relationships
     */
    public function permissionGroup()
    {
        return $this->belongsTo(PermissionGroup::class, 'perm_group_id');
    }
}
