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
        Schema::create('radiology_parameterdetails', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->unsignedBigInteger('radiology_id')->nullable()->index();
            $table->unsignedBigInteger('radiology_parameter_id')->nullable()->index();
            $table->timestamps();
            $table->foreign('radiology_id')->references('id')->on('radio')->onDelete('set null');
            $table->foreign('radiology_parameter_id')->references('id')->on('radiology_parameter')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radiology_parameterdetails');
    }
};