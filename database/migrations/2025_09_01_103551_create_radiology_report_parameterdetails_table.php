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
        Schema::create('radiology_report_parameterdetails', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->string('branch_id', 8);
            $table->unsignedBigInteger('radiology_report_id')->nullable()->index();
            $table->unsignedBigInteger('radiology_parameterdetail_id')->nullable()->index();
            $table->timestamps();
            $table->foreign('radiology_report_id')->references('id')->on('radiology_report')->onDelete('set null');
            $table->foreign('radiology_parameterdetail_id')->references('id')->on('radiology_parameterdetails')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radiology_report_parameterdetails');
    }
};