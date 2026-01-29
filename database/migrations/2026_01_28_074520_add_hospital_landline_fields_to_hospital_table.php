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
        Schema::table('hospital', function (Blueprint $table) {
            $table->string('hospital_landline_1', 50)->nullable()->after('phone');
            $table->string('hospital_landline_2', 50)->nullable()->after('hospital_landline_1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospital', function (Blueprint $table) {
            $table->dropColumn(['hospital_landline_1', 'hospital_landline_2']);
        });
    }
};
