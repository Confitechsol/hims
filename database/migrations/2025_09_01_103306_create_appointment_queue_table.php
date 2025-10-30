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
        Schema::create('appointment_queue', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->integer('position')->nullable();
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->date('date')->default('2021-01-11');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_queue');
    }
};
