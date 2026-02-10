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
        Schema::table('ipd_details', function (Blueprint $table) {
            $table->string('due_patient_party_receipt_type', 50)->nullable()->after('due_patient_party_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ipd_details', function (Blueprint $table) {
            $table->dropColumn('due_patient_party_receipt_type');
        });
    }
};
