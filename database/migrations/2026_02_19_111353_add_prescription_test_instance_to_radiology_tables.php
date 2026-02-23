<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add to radiology_report table
        Schema::table('radiology_report', function (Blueprint $table) {
            $table->unsignedBigInteger('ipd_prescription_test_id')->nullable()->after('radiology_id');
            $table->integer('instance_number')->nullable()->after('ipd_prescription_test_id');
            
            // Add foreign key constraint
            $table->foreign('ipd_prescription_test_id')
                  ->references('id')
                  ->on('ipd_prescription_test')
                  ->onDelete('set null');
            
            // Add index for faster lookups
            $table->index('ipd_prescription_test_id', 'idx_radiology_report_prescription_test');
        });

        // Add to radiology_billing table (optional but useful for tracking)
        Schema::table('radiology_billing', function (Blueprint $table) {
            $table->unsignedBigInteger('ipd_prescription_test_id')->nullable()->after('ipd_prescription_basic_id');
            
            // Add foreign key constraint
            $table->foreign('ipd_prescription_test_id')
                  ->references('id')
                  ->on('ipd_prescription_test')
                  ->onDelete('set null');
            
            // Add index
            $table->index('ipd_prescription_test_id', 'idx_radiology_billing_prescription_test');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('radiology_report', function (Blueprint $table) {
            $table->dropForeign(['ipd_prescription_test_id']);
            $table->dropIndex('idx_radiology_report_prescription_test');
            $table->dropColumn(['ipd_prescription_test_id', 'instance_number']);
        });

        Schema::table('radiology_billing', function (Blueprint $table) {
            $table->dropForeign(['ipd_prescription_test_id']);
            $table->dropIndex('idx_radiology_billing_prescription_test');
            $table->dropColumn('ipd_prescription_test_id');
        });
    }
};
