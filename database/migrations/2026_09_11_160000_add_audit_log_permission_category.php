<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Seed Audit Log permission category and grant admin roles view access.
     */
    public function up(): void
    {
        $hospitals = DB::table('permission_category')
            ->select('hospital_id', 'branch_id')
            ->distinct()
            ->get();

        if ($hospitals->isEmpty()) {
            $hospitals = collect([(object) [
                'hospital_id' => DB::table('roles_permissions')->value('hospital_id') ?? 'HS001',
                'branch_id' => DB::table('roles_permissions')->value('branch_id') ?? '',
            ]]);
        }

        $categoryIds = [];

        foreach ($hospitals as $hospital) {
            $existing = DB::table('permission_category')
                ->where('hospital_id', $hospital->hospital_id)
                ->where('short_code', 'audit_log')
                ->first();

            if ($existing) {
                $categoryIds[] = $existing->id;
                continue;
            }

            $categoryIds[] = DB::table('permission_category')->insertGetId([
                'hospital_id' => $hospital->hospital_id,
                'branch_id' => $hospital->branch_id ?? '',
                'perm_group_id' => null,
                'name' => 'Audit Log',
                'short_code' => 'audit_log',
                'enable_view' => 1,
                'enable_add' => 0,
                'enable_edit' => 0,
                'enable_delete' => 0,
                'created_at' => now(),
            ]);
        }

        $adminRoleIds = DB::table('roles')
            ->whereRaw('LOWER(TRIM(name)) IN (?, ?, ?, ?)', [
                'admin',
                'super admin',
                'administrator',
                'adm',
            ])
            ->pluck('id');

        foreach ($adminRoleIds as $roleId) {
            $hospitalId = DB::table('roles_permissions')
                ->where('role_id', $roleId)
                ->whereNotNull('hospital_id')
                ->value('hospital_id')
                ?? ($hospitals->first()->hospital_id ?? 'HS001');
            $branchId = DB::table('roles_permissions')
                ->where('role_id', $roleId)
                ->whereNotNull('branch_id')
                ->value('branch_id')
                ?? ($hospitals->first()->branch_id ?? '');

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
                            'can_add' => 0,
                            'can_edit' => 0,
                            'can_delete' => 0,
                        ]);
                } else {
                    DB::table('roles_permissions')->insert([
                        'hospital_id' => $hospitalId,
                        'branch_id' => $branchId,
                        'role_id' => $roleId,
                        'perm_cat_id' => $categoryId,
                        'can_view' => 1,
                        'can_add' => 0,
                        'can_edit' => 0,
                        'can_delete' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        $categoryIds = DB::table('permission_category')
            ->where('short_code', 'audit_log')
            ->pluck('id');

        if ($categoryIds->isEmpty()) {
            return;
        }

        DB::table('roles_permissions')->whereIn('perm_cat_id', $categoryIds)->delete();
        DB::table('permission_category')->whereIn('id', $categoryIds)->delete();
    }
};
