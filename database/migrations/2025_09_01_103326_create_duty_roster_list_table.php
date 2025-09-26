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
        Schema::create('duty_roster_list', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->unsignedBigInteger('duty_roster_shift_id')->index();

            $table->date('duty_roster_start_date')->index();

            $table->date('duty_roster_end_date')->index();

            $table->integer('duty_roster_total_day');
            $table->timestamps();

            //$table->foreign('duty_roster_shift_id')->references('id')->on('duty_roster_shift')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duty_roster_list');
    }
};