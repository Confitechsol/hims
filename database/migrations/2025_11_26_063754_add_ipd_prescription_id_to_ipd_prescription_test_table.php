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
            $table->unsignedBigInteger('ipd_prescription_id')->nullable()->after('ipd_prescription_basic_id')->index();
            
            $table->foreign('ipd_prescription_id')
                  ->references('id')
                  ->on('ipd_prescription')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ipd_prescription_test', function (Blueprint $table) {
            $table->dropForeign(['ipd_prescription_id']);
            $table->dropColumn('ipd_prescription_id');
        });
    }
};
