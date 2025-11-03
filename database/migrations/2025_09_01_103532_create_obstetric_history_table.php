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
        Schema::create('obstetric_history', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->unsignedBigInteger('patient_id');
            $table->string('place_of_delivery', 250)->index();
            $table->string('pregnancy_duration', 250)->index();
            $table->string('pregnancy_complications', 250)->index();
            $table->string('birth_weight', 250)->index();
            $table->string('gender', 100)->index();
            $table->string('infant_feeding', 250)->index();
            $table->string('alive_dead', 50)->index();
            $table->date('date')->nullable()->index();
            $table->string('death_cause', 250)->index();
            $table->text('previous_medical_history');
            $table->text('special_instruction');
            $table->timestamps();
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obstetric_history');
    }
};