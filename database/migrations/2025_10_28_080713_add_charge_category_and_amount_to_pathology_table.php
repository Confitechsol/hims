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
        Schema::table('pathology', function (Blueprint $table) {
            // Check and add new columns if they don't exist
            if (!Schema::hasColumn('pathology', 'hospital_id')) {
                $table->string('hospital_id', 8)->nullable()->after('id');
            }
            if (!Schema::hasColumn('pathology', 'branch_id')) {
                $table->string('branch_id', 8)->nullable()->after('hospital_id');
            }
            if (!Schema::hasColumn('pathology', 'charge_category_id')) {
                $table->unsignedBigInteger('charge_category_id')->nullable()->index()->after('method');
            }
            if (!Schema::hasColumn('pathology', 'standard_charge')) {
                $table->decimal('standard_charge', 10, 2)->nullable()->after('charge_id');
            }
            if (!Schema::hasColumn('pathology', 'amount')) {
                $table->decimal('amount', 10, 2)->nullable()->after('standard_charge');
            }
        });
        
        // Modify existing columns in a separate statement
        Schema::table('pathology', function (Blueprint $table) {
            $table->string('test_name', 50)->nullable()->change();
            $table->string('short_name', 20)->nullable()->change();
            $table->string('test_type', 15)->nullable()->change();
            $table->string('sub_category', 25)->nullable()->change();
            $table->string('method', 25)->nullable()->change();
            $table->integer('report_days')->nullable()->change();
            $table->string('unit', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pathology', function (Blueprint $table) {
            if (Schema::hasColumn('pathology', 'hospital_id')) {
                $table->dropColumn('hospital_id');
            }
            if (Schema::hasColumn('pathology', 'branch_id')) {
                $table->dropColumn('branch_id');
            }
            if (Schema::hasColumn('pathology', 'charge_category_id')) {
                $table->dropColumn('charge_category_id');
            }
            if (Schema::hasColumn('pathology', 'standard_charge')) {
                $table->dropColumn('standard_charge');
            }
            if (Schema::hasColumn('pathology', 'amount')) {
                $table->dropColumn('amount');
            }
        });
    }
};
