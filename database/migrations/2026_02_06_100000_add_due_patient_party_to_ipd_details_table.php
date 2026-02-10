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
            $table->unsignedBigInteger('due_patient_party_doctor_id')->nullable()->after('special_discount');
            $table->decimal('due_patient_party_amount', 12, 2)->default(0)->after('due_patient_party_doctor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ipd_details', function (Blueprint $table) {
            $table->dropColumn(['due_patient_party_doctor_id', 'due_patient_party_amount']);
        });
    }
};
