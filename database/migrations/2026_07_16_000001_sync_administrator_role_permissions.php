<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ensure administrator roles have full access to all permission categories,
     * including Expense (12) and Expense Head (13).
     */
    public function up(): void
    {
        $adminRoleIds = DB::table('roles')
            ->whereRaw('LOWER(TRIM(name)) IN (?, ?, ?, ?)', [
                'admin',
                'super admin',
                'administrator',
                'adm',
            ])
            ->pluck('id');

        if ($adminRoleIds->isEmpty()) {
            return;
        }

        $categoryIds = DB::table('permission_category')->pluck('id');
        $defaultHospitalId = DB::table('roles_permissions')
            ->whereIn('role_id', $adminRoleIds)
            ->whereNotNull('hospital_id')
            ->where('hospital_id', '<>', '')
            ->value('hospital_id')
            ?? 'HS001';
        $defaultBranchId = DB::table('roles_permissions')
            ->whereIn('role_id', $adminRoleIds)
            ->whereNotNull('branch_id')
            ->value('branch_id')
            ?? '';

        foreach ($adminRoleIds as $roleId) {
            $hospitalId = DB::table('roles_permissions')
                ->where('role_id', $roleId)
                ->whereNotNull('hospital_id')
                ->value('hospital_id')
                ?? $defaultHospitalId;
            $branchId = DB::table('roles_permissions')
                ->where('role_id', $roleId)
                ->whereNotNull('branch_id')
                ->value('branch_id')
                ?? $defaultBranchId;

            foreach ($categoryIds as $categoryId) {
                $existing = DB::table('roles_permissions')
                    ->where('role_id', $roleId)
                    ->where('perm_cat_id', $categoryId)
                    ->first();

                if ($existing) {
                    DB::table('roles_permissions')
                        ->where('id', $existing->id)
                        ->update([
                            'can_view' => 1,
                            'can_add' => 1,
                            'can_edit' => 1,
                            'can_delete' => 1,
                        ]);
                } else {
                    DB::table('roles_permissions')->insert([
                        'hospital_id' => $hospitalId,
                        'branch_id' => $branchId,
                        'role_id' => $roleId,
                        'perm_cat_id' => $categoryId,
                        'can_view' => 1,
                        'can_add' => 1,
                        'can_edit' => 1,
                        'can_delete' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        // Non-destructive: do not revoke administrator permissions on rollback.
    }
};
