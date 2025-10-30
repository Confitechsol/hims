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
        Schema::create('doctor_shift_time', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->string('day', 20)->nullable()->index();

            $table->unsignedBigInteger('staff_id')->nullable()->index();

            $table->unsignedBigInteger('doctor_global_shift_id')->nullable()->index();

            $table->time('start_time')->nullable();

            $table->time('end_time')->nullable();
            $table->timestamps();

           // $table->foreign('staff_id')->references('id')->on('staff')->onDelete('set null');
           // $table->foreign('doctor_global_shift_id')->references('id')->on('doctor_global_shift')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_shift_time');
    }
};