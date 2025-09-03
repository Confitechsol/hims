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
        Schema::create('ipd_prescription_test', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_id', 8);
            $table->unsignedBigInteger('ipd_prescription_basic_id')->nullable()->index();
            $table->unsignedBigInteger('pathology_id')->nullable()->index();
            $table->unsignedBigInteger('radiology_id')->nullable()->index();
            $table->timestamps();
            $table->foreign('ipd_prescription_basic_id')->references('id')->on('ipd_prescription_basic')->onDelete('cascade');
            $table->foreign('pathology_id')->references('id')->on('pathology')->onDelete('cascade');
            $table->foreign('radiology_id')->references('id')->on('radiology')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipd_prescription_test');
    }
};