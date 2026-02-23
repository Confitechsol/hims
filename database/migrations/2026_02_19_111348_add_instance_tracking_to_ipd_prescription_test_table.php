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
        Schema::table('ipd_prescription_test', function (Blueprint $table) {
            // Add instance tracking fields
            $table->integer('instance_number')->default(1)->after('radiology_id');
            $table->date('test_date')->nullable()->after('instance_number');
            $table->time('prescription_time')->nullable()->after('test_date');
            $table->text('notes')->nullable()->after('prescription_time');
            
            // Add index for faster queries on same test same day
            $table->index(['pathology_id', 'test_date'], 'idx_pathology_test_date');
            $table->index(['radiology_id', 'test_date'], 'idx_radiology_test_date');
        });

        // Migrate existing data: Set instance_number = 1 and test_date from prescription
        DB::statement("
            UPDATE ipd_prescription_test ipt
            INNER JOIN ipd_prescription ip ON ipt.ipd_prescription_id = ip.id
            SET 
                ipt.instance_number = 1,
                ipt.test_date = DATE(ip.date),
                ipt.prescription_time = TIME(ip.created_at)
            WHERE ipt.instance_number IS NULL OR ipt.test_date IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ipd_prescription_test', function (Blueprint $table) {
            $table->dropIndex('idx_pathology_test_date');
            $table->dropIndex('idx_radiology_test_date');
            $table->dropColumn(['instance_number', 'test_date', 'prescription_time', 'notes']);
        });
    }
};
