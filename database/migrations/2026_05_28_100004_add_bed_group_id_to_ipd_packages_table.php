<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ipd_packages', function (Blueprint $table) {
            $table->unsignedInteger('bed_group_id')->nullable()->after('package_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('ipd_packages', function (Blueprint $table) {
            $table->dropColumn('bed_group_id');
        });
    }
};
