<?php

use App\Models\PermissionCategory;

if (!function_exists('hasPermission')) {
    /**
     * Check if the current user has a specific permission
     * 
     * @param int $permCatId Permission category ID
     * @param string $permissionType Type of permission: 'view', 'add', 'edit', 'delete'
     * @return bool
     */
    function hasPermission($permCatId, $permissionType = 'view')
    {
        if (isSuperAdmin()) {
            return true;
        }

        // Get permissions from session
        $permissions = session('user_permissions', []);
        
        // Check if permission exists for this category
        if (!isset($permissions[$permCatId])) {
            return false;
        }
        
        // Map permission type to session key
        $permissionKey = 'can_' . strtolower($permissionType);
        
        // Check if the specific permission is granted
        return isset($permissions[$permCatId][$permissionKey]) 
            && $permissions[$permCatId][$permissionKey] === true;
    }
}

if (!function_exists('canView')) {
    /**
     * Check if user can view a permission category
     * 
     * @param int $permCatId Permission category ID
     * @return bool
     */
    function canView($permCatId)
    {
        return hasPermission($permCatId, 'view');
    }
}

if (!function_exists('canAdd')) {
    /**
     * Check if user can add in a permission category
     * 
     * @param int $permCatId Permission category ID
     * @return bool
     */
    function canAdd($permCatId)
    {
        return hasPermission($permCatId, 'add');
    }
}

if (!function_exists('canEdit')) {
    /**
     * Check if user can edit in a permission category
     * 
     * @param int $permCatId Permission category ID
     * @return bool
     */
    function canEdit($permCatId)
    {
        return hasPermission($permCatId, 'edit');
    }
}

if (!function_exists('canDelete')) {
    /**
     * Check if user can delete in a permission category
     * 
     * @param int $permCatId Permission category ID
     * @return bool
     */
    function canDelete($permCatId)
    {
        return hasPermission($permCatId, 'delete');
    }
}

if (!function_exists('hasAnyPermission')) {
    /**
     * Check if user has any of the specified permissions
     * 
     * @param int $permCatId Permission category ID
     * @param array $permissionTypes Array of permission types: ['view', 'add', 'edit', 'delete']
     * @return bool
     */
    function hasAnyPermission($permCatId, array $permissionTypes)
    {
        foreach ($permissionTypes as $type) {
            if (hasPermission($permCatId, $type)) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('hasAllPermissions')) {
    /**
     * Check if user has all of the specified permissions
     * 
     * @param int $permCatId Permission category ID
     * @param array $permissionTypes Array of permission types: ['view', 'add', 'edit', 'delete']
     * @return bool
     */
    function hasAllPermissions($permCatId, array $permissionTypes)
    {
        foreach ($permissionTypes as $type) {
            if (!hasPermission($permCatId, $type)) {
                return false;
            }
        }
        return true;
    }
}

if (!function_exists('getUserRoleId')) {
    /**
     * Get current user's role ID from session
     * 
     * @return int|null
     */
    function getUserRoleId()
    {
        return session('user_role_id');
    }
}

if (!function_exists('getUserRoleName')) {
    /**
     * Get current user's role name from session
     * 
     * @return string|null
     */
    function getUserRoleName()
    {
        return session('user_role_name');
    }
}

if (!function_exists('isSuperAdmin')) {
    /**
     * Check if current user is super admin
     * 
     * @return bool
     */
    function isSuperAdmin()
    {
        $roleName = strtolower(trim((string) getUserRoleName()));

        return in_array($roleName, [
            'super admin',
            'admin',
            'administrator',
            'adm',
        ], true);
    }
}

if (!function_exists('permCatId')) {
    /**
     * Resolve permission category ID by name or short code.
     *
     * Uses a simple static cache so repeated calls within a single request
     * don't hit the database multiple times.
     *
     * @param string $identifier Permission category name or short_code
     * @return int|null
     */
    function permCatId(string $identifier): ?int
    {
        static $cache = [];

        if (array_key_exists($identifier, $cache)) {
            return $cache[$identifier];
        }

        $category = PermissionCategory::query()
            ->where('name', $identifier)
            ->orWhere('short_code', $identifier)
            ->first();

        $cache[$identifier] = $category?->id;

        return $cache[$identifier];
    }
}
