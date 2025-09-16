<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolesPermission extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'roles_permissions';

    // Mass assignable attributes
    protected $fillable = [
        'hospital_id',
        'role_id',
        'perm_cat_id',
        'can_view',
        'can_add',
        'can_edit',
        'can_delete',
    ];

    /**
     * Relationships
     */

    // Each record belongs to a role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // Each record belongs to a permission category
    public function permissionCategory()
    {
        return $this->belongsTo(PermissionCategory::class, 'perm_cat_id');
    }
   
}
