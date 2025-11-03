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
        Schema::create('pathology_report_parameterdetails', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->unsignedBigInteger('pathology_report_id')->nullable()->index();
            $table->unsignedBigInteger('pathology_parameterdetail_id')->nullable()->index();
            $table->string('pathology_report_value', 200);
            $table->timestamps();
            $table->foreign('pathology_report_id')->references('id')->on('pathology_report')->onDelete('cascade');
            $table->foreign('pathology_parameterdetail_id')->references('id')->on('pathology_parameter_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pathology_report_parameterdetails');
    }
};