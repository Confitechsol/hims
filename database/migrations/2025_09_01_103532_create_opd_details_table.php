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
        Schema::create('opd_details', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('opd_no', 255);

            // 🔹 Foreign keys
            $table->integer('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->integer('charge_category_id');
            $table->integer('charge_id');
            $table->integer('created_by');

            // 🔹 Appointment & Case Details
            $table->date('appointment_date');
            $table->date('payment_date')->nullable();
            $table->string('case_type');
            $table->string('casualty');
            $table->string('reference')->nullable();

            // 🔹 Financial Details
            $table->decimal('standard_charge', 10, 2)->default(0);
            $table->decimal('applied_charge', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->string('payment_mode', 50)->nullable();

            // 🔹 Consultation Info
            $table->string('live_consultation', 100)->nullable();

            // 🔹 Symptoms & Allergies
            $table->text('symptoms_type');
            $table->text('symptoms_title');
            $table->text('symptoms_description');
            $table->text('allergies')->nullable();
            $table->text('note')->nullable();

            // 🔹 Misc
            $table->string('status', 50)->nullable()->default('Active');
            $table->string('apply_tpa', 10)->nullable()->default('No');

            // 🔹 Timestamps
            $table->timestamps();

            // 🔹 Foreign key constraints
            $table->foreign('patient_id')
                ->references('id')->on('patients')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('doctor_id')
                ->references('id')->on('doctor')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('charge_category_id')
                ->references('id')->on('charge_categories')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('charge_id')
                ->references('id')->on('charges')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')
                ->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');

            // $table->unsignedBigInteger('case_reference_id')->nullable()->index();
            // $table->unsignedBigInteger('patient_id')->nullable()->index();
            // $table->unsignedBigInteger('generated_by')->nullable()->index();
            // $table->integer('is_ipd_moved')->default(0);
            // $table->string('discharged', 10)->default('no');
            // $table->string('opd_no', 255)->nullable();
            // $table->string('reference', 255)->nullable();
            // $table->unsignedBigInteger('doctor_id')->nullable()->index();
            // $table->unsignedBigInteger('symptom_id')->nullable()->index();
            // $table->timestamps();
            // $table->foreign('case_reference_id')->references('id')->on('case_references')->onDelete('set null');
            // $table->foreign('patient_id')->references('id')->on('patients')->onDelete('set null');
            // $table->foreign('generated_by')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('doctor_id')->references('id')->on('doctor')->onDelete('set null');
            // $table->foreign('symptom_id')->references('id')->on('symptoms')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opd_details');
    }
};