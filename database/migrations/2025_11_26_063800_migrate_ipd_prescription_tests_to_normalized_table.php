<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all existing prescriptions with comma-separated test IDs
        $prescriptions = DB::table('ipd_prescription')
            ->where(function($query) {
                $query->whereNotNull('pathology_id')
                      ->where('pathology_id', '!=', '')
                      ->orWhereNotNull('radiology_id')
                      ->where('radiology_id', '!=', '');
            })
            ->get();

        foreach ($prescriptions as $prescription) {
            $hospitalId = '00000001'; // Default, should get from prescription if available
            $branchId = '00000001';   // Default
            
            // Migrate pathology tests
            if (!empty($prescription->pathology_id)) {
                $pathologyIds = array_filter(
                    array_map('trim', explode(',', $prescription->pathology_id))
                );
                
                foreach ($pathologyIds as $pathologyId) {
                    if (is_numeric($pathologyId) && $pathologyId > 0) {
                        DB::table('ipd_prescription_test')->insert([
                            'hospital_id' => $hospitalId,
                            'branch_id' => $branchId,
                            'ipd_prescription_id' => $prescription->id,
                            'pathology_id' => (int)$pathologyId,
                            'radiology_id' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
            
            // Migrate radiology tests
            if (!empty($prescription->radiology_id)) {
                $radiologyIds = array_filter(
                    array_map('trim', explode(',', $prescription->radiology_id))
                );
                
                foreach ($radiologyIds as $radiologyId) {
                    if (is_numeric($radiologyId) && $radiologyId > 0) {
                        DB::table('ipd_prescription_test')->insert([
                            'hospital_id' => $hospitalId,
                            'branch_id' => $branchId,
                            'ipd_prescription_id' => $prescription->id,
                            'pathology_id' => null,
                            'radiology_id' => (int)$radiologyId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert: Convert normalized tests back to comma-separated strings
        $prescriptions = DB::table('ipd_prescription')->get();
        
        foreach ($prescriptions as $prescription) {
            // Get pathology IDs
            $pathologyTests = DB::table('ipd_prescription_test')
                ->where('ipd_prescription_id', $prescription->id)
                ->whereNotNull('pathology_id')
                ->pluck('pathology_id')
                ->toArray();
            
            // Get radiology IDs
            $radiologyTests = DB::table('ipd_prescription_test')
                ->where('ipd_prescription_id', $prescription->id)
                ->whereNotNull('radiology_id')
                ->pluck('radiology_id')
                ->toArray();
            
            // Update prescription with comma-separated values
            DB::table('ipd_prescription')
                ->where('id', $prescription->id)
                ->update([
                    'pathology_id' => !empty($pathologyTests) ? implode(', ', $pathologyTests) : null,
                    'radiology_id' => !empty($radiologyTests) ? implode(', ', $radiologyTests) : null,
                ]);
        }
        
        // Delete migrated test records
        DB::table('ipd_prescription_test')
            ->whereNotNull('ipd_prescription_id')
            ->delete();
    }
};
