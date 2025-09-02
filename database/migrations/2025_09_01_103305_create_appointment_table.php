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
        Schema::create('appointment', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->unsignedBigInteger('case_reference_id')->nullable();
            $table->unsignedBigInteger('visit_details_id')->nullable();
            $table->dateTime('date')->nullable();
            $table->string('priority', 100);
            $table->string('specialist', 100);
            $table->unsignedBigInteger('doctor')->nullable();
            $table->string('amount', 200);
            $table->text('message')->nullable();
            $table->string('appointment_status', 11)->nullable();
            $table->string('source', 100);
            $table->string('is_opd', 10);
            $table->string('is_ipd', 10);
            $table->unsignedBigInteger('doctor_shift_time_id')->nullable();
            $table->unsignedBigInteger('doctor_global_shift_id')->nullable();
            $table->integer('is_queue')->nullable();
            $table->dateTime('created_time')->nullable();
            $table->dateTime('rejected_time')->nullable();
            $table->string('live_consult', 50)->nullable();
            $table->string('live_consult_link')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment');
    }
};
