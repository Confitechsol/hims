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
        Schema::create('pathology_parameterdetails', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->unsignedBigInteger('pathology_id')->nullable()->index();
            $table->unsignedBigInteger('pathology_parameter_id')->nullable()->index();
            $table->timestamps();
            $table->foreign('pathology_id')->references('id')->on('pathology')->onDelete('cascade');
            $table->foreign('pathology_parameter_id')->references('id')->on('pathology_parameter')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pathology_parameterdetails');
    }
};