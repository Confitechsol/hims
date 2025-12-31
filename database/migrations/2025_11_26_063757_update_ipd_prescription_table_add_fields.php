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
        Schema::table('ipd_prescription', function (Blueprint $table) {
            // Add attachment fields
            $table->string('attachment')->nullable()->after('prescribed_by');
            $table->string('attachment_name')->nullable()->after('attachment');
            
            // Add visit_details_id for future OPD integration
            $table->unsignedBigInteger('visit_details_id')->nullable()->after('ipd_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ipd_prescription', function (Blueprint $table) {
            $table->dropColumn(['attachment', 'attachment_name', 'visit_details_id']);
        });
    }
};
