<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->string('discharge_number', 20)->nullable();
            $table->string('patient_name');
            $table->integer('patient_id')->nullable();
            $table->string('admission_no')->nullable();
            $table->string('discharge_contact')->nullable();
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
            $table->string('registration_no')->nullable();
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
            $table->text('investigation')->nullable();
            $table->text('urgent_care')->nullable();
            $table->text('diet_advice')->nullable();
            $table->text('course_in_hospital')->nullable();
            $table->text('present_complaints')->nullable();
            $table->text('remarks')->nullable();
            $table->text('medicines')->nullable();
            $table->text('medicine_types')->nullable();
            $table->text('intervals')->nullable();
            $table->text('durations')->nullable();
            $table->string('med_dates', 255)->nullable();
            $table->text('doctor_advice')->nullable();

            // 🔹 Meta
            $table->string('discharged_by', 255)->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable()->index();

            $table->timestamps();
        });
        DB::statement('ALTER TABLE discharge_card ADD barcode LONGBLOB NULL AFTER id');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discharge_card');
    }
};
