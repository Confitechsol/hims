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
        Schema::create('ipd_details', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->unsignedBigInteger('patient_id')->nullable()->index();
            $table->unsignedBigInteger('case_reference_id')->nullable()->index();
            $table->string('height', 5)->nullable();
            $table->string('weight', 5)->nullable();
            $table->string('pulse', 200);
            $table->string('temperature', 200);
            $table->string('respiration', 200);
            $table->string('bp', 20)->nullable();
            $table->unsignedBigInteger('bed')->nullable()->index();
            $table->unsignedBigInteger('bed_group_id')->nullable()->index();
            $table->string('case_type', 100);
            $table->string('casualty', 100);
            $table->longText('symptoms');
            $table->string('known_allergies', 200)->nullable();
            $table->string('patient_old', 50);
            $table->text('note')->nullable();
            $table->string('refference', 200);
            $table->unsignedBigInteger('cons_doctor')->nullable()->index();
            $table->unsignedBigInteger('organisation_id')->nullable();
            $table->string('credit_limit', 100);
            $table->string('payment_mode', 100);
            $table->dateTime('date')->nullable();
            $table->string('discharged', 200);
            $table->string('live_consult', 50)->nullable();
            $table->unsignedBigInteger('generated_by')->nullable();
            $table->integer('is_antenatal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipd_details');
    }
};