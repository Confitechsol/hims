<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ipd_packages', function (Blueprint $table) {
            if (!Schema::hasColumn('ipd_packages', 'approval_percentage')) {
                $table->decimal('approval_percentage', 5, 2)->nullable()->after('package_rate');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ipd_packages', function (Blueprint $table) {
            if (Schema::hasColumn('ipd_packages', 'approval_percentage')) {
                $table->dropColumn('approval_percentage');
            }
        });
    }
};
