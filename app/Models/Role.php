<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// extend Spatie’s Role model

class Role extends Model
{
    use HasFactory;

    // Table name
    protected $table   = 'roles';
    public $timestamps = false;

    // Mass assignable attributes
    protected $fillable = [
        'hospital_id',
        'branch_id',
        'name',
        'slug',
        'is_active',
        'is_system',
        'is_superadmin',
    ];

    /**
     * Relationships
     */

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }

    // Branch relation
    public function branch()
    {
        return $this->belongsTo(HospitalBranch::class, 'branch_id');
    }

    // Role has many permissions
    public function permissions()
    {
        return $this->belongsToMany(RolesPermission::class, 'role_permissions', 'role_id', 'permission_id')
            ->withTimestamps();
    }
}