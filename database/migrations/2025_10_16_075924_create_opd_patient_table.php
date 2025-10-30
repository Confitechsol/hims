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
        Schema::create('opd_patient', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id');
            $table->integer('opd_id');
            $table->bigInteger('doctor_id')->unsigned();
            $table->timestamps();

            $table->foreign('patient_id')
                ->references('id')
                ->on('patients')
                ->onDelete('cascade');

            $table->foreign('opd_id')
                ->references('id')
                ->on('opd_details')
                ->onDelete('cascade');

            $table->foreign('doctor_id')
                ->references('id')
                ->on('doctor')
                ->onDelete('cascade');

            // Prevent duplicates
            // $table->unique(['patient_id', 'opd_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opd_patient');
    }
};
