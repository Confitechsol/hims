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
        Schema::create('discharge_card', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8)->nullable()->index();
            $table->string('branch_id', 8)->nullable()->index();

            // 🔹 Relations
            $table->unsignedBigInteger('case_reference_id')->nullable()->index();
            $table->unsignedBigInteger('opd_details_id')->nullable()->index();
            $table->unsignedBigInteger('ipd_details_id')->nullable()->index();

            // 🔹 Patient & Admission
            $table->string('patient_name');
            $table->string('admission_no')->nullable();
            $table->string('bed')->nullable();

            $table->date('admission_date')->nullable();
            $table->time('admit_time')->nullable();

            // 🔹 Discharge
            $table->date('discharge_date');
            $table->time('discharge_time')->nullable();
            $table->string('reason_discharge')->nullable();

            // 🔹 Patient Details
            $table->string('age', 50)->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('marital_status', 20)->nullable();
            $table->text('address')->nullable();

            $table->string('guardian')->nullable();
            $table->string('relation')->nullable();
            $table->string('nationality')->nullable();

            // 🔹 Medical / Admin
            $table->string('under_care_dr')->nullable();
            $table->string('referral')->nullable();
            $table->string('corporate')->nullable();

            // 🔹 OT Details
            $table->date('ot_date')->nullable();
            $table->string('ot_type')->nullable();
            $table->string('ot_name')->nullable();
            $table->integer('ot_done')->nullable();
            $table->text('ot_done_by')->nullable(); // comma-separated IDs

            // 🔹 Diagnosis
            $table->text('diagnosis')->nullable();
            $table->text('ot_note')->nullable();
            $table->text('discharge_advice')->nullable();
            $table->text('present_complaints')->nullable();
            $table->text('remarks')->nullable();

            // 🔹 Meta
            $table->string('discharged_by', 255)->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable()->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discharge_card');
    }
};
