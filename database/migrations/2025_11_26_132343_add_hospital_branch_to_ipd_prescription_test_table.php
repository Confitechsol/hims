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
        Schema::table('ipd_prescription_test', function (Blueprint $table) {
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('ipd_prescription_test', 'hospital_id')) {
                $table->string('hospital_id', 8)->default('00000001')->after('id');
            }
            if (!Schema::hasColumn('ipd_prescription_test', 'branch_id')) {
                $table->string('branch_id', 8)->default('00000001')->after('hospital_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ipd_prescription_test', function (Blueprint $table) {
            if (Schema::hasColumn('ipd_prescription_test', 'hospital_id')) {
                $table->dropColumn('hospital_id');
            }
            if (Schema::hasColumn('ipd_prescription_test', 'branch_id')) {
                $table->dropColumn('branch_id');
            }
        });
    }
};
