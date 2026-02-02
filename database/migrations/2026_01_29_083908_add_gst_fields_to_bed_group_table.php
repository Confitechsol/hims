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
        Schema::table('bed_group', function (Blueprint $table) {
            $table->string('sac_hsn_code', 50)->nullable()->after('description');
            $table->decimal('gst_rate', 10, 2)->nullable()->after('sac_hsn_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bed_group', function (Blueprint $table) {
            $table->dropColumn(['sac_hsn_code', 'gst_rate']);
        });
    }
};
