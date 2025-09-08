<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'roles';

    // Mass assignable attributes
    protected $fillable = [
        'hospital_id',
        'name',
        'slug',
        'is_active',
        'is_system',
        'is_superadmin',
    ];

    /**
     * Relationships
     */

    // A role may have many permissions
    public function permissions()
    {
        return $this->hasMany(RolesPermission::class, 'role_id');
    }
}
