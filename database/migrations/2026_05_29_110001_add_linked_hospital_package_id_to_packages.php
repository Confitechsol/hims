<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('packages', 'linked_hospital_package_id')) {
                $table->unsignedBigInteger('linked_hospital_package_id')->nullable()->after('insurance_rate_panel_id');
                $table->foreign('linked_hospital_package_id')
                    ->references('id')->on('packages')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            if (Schema::hasColumn('packages', 'linked_hospital_package_id')) {
                $table->dropForeign(['linked_hospital_package_id']);
                $table->dropColumn('linked_hospital_package_id');
            }
        });
    }
};
